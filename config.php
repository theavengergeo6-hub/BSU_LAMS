<?php
// config.php
session_start();

// Define Base URL
if (!defined('BASE_URL')) {
    $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
    $script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $base_url .= ($script_name === '/') ? '' : $script_name;
    define('BASE_URL', rtrim($base_url, '/'));
}

// Database Configuration
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'bsu_lab_assets');

// Connect to Database
$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($con === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Define Upload Path
define('UPLOAD_PATH', __DIR__ . '/uploads/lab_items/');

// Helper functions (e.g., alert, redirect)
function redirect($url){
    echo "<script>window.location.href='$url';</script>";
    exit;
}

function alert($type, $msg){
    $bs_class = ($type == "success") ? "alert-success" : "alert-danger";
    echo <<<alert
    <div class="alert $bs_class alert-dismissible fade show custom-alert" role="alert">
        <strong class="me-3">$msg</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    alert;
}
?>
