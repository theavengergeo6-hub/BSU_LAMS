<?php
require('config.php');

$q = mysqli_query($con, "SELECT id, item_name, total_quantity, available_quantity FROM lab_items WHERE available_quantity > total_quantity");

echo "Items where Available > Total:\n";
echo str_pad("ID", 5) . " | " . str_pad("Item Name", 30) . " | " . str_pad("Total", 10) . " | " . str_pad("Available", 10) . "\n";
echo str_repeat("-", 65) . "\n";

while($row = mysqli_fetch_assoc($q)) {
    echo str_pad($row['id'], 5) . " | " . str_pad($row['item_name'], 30) . " | " . str_pad($row['total_quantity'], 10) . " | " . str_pad($row['available_quantity'], 10) . "\n";
}
?>
