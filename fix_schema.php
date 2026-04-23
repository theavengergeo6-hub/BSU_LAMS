<?php
require('config.php');

$queries = [
    "ALTER TABLE lab_reservations ADD COLUMN IF NOT EXISTS reservation_end_time VARCHAR(50) AFTER reservation_time",
    "ALTER TABLE lab_reservations ADD COLUMN IF NOT EXISTS stock_restored TINYINT(1) DEFAULT 0",
    "ALTER TABLE lab_item_logs ADD COLUMN IF NOT EXISTS is_disposal TINYINT(1) DEFAULT 0",
    "ALTER TABLE lab_item_logs ADD COLUMN IF NOT EXISTS disposal_reason VARCHAR(255) DEFAULT NULL",
    "ALTER TABLE lab_items ADD COLUMN IF NOT EXISTS acquisition_date DATE DEFAULT NULL"
];

foreach ($queries as $q) {
    if (mysqli_query($con, $q)) {
        echo "Success: $q <br>";
    } else {
        echo "Error: " . mysqli_error($con) . " <br>";
    }
}
?>
