<?php
require('../../config.php');
require('../../inc/auth.php');
adminLogin();

$query = "SELECT COUNT(*) as count FROM lab_reservations WHERE status = 'Pending'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($result);

header('Content-Type: application/json');
echo json_encode(['count' => (int)$row['count']]);
?>
