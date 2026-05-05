<?php
require('../config.php');
$q = mysqli_query($con, "SELECT id, item_name, total_quantity, available_quantity FROM lab_items WHERE item_name LIKE '%Blender%'");
while($row = mysqli_fetch_assoc($q)) {
    echo $row['id'] . " | " . $row['item_name'] . " | " . $row['total_quantity'] . "\n";
}
?>
