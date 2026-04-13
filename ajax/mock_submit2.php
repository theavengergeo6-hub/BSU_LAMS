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
    'cart' => json_encode(['2' => ['quantity' => 1]])
];
require 'reservation_submit.php';
?>
