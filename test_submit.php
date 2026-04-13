<?php
$url = 'http://localhost/BSU_Kitchen/ajax/reservation_submit.php';
$data = [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'contact' => '1234567890',
    'subject' => 'Math',
    'course' => 'BSCS',
    'station' => '1',
    'batch' => '1',
    'date' => '2026-10-10',
    'time' => '07:00 AM',
    'cart' => json_encode(['1' => ['quantity' => 1]])
];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$res = curl_exec($ch);
echo "Response: " . $res;
?>
