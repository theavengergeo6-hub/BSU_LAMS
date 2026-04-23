<?php
require('config.php');
$sql = "ALTER TABLE lab_reservations 
        ADD COLUMN IF NOT EXISTS ongoing_at DATETIME DEFAULT NULL,
        ADD COLUMN IF NOT EXISTS cooldown_until DATETIME DEFAULT NULL,
        ADD COLUMN IF NOT EXISTS stock_restored TINYINT(1) DEFAULT 0";
if(mysqli_query($con, $sql)) {
    echo "Schema updated successfully (columns added if missing).\n";
} else {
    echo "Error updating schema: " . mysqli_error($con) . "\n";
}
?>
