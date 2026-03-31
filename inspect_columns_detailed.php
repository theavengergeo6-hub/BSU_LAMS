<?php
require('config.php');
$res = mysqli_query($con, "SHOW COLUMNS FROM lab_reservations");
while($row = mysqli_fetch_assoc($res)) {
    echo $row['Field'] . " | " . $row['Null'] . " | " . $row['Default'] . "\n";
}
?>
