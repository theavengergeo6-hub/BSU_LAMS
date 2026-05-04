<?php
require_once('../config.php');
require_once('../inc/auth.php');
require_once('../inc/cron_cooldown.php');
adminLogin();

// Run availability checks periodically (on each admin page hit)
runCooldownCron($con);

// Prevent browser caching to ensure user cannot go back after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - KLRS</title>
    <?php require('../inc/link.php'); ?>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --bsu-red: #dc3545;
            --bsu-red-dark: #b02a37;
            --sidebar-width: 260px;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
        }
        .admin-wrapper {
            display: flex;
        }
        
        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            z-index: 1000;
            transition: all 0.3s ease;
        }
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
        }
        .sidebar-header h2 {
            color: var(--bsu-red);
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 0;
        }
        .sidebar-header p {
            color: #666;
            font-size: 0.8rem;
            margin-bottom: 0;
        }
        .sidebar-nav {
            padding: 1rem 0;
        }
        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.8rem 1.5rem;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }
        .sidebar-nav a i {
            width: 24px;
            color: #666;
            transition: color 0.3s ease;
            font-size: 1.2rem;
        }
        .sidebar-nav a:hover {
            background: #fff5f5;
            color: var(--bsu-red);
        }
        .sidebar-nav a:hover i {
            color: var(--bsu-red);
        }
        .sidebar-nav a.active {
            background: #fff5f5;
            color: var(--bsu-red);
            font-weight: 600;
            border-left: 4px solid var(--bsu-red);
        }
        .sidebar-nav a.active i {
            color: var(--bsu-red);
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background: #f8f9fa;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        .top-bar {
            background: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .page-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
        }
        .user-menu {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        .user-info {
            text-align: right;
            border-right: 1px solid #eee;
            padding-right: 1.5rem;
        }
        .user-name {
            font-weight: 600;
            color: #333;
            font-size: 0.9rem;
        }
        .user-role {
            color: #888;
            font-size: 0.75rem;
        }
        .btn-logout {
            background: #fff5f5;
            color: var(--bsu-red);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .btn-logout:hover {
            background: var(--bsu-red);
            color: white;
        }

        /* Mobile Adjustments */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--bsu-red);
            cursor: pointer;
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .menu-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>

<div class="admin-wrapper">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2>KLRS</h2>
            <p>Admin Control Panel</p>
        </div>
        <nav class="sidebar-nav">
            <a href="index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i>
                Dashboard
            </a>
            <a href="reservations.php" class="<?= $current_page == 'reservations.php' ? 'active' : '' ?>">
                <i class="bi bi-calendar-check"></i>
                Requisitions
            </a>
            <a href="inventory.php" class="<?= $current_page == 'inventory.php' ? 'active' : '' ?>">
                <i class="bi bi-box-seam"></i>
                Inventory
            </a>
            <a href="item_logs.php" class="<?= $current_page == 'item_logs.php' ? 'active' : '' ?>">
                <i class="bi bi-clock-history"></i>
                Item Logs
            </a>
            <a href="settings.php" class="<?= $current_page == 'settings.php' ? 'active' : '' ?>">
                <i class="bi bi-sliders"></i>
                Settings
            </a>
            <a href="breakage_reports.php" class="<?= $current_page == 'breakage_reports.php' ? 'active' : '' ?>">
                <i class="bi bi-journal-x"></i>
                Breakage Reports
            </a>
            <div style="height: 1px; background: #eee; margin: 1rem 0;"></div>
            <a href="logout.php" style="color: #dc3545;">
                <i class="bi bi-box-arrow-right" style="color: #dc3545;"></i>
                Logout
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-bar">
            <div class="d-flex align-items-center gap-3">
                <button class="menu-toggle" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <div class="page-title"><i class="bi bi-gear-fill me-2 text-danger"></i>Control Panel</div>
            </div>
            <div class="user-menu">
                <div class="user-info">
                    <div class="user-name"><?= $_SESSION['adminUsername'] ?? 'Admin' ?></div>
                    <div class="user-role">Super Administrator</div>
                </div>
                <a href="logout.php" class="btn-logout">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>LOGOUT</span>
                </a>
            </div>
        </div>
        
        <div class="p-4 pt-4">
            <div id="admin-alerts"></div>
            <script>
                function toggleSidebar() {
                    document.getElementById('sidebar').classList.toggle('active');
                }
            </script>
