<?php
require(__DIR__ . '/../config.php');

echo "Diagnosing Database...\n";

// 1. Check and fix columns
$sql = "ALTER TABLE lab_reservations 
        ADD COLUMN IF NOT EXISTS ongoing_at DATETIME DEFAULT NULL,
        ADD COLUMN IF NOT EXISTS cooldown_until DATETIME DEFAULT NULL,
        ADD COLUMN IF NOT EXISTS stock_restored TINYINT(1) DEFAULT 0";
if(mysqli_query($con, $sql)) {
    echo "- Columns checked/added successfully.\n";
} else {
    echo "- Error adding columns: " . mysqli_error($con) . "\n";
}

// 2. Check Beer Mug item data
echo "\nChecking 'Beer Mug' status:\n";
$q = mysqli_query($con, "SELECT id, item_name, total_quantity, available_quantity FROM lab_items WHERE item_name LIKE '%Beer Mug%'");
while($row = mysqli_fetch_assoc($q)) {
    $itemId = $row['id'];
    echo "ID: {$row['id']} | Name: {$row['item_name']} | Total: {$row['total_quantity']} | Current Avail: {$row['available_quantity']}\n";
    
    // Check reservations for this item
    $res_q = mysqli_query($con, "
        SELECT r.id, r.reservation_no, r.status, ri.requested_quantity, ri.approved_quantity 
        FROM lab_reservations r
        JOIN lab_reservation_items ri ON r.id = ri.reservation_id
        WHERE ri.item_id = $itemId
    ");
    while($r = mysqli_fetch_assoc($res_q)) {
        echo "   -> Res #{$r['reservation_no']} | Status: {$r['status']} | Req: {$r['requested_quantity']} | Appr: {$r['approved_quantity']}\n";
    }
}

// 3. Check for Ongoing/Approved on April 24
echo "\nReservations on April 24:\n";
$q2 = mysqli_query($con, "SELECT id, reservation_no, status, reservation_time FROM lab_reservations WHERE reservation_date = '2026-04-24'");
while($row = mysqli_fetch_assoc($q2)) {
    echo "Res #{$row['reservation_no']} | Status: {$row['status']} | Time: {$row['reservation_time']}\n";
}

?>
