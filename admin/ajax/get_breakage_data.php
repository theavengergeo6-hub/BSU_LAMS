<?php
require('../../config.php');
require('../../inc/auth.php');
require('../../includes/breakage_logger.php');
adminLogin();

header('Content-Type: application/json');

if (isset($_GET['filename'])) {
    $data = read_monthly_report($_GET['filename']);
    echo json_encode($data);
} else {
    echo json_encode([]);
}
