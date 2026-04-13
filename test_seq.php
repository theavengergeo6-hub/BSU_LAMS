<?php
require 'c:\xampp\htdocs\BSU_Kitchen\config.php';
$date_prefix = date('Ymd');
$query = "SELECT reservation_no FROM lab_reservations WHERE reservation_no LIKE 'LAB-{$date_prefix}-%' ORDER BY id DESC";
$q_res = mysqli_query($con, $query);
while($row = mysqli_fetch_assoc($q_res)) {
    echo $row['reservation_no'] . "<br>";
}
?>
