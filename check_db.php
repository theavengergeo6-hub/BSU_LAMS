<?php
require('config.php');
$res = mysqli_query($con, "DESCRIBE lab_reservations");
while($row = mysqli_fetch_assoc($res)) {
    print_r($row);
}
?>
