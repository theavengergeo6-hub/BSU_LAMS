<?php
require_once __DIR__ . '/../config.php';
$current_page = basename($_SERVER['PHP_SELF']);
$logo_url = BASE_URL . '/assets/images/logo.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BSU Laboratory Asset Management System</title>
    <?php include __DIR__ . '/link.php'; ?>
</head>
<body class="bg-light">

    <!-- Header / Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white px-lg-3 py-lg-2 shadow-sm sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand me-5 fw-bold fs-4 text-danger d-flex align-items-center" href="<?= BASE_URL ?>/index.php">
                <img src="<?= $logo_url ?>" alt="BSU Logo" width="35" height="35" class="me-2" onerror="this.src='https://via.placeholder.com/35x35/b71c1c/fff?text=BSU'">
                <span>LAMS</span>
            </a>
            <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page == 'index.php' ? 'active text-danger' : 'text-dark' ?> fw-medium me-2" href="<?= BASE_URL ?>/index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page == 'reserve.php' ? 'active text-danger' : 'text-dark' ?> fw-medium me-2" href="<?= BASE_URL ?>/reserve.php">Make Reservation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page == 'my_reservations.php' ? 'active text-danger' : 'text-dark' ?> fw-medium" href="<?= BASE_URL ?>/my_reservations.php">My Reservations</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <!-- Admin login removed for security; admins should access directly via the admin URL -->
                </div>
            </div>
        </div>
    </nav>
