<?php
require(__DIR__ . '/../config.php');

$date = '2026-04-24';
$start = '07:00';
$end = '10:00';

echo "Testing availability for $date $start-$end\n";

$q_items = mysqli_query($con, "SELECT id, item_name, total_quantity FROM lab_items WHERE item_name LIKE '%Blender%' OR item_name LIKE '%Beer Mug%'");
while($item = mysqli_fetch_assoc($q_items)) {
    $itemId = $item['id'];
    $name = $item['item_name'];
    $total = $item['total_quantity'];
    echo "\nChecking '$name' (ID $itemId) | Total: $total:\n";

    $query = "
        SELECT r.reservation_no, r.status, ri.approved_quantity, r.reservation_time,
               r.reservation_date
        FROM lab_reservation_items ri
        JOIN lab_reservations r ON ri.reservation_id = r.id
        WHERE ri.item_id = $itemId
          AND r.reservation_date = '$date'
    ";
    $res = mysqli_query($con, $query);
    while($row = mysqli_fetch_assoc($res)) {
        echo "Res: {$row['reservation_no']} | Status: {$row['status']} | Appr: {$row['approved_quantity']} | Time: {$row['reservation_time']}\n";
    }
}

$res = mysqli_query($con, $query);
if(!$res) die(mysqli_error($con));

while($row = mysqli_fetch_assoc($res)) {
    echo "Res: {$row['reservation_no']} | Status: {$row['status']} | Appr: {$row['approved_quantity']} | Start: {$row['reservation_time']} | WinEnd: {$row['window_end']} | Overlap: {$row['is_overlap']}\n";
}

?>
