<?php
require('config.php');

// 1. Find all items and their total quantity
$items = [];
$q = mysqli_query($con, "SELECT id, item_name, total_quantity, available_quantity FROM lab_items");
while($row = mysqli_fetch_assoc($q)) {
    $items[$row['id']] = $row;
    $items[$row['id']]['calculated_out'] = 0;
}

// 2. Find all items that are currently "out" (stock_restored = 0)
// This includes Approved, Ongoing, and Completed/Denied that are awaiting cooldown
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

echo "Inventory Audit:\n";
echo str_pad("ID", 5) . " | " . str_pad("Item Name", 30) . " | " . str_pad("Total", 10) . " | " . str_pad("Out", 10) . " | " . str_pad("ShouldBe", 10) . " | " . str_pad("Current", 10) . " | Status\n";
echo str_repeat("-", 100) . "\n";

$to_fix = [];

foreach($items as $id => $item) {
    $should_be = $item['total_quantity'] - $item['calculated_out'];
    $current = $item['available_quantity'];
    $diff = $should_be - $current;
    
    $status = ($diff == 0) ? "OK" : "ERROR ($diff)";
    
    if ($diff != 0) {
        echo str_pad($id, 5) . " | " . str_pad($item['item_name'], 30) . " | " . str_pad($item['total_quantity'], 10) . " | " . str_pad($item['calculated_out'], 10) . " | " . str_pad($should_be, 10) . " | " . str_pad($current, 10) . " | $status\n";
        $to_fix[$id] = $should_be;
    }
}

if (empty($to_fix)) {
    echo "\nAll items are consistent.\n";
} else {
    echo "\nFound " . count($to_fix) . " inconsistent items.\n";
}
?>
