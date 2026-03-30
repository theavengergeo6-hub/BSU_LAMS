<?php
require_once('../config.php');
require_once('../inc/auth.php');
adminLogin();
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - BSU LAMS</title>
    <?php require('../inc/link.php'); ?>
</head>
<body class="bg-light">

<div class="container-fluid bg-danger text-light p-3 d-flex align-items-center justify-content-between sticky-top shadow-sm">
    <h3 class="mb-0 fw-bold"><i class="bi bi-gear-fill me-2"></i>BSU LAMS Admin</h3>
    <div>
        <span class="me-3"><i class="bi bi-person-circle me-1"></i><?= $_SESSION['adminUsername'] ?? 'Admin' ?></span>
        <a href="<?= BASE_URL ?>/logout.php" class="btn btn-sm btn-light fw-bold text-danger px-3 shadow-none">LOGOUT</a>
    </div>
</div>

<div class="container-fluid px-0">
    <div class="row w-100 m-0">
        <!-- Sidebar -->
        <div class="col-lg-2 bg-dark text-white p-0 border-top border-3 border-secondary" style="min-height: 100vh;">
            <div class="p-3 bg-secondary bg-opacity-25 pb-4">
                <nav class="nav flex-column mt-3 gap-2">
                    <a class="nav-link <?= $current_page == 'index.php' ? 'active bg-danger text-white rounded shadow-sm' : 'text-white-50 hover-white' ?>" href="index.php">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                    <a class="nav-link <?= $current_page == 'reservations.php' ? 'active bg-danger text-white rounded shadow-sm' : 'text-white-50 hover-white' ?>" href="reservations.php">
                        <i class="bi bi-calendar-check me-2"></i>Reservations
                    </a>
                    <a class="nav-link <?= $current_page == 'inventory.php' ? 'active bg-danger text-white rounded shadow-sm' : 'text-white-50 hover-white' ?>" href="inventory.php">
                        <i class="bi bi-box-seam me-2"></i>Inventory
                    </a>
                    <a class="nav-link <?= $current_page == 'item_logs.php' ? 'active bg-danger text-white rounded shadow-sm' : 'text-white-50 hover-white' ?>" href="item_logs.php">
                        <i class="bi bi-clock-history me-2"></i>Item Logs
                    </a>
                    <a class="nav-link <?= $current_page == 'settings.php' ? 'active bg-danger text-white rounded shadow-sm' : 'text-white-50 hover-white' ?>" href="settings.php">
                        <i class="bi bi-sliders me-2"></i>Settings
                    </a>
                </nav>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-10 p-4">
            <!-- Alert container for ajax msgs -->
            <div id="admin-alerts"></div>
