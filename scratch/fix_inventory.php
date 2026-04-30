<?php
require('config.php');

$items = [];
$q = mysqli_query($con, "SELECT id, item_name, total_quantity, available_quantity FROM lab_items");
while($row = mysqli_fetch_assoc($q)) {
    $items[$row['id']] = $row;
    $items[$row['id']]['calculated_out'] = 0;
}

$q_out = mysqli_query($con, "
    SELECT ri.item_id, SUM(ri.approved_quantity) as total_out
    FROM lab_reservation_items ri
    JOIN lab_reservations r ON ri.reservation_id = r.id
    WHERE r.stock_restored = 0
    GROUP BY ri.item_id
");

while($row = mysqli_fetch_assoc($q_out)) {
    if (isset($items[$row['item_id']])) {
        $items[$row['item_id']]['calculated_out'] = (int)$row['total_out'];
    }
}

echo "Fixing Inventory Inconsistencies...\n";

foreach($items as $id => $item) {
    $should_be = $item['total_quantity'] - $item['calculated_out'];
    $current = $item['available_quantity'];
    
    if ($should_be != $current) {
        echo "Updating Item #$id ({$item['item_name']}): $current -> $should_be\n";
        mysqli_query($con, "UPDATE lab_items SET available_quantity = $should_be WHERE id = $id");
        
        // Log the fix
        $diff = $should_be - $current;
        $type = ($diff > 0) ? '+' : '-';
        $abs_diff = abs($diff);
        $remarks = "System correction: Fixed available quantity discrepancy (was $current, should be $should_be)";
        $stmt = $con->prepare("INSERT INTO lab_item_logs (item_id, change_type, quantity_change, remarks, performed_by) VALUES (?, ?, ?, ?, 0)");
        $stmt->bind_param("isis", $id, $type, $abs_diff, $remarks);
        $stmt->execute();
    }
}

echo "Done.\n";
?>
