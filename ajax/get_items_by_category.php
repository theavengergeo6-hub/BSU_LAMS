<?php
/**
 * ajax/get_items_by_category.php
 *
 * Returns items for a given category_id.
 * When `date` and `time` are supplied (from reserve.php), the available_quantity
 * returned is TIME-SLOT-AWARE: total_quantity minus what is already committed
 * (Approved or Ongoing) for that exact date+time combination.
 *
 * This means:
 *  - 8:30 AM batch using 5 Ice Cream Scoopers → 2:00 PM students still see FULL qty.
 *  - A 2nd group booking 8:30 AM will see REDUCED qty if items are already taken.
 */
require('../config.php');

if (isset($_GET['category_id'])) {
    $cat_id = (int) $_GET['category_id'];
    $date   = isset($_GET['date']) ? mysqli_real_escape_string($con, $_GET['date']) : '';
    $time   = isset($_GET['time']) ? mysqli_real_escape_string($con, $_GET['time']) : '';

    if ($date && $time) {
        // ── TIME-SLOT-AWARE QUERY ────────────────────────────────────────────
        // available = total_quantity - already committed for this date+time slot
        $query = "
            SELECT i.*,
                   GREATEST(0,
                       i.total_quantity - COALESCE((
                           SELECT SUM(ri.approved_quantity)
                           FROM lab_reservation_items ri
                           JOIN lab_reservations r ON ri.reservation_id = r.id
                           WHERE ri.item_id = i.id
                             AND r.reservation_date = '$date'
                             AND r.reservation_time = '$time'
                             AND r.status IN ('Approved', 'Ongoing')
                       ), 0)
                   ) AS available_quantity
            FROM lab_items i
            WHERE i.category_id = $cat_id
            ORDER BY i.item_name
        ";
    } else {
        // ── FALLBACK: show raw available_quantity when no time slot selected ─
        $query = "SELECT * FROM lab_items WHERE category_id = $cat_id ORDER BY item_name";
    }

    $res  = mysqli_query($con, $query);
    $data = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $row['available_quantity'] = (int) $row['available_quantity'];
        $data[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($data);
}
?>
