<?php
require('inc/header.php');
?>
<!-- Hero Section -->
<div class="container-fluid px-0 mb-5 relative">
    <div style="background: linear-gradient(rgba(183, 28, 28, 0.8), rgba(0, 0, 0, 0.7)), url('assets/images/placeholder.png') center/cover; padding: 100px 0;">
        <div class="container text-center text-white fade-in-up">
            <h1 class="display-4 fw-bold mb-3">BSU Laboratory Asset Management</h1>
            <p class="mb-4 opacity-75">Efficient monitoring and borrowing of kitchen tools, equipment, and linens.</p>
            <div>
                <a href="<?= BASE_URL ?>/reserve.php" class="btn btn-primary btn-lg px-5 py-3 fw-bold shadow">
                    Make a Reservation
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Information Section -->
<div class="container mb-5 p-5 bg-white rounded shadow-sm custom-card">
    <div class="row align-items-center">
        <div class="col-md-6 mb-4 mb-md-0">
            <h2 class="fw-bold mb-4 text-danger border-bottom pb-2">About the Laboratory</h2>
            <p class="text-secondary lh-lg mb-4">
                The BSU LAMS handles inventory tracking and borrowing records for the Hot Kitchen, Cold Kitchen, Food & Beverages Service, and Laundry classes. It streamlines the reservation workflow for students and limits equipment loss.
            </p>
            <ul class="list-unstyled lh-lg">
                <li><i class="bi bi-check-circle-fill text-danger me-2"></i> Real-time inventory tracking</li>
                <li><i class="bi bi-check-circle-fill text-danger me-2"></i> Easy online reservations</li>
                <li><i class="bi bi-check-circle-fill text-danger me-2"></i> Clear accountability records</li>
            </ul>
        </div>
        <div class="col-md-6 text-center">
            <img src="<?= BASE_URL ?>/assets/images/placeholder.png" alt="Lab Setup" class="img-fluid rounded shadow-sm" style="max-height: 350px; object-fit: cover;">
        </div>
    </div>
</div>

<?php require('inc/footer.php'); ?>
