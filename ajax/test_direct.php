<?php
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'contact' => '1234567890',
    'subject' => 'Math',
    'course' => 'BSCS',
    'station' => '1',
    'batch' => '1',
    'date' => '2026-10-10',
    'time' => '07:00 AM',
require '../config.php';
$valid_id = 2; // I know 2 exists
$_POST['cart'] = json_encode([$valid_id => ['quantity' => 1]]);
echo "DEBUG: cart=" . $_POST['cart'] . "<br>";
require 'reservation_submit.php';
?>
