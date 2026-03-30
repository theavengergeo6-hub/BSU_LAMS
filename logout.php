<?php
require_once __DIR__ . '/inc/header.php';
session_start();
session_destroy();
header('Location: index.php');
exit;
?>
