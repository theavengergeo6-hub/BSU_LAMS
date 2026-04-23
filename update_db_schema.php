<?php
include 'config.php';
$sql = "ALTER TABLE `lab_items` ADD COLUMN `acquisition_date` DATE DEFAULT NULL AFTER `description` CASCADE;";
// Wait, CASCADE is not for ALTER TABLE columns in MySQL usually.
$sql = "ALTER TABLE `lab_items` ADD COLUMN `acquisition_date` DATE DEFAULT NULL AFTER `description` ";
if(mysqli_query($con, $sql)){
    echo "acquisition_date added\n";
} else {
    echo "Error adding acquisition_date: " . mysqli_error($con) . "\n";
}

$sql = "ALTER TABLE `lab_item_logs` ADD COLUMN `is_disposal` TINYINT(1) DEFAULT 0 AFTER `remarks`, ADD COLUMN `disposal_reason` VARCHAR(255) DEFAULT NULL AFTER `is_disposal` ";
if(mysqli_query($con, $sql)){
    echo "is_disposal and disposal_reason added\n";
} else {
    echo "Error adding log columns: " . mysqli_error($con) . "\n";
}

$sql = "ALTER TABLE `lab_reservations` ADD COLUMN `cooldown_until` DATETIME DEFAULT NULL AFTER `completed_at` ";
if(mysqli_query($con, $sql)){
    echo "cooldown_until added\n";
} else {
    echo "Error adding cooldown_until: " . mysqli_error($con) . "\n";
}
?>
