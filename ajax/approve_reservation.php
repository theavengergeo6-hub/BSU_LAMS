<?php
/**
 * ajax/approve_reservation.php
 * Approves a reservation with per-item approved quantities.
 *
 * POST params:
 *   reservation_id  int
 *   approved[]      associative: res_item_id => approved_qty
 *
 * Logic:
 *  1. Validate each approved qty <= requested qty AND <= available stock
 *  2. Save approved_quantity to lab_reservation_items
 *  3. Deduct ONLY the approved quantity from lab_items.available_quantity
 *  4. Update lab_reservations.status = 'Approved'
 *  All inside a transaction so it's atomic.
 */
require_once('../config.php'); // pulls in $con
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

$reservation_id = (int) ($_POST['reservation_id'] ?? 0);
$approved = $_POST['approved'] ?? [];   // [res_item_id => qty]

if (!$reservation_id || empty($approved)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing reservation ID or item data.']);
    exit;
}

// Verify reservation exists and is still Pending
$res_check = mysqli_query($con, "SELECT id, status FROM lab_reservations WHERE id = $reservation_id");
$reservation = mysqli_fetch_assoc($res_check);
if (!$reservation) {
    echo json_encode(['status' => 'error', 'message' => 'Requisition not found.']);
    exit;
}
if (strtolower($reservation['status']) !== 'pending') {
    echo json_encode(['status' => 'error', 'message' => 'Requisition is no longer pending (status: ' . $reservation['status'] . ').']);
    exit;
}

// ── Fetch reservation date & time for time-slot validation ───────────────────
$res_info_q = mysqli_query($con, "SELECT reservation_date, reservation_time FROM lab_reservations WHERE id = $reservation_id");
$res_info = mysqli_fetch_assoc($res_info_q);
$res_date = $res_info['reservation_date'];
$res_time = $res_info['reservation_time'];

// ── Validate ALL items before touching the database ──────────────────────────
$validated = [];
foreach ($approved as $res_item_id => $qty) {
    $res_item_id = (int) $res_item_id;
    $qty = (int) $qty;

    if ($res_item_id <= 0)
        continue;

    // Fetch the reservation item + total stock + how many are already committed
    // for the SAME date & time slot (by other Approved/Ongoing reservations)
    $item_q = mysqli_query($con, "
        SELECT ri.id, ri.item_id, ri.requested_quantity,
               i.item_name, i.total_quantity,
               COALESCE((
                   SELECT SUM(ri2.approved_quantity)
                   FROM lab_reservation_items ri2
                   JOIN lab_reservations r2 ON ri2.reservation_id = r2.id
                   WHERE ri2.item_id = i.id
                     AND r2.reservation_date = '$res_date'
                     AND r2.reservation_time = '$res_time'
                     AND r2.status IN ('Approved', 'Ongoing')
                     AND r2.id != $reservation_id
               ), 0) AS already_committed
        FROM lab_reservation_items ri
        JOIN lab_items i ON ri.item_id = i.id
        WHERE ri.id = $res_item_id
          AND ri.reservation_id = $reservation_id
    ");
    $item = mysqli_fetch_assoc($item_q);

    if (!$item) {
        echo json_encode(['status' => 'error', 'message' => "Item record #$res_item_id not found or doesn't belong to this requisition."]);
        exit;
    }

    $requested         = (int) $item['requested_quantity'];
    $total             = (int) $item['total_quantity'];
    $already_committed = (int) $item['already_committed'];
    $time_slot_avail   = $total - $already_committed;

    if ($qty < 0) {
        echo json_encode(['status' => 'error', 'message' => "Approved quantity for '{$item['item_name']}' cannot be negative."]);
        exit;
    }
    if ($qty > $requested) {
        echo json_encode(['status' => 'error', 'message' => "Approved quantity for '{$item['item_name']}' ($qty) exceeds requested quantity ($requested)."]);
        exit;
    }
    if ($qty > $time_slot_avail) {
        echo json_encode(['status' => 'error', 'message' => "Only $time_slot_avail of '{$item['item_name']}' available for this time slot ($res_time on $res_date). {$already_committed} already committed to another group."]);
        exit;
    }

    $validated[] = [
        'res_item_id' => $res_item_id,
        'item_id'     => (int) $item['item_id'],
        'approved_qty'=> $qty,
        'item_name'   => $item['item_name'],
    ];
}

// ── All good — run inside a transaction ──────────────────────────────────────
mysqli_begin_transaction($con);

try {
    foreach ($validated as $v) {
        // 1. Save approved_quantity on the reservation item row
        $upd = mysqli_query($con, "
            UPDATE lab_reservation_items
            SET approved_quantity = {$v['approved_qty']}
            WHERE id = {$v['res_item_id']}
        ");
        if (!$upd)
            throw new Exception("Failed to update approved qty for item #{$v['res_item_id']}: " . mysqli_error($con));

        // 2. Deduct ONLY the approved quantity from inventory (not the full requested qty)
        if ($v['approved_qty'] > 0) {
            $deduct = mysqli_query($con, "
                UPDATE lab_items
                SET available_quantity = available_quantity - {$v['approved_qty']}
                WHERE id = {$v['item_id']}
                  AND available_quantity >= {$v['approved_qty']}
            ");
            if (!$deduct || mysqli_affected_rows($con) === 0) {
                throw new Exception("Stock deduction failed for '{$v['item_name']}' — stock may have changed. Please refresh and try again.");
            }
        }
    }

    // 3. Update reservation status to Approved
    $approve = mysqli_query($con, "
        UPDATE lab_reservations
        SET status = 'Approved'
        WHERE id = $reservation_id
    ");
    if (!$approve)
        throw new Exception("Failed to update reservation status: " . mysqli_error($con));

    mysqli_commit($con);

    echo json_encode([
        'status' => 'success',
        'message' => 'Requisition approved successfully. Inventory updated with approved quantities.'
    ]);

} catch (Exception $e) {
    mysqli_rollback($con);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}