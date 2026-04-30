<?php
require('config.php');

$item_id = 6; // Blender
$q = mysqli_query($con, "SELECT * FROM lab_item_logs WHERE item_id = $item_id ORDER BY created_at DESC");

echo "Logs for Blender (ID 6):\n";
echo str_pad("ID", 5) . " | " . str_pad("Type", 5) . " | " . str_pad("Qty", 5) . " | " . str_pad("Remarks", 50) . " | " . str_pad("Date", 20) . "\n";
echo str_repeat("-", 95) . "\n";

while($row = mysqli_fetch_assoc($q)) {
    echo str_pad($row['id'], 5) . " | " . str_pad($row['change_type'], 5) . " | " . str_pad($row['quantity_change'], 5) . " | " . str_pad($row['remarks'], 50) . " | " . str_pad($row['created_at'], 20) . "\n";
}

// Also check the current item state
$it = mysqli_query($con, "SELECT * FROM lab_items WHERE id = $item_id");
$item = mysqli_fetch_assoc($it);
echo "\nCurrent State:\n";
echo "Total: " . $item['total_quantity'] . "\n";
echo "Available: " . $item['available_quantity'] . "\n";
?>
