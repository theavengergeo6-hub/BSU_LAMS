<?php
require('inc/header.php');
?>
<!-- Hero Section -->
<div class="container-fluid px-0 mb-5 relative">
    <div style="background: linear-gradient(rgba(183, 28, 28, 0.8), rgba(0, 0, 0, 0.7)), url('assets/images/placeholder.png') center/cover; padding: 100px 0;">
        <div class="container text-center text-white fade-in-up">
            <h1 class="display-3 fw-bold mb-3">BSU Laboratory Asset Management</h1>
            <p class="lead mb-4">Efficient monitoring and borrowing of kitchen tools, equipment, and linens.</p>
            <div>
                <a href="<?= BASE_URL ?>/reserve.php" class="btn btn-primary btn-lg px-5 py-3 fw-bold shadow">
                    Make a Reservation
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="container mb-5">
    <div class="row text-center fade-in-up">
        <?php
        $stats = [
            ['title' => 'Total Categories', 'icon' => 'bi-tags', 'count' => 0],
            ['title' => 'Registered Items', 'icon' => 'bi-box-seam', 'count' => 0],
            ['title' => 'Pending Reservations', 'icon' => 'bi-calendar-date', 'count' => 0]
        ];
        
        $cat_q = mysqli_query($con, "SELECT COUNT(*) AS cnt FROM lab_categories");
        if($cat_q) $stats[0]['count'] = mysqli_fetch_assoc($cat_q)['cnt'];
        
        $item_q = mysqli_query($con, "SELECT COUNT(*) AS cnt FROM lab_items");
        if($item_q) $stats[1]['count'] = mysqli_fetch_assoc($item_q)['cnt'];
        
        $res_q = mysqli_query($con, "SELECT COUNT(*) AS cnt FROM lab_reservations WHERE status='Pending'");
        if($res_q) $stats[2]['count'] = mysqli_fetch_assoc($res_q)['cnt'];

        foreach ($stats as $s) {
            echo "
            <div class='col-lg-4 col-md-6 mb-4'>
                <div class='card text-center p-4 border-0 shadow-sm custom-card h-100'>
                    <div class='card-body'>
                        <i class='bi {$s['icon']} text-danger display-4 mb-3 d-inline-block'></i>
                        <h4 class='mb-2 fw-bold text-dark w-100'>{$s['count']}</h4>
                        <p class='text-muted m-0 fs-5'>{$s['title']}</p>
                    </div>
                </div>
            </div>
            ";
        }
        ?>
    </div>
</div>

<!-- Information Section -->
<div class="container mb-5 p-5 bg-white rounded shadow-sm custom-card">
    <div class="row align-items-center">
        <div class="col-md-6 mb-4 mb-md-0">
            <h2 class="fw-bold mb-4 text-danger border-bottom pb-2">About the Laboratory</h2>
            <p class="fs-5 text-secondary lh-lg mb-4">
                The BSU LAMS handles inventory tracking and borrowing records for the Hot Kitchen, Cold Kitchen, Food & Beverages Service, and Laundry classes. It streamlines the reservation workflow for students and limits equipment loss.
            </p>
            <ul class="list-unstyled lh-lg fs-5">
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
