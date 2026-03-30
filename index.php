<?php
require('inc/header.php');
?>
<!-- Hero Section -->
<div class="container-fluid px-0 mb-5 hero-wrapper" style="min-height: 60vh; display: flex; align-items: center; position: relative; background: #000;">
    <div class="hero-bg" style="position: absolute; top:0; left:0; width: 100%; height: 100%; background: url('<?= BASE_URL ?>/assets/images/placeholder.png') center/cover; opacity: 0.5;"></div>
    <div style="position: absolute; top:0; left:0; width: 100%; height: 100%; background: linear-gradient(135deg, rgba(204, 0, 0, 0.85) 0%, rgba(15, 23, 42, 0.9) 100%); z-index: 1;"></div>
    <div class="container text-center text-white" style="position: relative; z-index: 2; padding: 100px 0;">
        <span class="badge bg-warning text-dark mb-3 px-4 py-2 fw-bold text-uppercase rounded-pill shadow-sm fade-in-up stagger-1" style="letter-spacing: 1px;">Batangas State University</span>
        <h1 class="display-3 fw-bold mb-4 fade-in-up stagger-2" style="text-shadow: 0 10px 20px rgba(0,0,0,0.3);">Laboratory Asset Management</h1>
        <p class="mb-5 opacity-75 fs-4 fw-light mx-auto fade-in-up stagger-3" style="max-width: 800px;">Efficient monitoring and seamless borrowing of kitchen tools, high-end equipment, and specialized linens.</p>
        <div class="fade-in-up" style="animation-delay: 0.4s;">
            <a href="<?= BASE_URL ?>/reserve.php" class="btn btn-primary btn-lg px-5 py-3 fw-bold shadow-lg fs-5">
                <i class="bi bi-calendar2-plus me-2"></i> Make a Reservation
            </a>
        </div>
    </div>
</div>

<!-- Information Section -->
<div class="container mb-5 p-lg-5 p-4 bg-white rounded shadow-sm custom-card border-0 fade-in-up stagger-1">
    <div class="row align-items-center g-5">
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                <div class="bg-danger text-white rounded-circle d-flex justify-content-center align-items-center me-3 shadow" style="width: 50px; height: 50px;">
                    <i class="bi bi-info-lg fs-3"></i>
                </div>
                <h2 class="fw-bold m-0 text-danger">About the Laboratory</h2>
            </div>
            <p class="text-secondary lh-lg mb-4 fs-5">
                The BSU LAMS handles inventory tracking and borrowing records for the Hot Kitchen, Cold Kitchen, Food & Beverages Service, and Laundry classes. It streamlines the reservation workflow for students and limits equipment loss.
            </p>
            <ul class="list-unstyled lh-lg mt-4">
                <li class="mb-3 d-flex align-items-center p-3 rounded bg-light hover-lift transition">
                    <i class="bi bi-check2-circle text-danger fs-4 me-3"></i> 
                    <span class="fs-5 fw-medium text-dark">Real-time inventory tracking</span>
                </li>
                <li class="mb-3 d-flex align-items-center p-3 rounded bg-light hover-lift transition">
                    <i class="bi bi-laptop text-danger fs-4 me-3"></i> 
                    <span class="fs-5 fw-medium text-dark">Easy online reservations</span>
                </li>
                <li class="mb-3 d-flex align-items-center p-3 rounded bg-light hover-lift transition">
                    <i class="bi bi-shield-check text-danger fs-4 me-3"></i> 
                    <span class="fs-5 fw-medium text-dark">Clear accountability records</span>
                </li>
            </ul>
        </div>
        <div class="col-md-6 text-center position-relative">
            <div class="position-absolute w-100 h-100 bg-danger rounded-4 opacity-10" style="top: 15px; left: 15px; z-index: 0;"></div>
            <img src="<?= BASE_URL ?>/assets/images/placeholder.png" alt="Lab Setup" class="img-fluid rounded-4 shadow-lg position-relative" style="z-index: 1; max-height: 450px; object-fit: cover; width: 100%;">
        </div>
    </div>
</div>

<?php require('inc/footer.php'); ?>
