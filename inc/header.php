<?php
/**
 * BSU Kitchen Laboratory Requisition System - Global Header & Navigation Navbar
 * 
 * Included at the top of client-side pages to maintain HTML wrappers and layout consistency.
 * Tasks:
 * 1. Checks active file name using $_SERVER['PHP_SELF'] to toggle '.active' classes.
 * 2. Defines the BatStateU brand header and navigation logo parameters.
 * 3. Triggers database-driven automatic stock cooldown recovery routines (`restoration.php`).
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../cron/restoration.php'; // Automated check to restore item stocks on expired bookings

// Determine filename of executing page for navigation highlights
$current_page = basename($_SERVER['PHP_SELF']);

// Define path to the official BatStateU institutional branding logo
$logo_url = BASE_URL . '/assets/images/logo.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BatStateU ARASOF-Nasugbu Kitchen Tools and Equipment Requisition System</title>
    
    <!-- Include CDN stylesheet stylesheets and custom styles stylesheet bundle -->
    <?php include __DIR__ . '/link.php'; ?>
</head>
<body class="bg-light">

    <!-- Global Responsive Navigation Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white px-lg-3 py-lg-2 shadow-sm sticky-top">
        <div class="container-fluid">
            <!-- Brand Identity (Logo + Brand Abbreviation) -->
            <a class="navbar-brand me-5 fw-bold fs-4 text-danger d-flex align-items-center" href="<?= BASE_URL ?>/index.php">
                <!-- Renders the official logo, falling back to a red placeholder if the file is missing -->
                <img src="<?= $logo_url ?>" alt="BSU Logo" width="35" height="35" class="me-2" onerror="this.src='https://via.placeholder.com/35x35/b71c1c/fff?text=BSU'">
                <span>KTERS</span>
            </a>
            
            <!-- Mobile Responsive Navigation Toggler -->
            <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Nav Links Collapsible Area -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <!-- Home Page Link -->
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page == 'index.php' ? 'active text-danger' : 'text-dark' ?> fw-medium me-2" href="<?= BASE_URL ?>/index.php">Home</a>
                    </li>
                    <!-- Requisition Catalog Link -->
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page == 'reserve.php' ? 'active text-danger' : 'text-dark' ?> fw-medium me-2" href="<?= BASE_URL ?>/reserve.php">Request Equipment</a>
                    </li>
                    <!-- Personal Requisitions History Link -->
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page == 'my_reservations.php' ? 'active text-danger' : 'text-dark' ?> fw-medium" href="<?= BASE_URL ?>/my_reservations.php">My Requisitions</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <!-- Admin login link removed for security purposes; custodians should navigate directly to the admin path -->
                </div>
            </div>
        </div>
    </nav>
