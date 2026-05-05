<?php
require('../config.php');
$item_id = 6;
$q = mysqli_query($con, "SELECT * FROM lab_item_logs WHERE item_id = $item_id ORDER BY created_at DESC LIMIT 10");
while($row = mysqli_fetch_assoc($q)) {
    echo $row['id'] . " | " . $row['change_type'] . " | " . $row['quantity_change'] . " | " . $row['remarks'] . " | " . $row['created_at'] . "\n";
}
?>
