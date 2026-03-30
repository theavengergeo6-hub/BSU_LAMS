<?php
require('header.php');

// Fetch Stats
$today = date('Y-m-d');
$stats = [
    'pending' => 0,
    'approved' => 0,
    'ongoing' => 0,
    'completed_today' => 0
];

$res1 = mysqli_query($con, "SELECT status, COUNT(*) as cnt, reservation_date FROM lab_reservations GROUP BY status, reservation_date");
while($r = mysqli_fetch_assoc($res1)) {
    if($r['status'] == 'Pending') $stats['pending'] += $r['cnt'];
    if($r['status'] == 'Approved') $stats['approved'] += $r['cnt'];
    if($r['status'] == 'Ongoing') $stats['ongoing'] += $r['cnt'];
    if($r['status'] == 'Completed' && $r['reservation_date'] == $today) $stats['completed_today'] += $r['cnt'];
}

// Fetch Low Stock
$low_stock = mysqli_query($con, "SELECT id, item_name, available_quantity, min_threshold FROM lab_items WHERE available_quantity < min_threshold AND total_quantity > 0 ORDER BY available_quantity ASC LIMIT 5");

// Fetch Recent Reservations
$recent_res = mysqli_query($con, "SELECT * FROM lab_reservations ORDER BY created_at DESC LIMIT 5");
?>

<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
    <h4 class="fw-bold mb-0">Dashboard</h4>
    <div>
        <a href="reservations.php" class="btn btn-outline-danger shadow-sm fw-medium"><i class="bi bi-eye"></i> View All Reservations</a>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- Stats Cards -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm border-start border-4 border-warning custom-card h-100">
            <div class="card-body">
                <h6 class="text-muted fw-semibold">Pending</h6>
                <h2 class="mb-0 fw-bold"><?= $stats['pending'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm border-start border-4 border-info custom-card h-100">
            <div class="card-body">
                <h6 class="text-muted fw-semibold">Approved</h6>
                <h2 class="mb-0 fw-bold"><?= $stats['approved'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm border-start border-4 border-primary custom-card h-100">
            <div class="card-body">
                <h6 class="text-muted fw-semibold">Ongoing</h6>
                <h2 class="mb-0 fw-bold"><?= $stats['ongoing'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm border-start border-4 border-success custom-card h-100">
            <div class="card-body">
                <h6 class="text-muted fw-semibold">Completed Today</h6>
                <h2 class="mb-0 fw-bold"><?= $stats['completed_today'] ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm custom-card">
            <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center border-bottom">
                <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history text-danger me-2"></i>Recent Reservations</h5>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Student</th>
                            <th>Date/Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($recent_res)): ?>
                            <tr>
                                <td class="fw-bold text-danger"><?= $row['reservation_no'] ?></td>
                                <td><?= $row['student_name'] ?></td>
                                <td><?= date('M d, Y', strtotime($row['reservation_date'])) ?> <?= $row['reservation_time'] ?></td>
                                <td><span class="badge bg-secondary"><?= $row['status'] ?></span></td>
                            </tr>
                        <?php endwhile; ?>
                        <?php if(mysqli_num_rows($recent_res) == 0): ?>
                            <tr><td colspan="4" class="text-center py-3 text-muted">No reservations found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm custom-card border-top border-4 border-danger">
            <div class="card-header bg-white p-3 border-bottom text-danger">
                <h5 class="mb-0 fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Low Stock Alerts</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <?php while($item = mysqli_fetch_assoc($low_stock)): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <?= $item['item_name'] ?>
                            <span class="badge bg-danger rounded-pill">Only <?= $item['available_quantity'] ?> left</span>
                        </li>
                    <?php endwhile; ?>
                    <?php if(mysqli_num_rows($low_stock) == 0): ?>
                        <li class="list-group-item text-center px-0 text-success"><i class="bi bi-check-circle me-1"></i> All stock levels are good!</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        
        <div class="mt-4">
            <h6 class="text-muted fw-semibold mb-3">Quick Actions</h6>
            <div class="d-grid gap-2">
                <a href="inventory.php" class="btn btn-danger shadow-sm text-start"><i class="bi bi-plus-circle me-2"></i> Add New Item</a>
                <a href="reservations.php" class="btn btn-outline-secondary shadow-sm text-start bg-white"><i class="bi bi-calendar me-2"></i> Manage Schedule</a>
            </div>
        </div>
    </div>
</div>

        </div> <!-- End Col 10 -->
    </div> <!-- End Row -->
</div> <!-- End Container Fluid -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
