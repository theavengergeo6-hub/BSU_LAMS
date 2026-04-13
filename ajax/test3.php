<?php
require '../config.php';
$res = mysqli_query($con, "SELECT id FROM lab_items");
$items = [];
while($row = mysqli_fetch_assoc($res)) {
    $items[] = $row['id'];
}
echo "Items: " . implode(', ', $items) . "<br>";
$res2 = mysqli_query($con, "SELECT id FROM lab_reservations");
$resvs = [];
while($row = mysqli_fetch_assoc($res2)) {
    $resvs[] = $row['id'];
}
echo "Reservations: " . implode(', ', $resvs) . "<br>";
?>
