<?php
require(__DIR__ . '/../config.php');

echo "Cleaning up laboratory data...\n";

// Disable foreign key checks for clean truncation
mysqli_query($con, "SET FOREIGN_KEY_CHECKS = 0");

$tables = [
    'lab_reservations',
    'lab_reservation_items',
    'lab_item_logs',
    'lab_admin_notifications'
];

foreach ($tables as $t) {
    if (mysqli_query($con, "TRUNCATE TABLE $t")) {
        echo "- Table '$t' truncated.\n";
    } else {
        echo "- Error truncating '$t': " . mysqli_error($con) . "\n";
    }
}

// Reset available_quantity to total_quantity
if (mysqli_query($con, "UPDATE lab_items SET available_quantity = total_quantity")) {
    echo "- All item available_quantities reset to total_quantity.\n";
} else {
    echo "- Error resetting quantities: " . mysqli_error($con) . "\n";
}

mysqli_query($con, "SET FOREIGN_KEY_CHECKS = 1");

echo "Done. System is back to zero.\n";
?>
