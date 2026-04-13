<?php
require 'config.php';
$res = mysqli_query($con, "DESCRIBE lab_reservations");
while($row = mysqli_fetch_assoc($res)) {
    echo $row['Field'] . ' | Null: ' . $row['Null'] . ' | Default: ' . $row['Default'] . "<br>";
}
echo "<hr>";
$res2 = mysqli_query($con, "DESCRIBE lab_reservation_items");
while($row = mysqli_fetch_assoc($res2)) {
    echo $row['Field'] . ' | Null: ' . $row['Null'] . ' | Default: ' . $row['Default'] . "<br>";
}
?>
