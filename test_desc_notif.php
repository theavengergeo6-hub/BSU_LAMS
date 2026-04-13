<?php
require 'c:\xampp\htdocs\BSU_Kitchen\config.php';
$res = mysqli_query($con, "DESCRIBE lab_admin_notifications");
while($row = mysqli_fetch_assoc($res)) {
    echo $row['Field'] . "\n";
}
?>
