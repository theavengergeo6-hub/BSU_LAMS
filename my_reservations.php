<?php
require('inc/header.php');

$search_email = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($con, $_GET['status']) : '';

$where_clause = "";
if($search_email) {
    $where_clause .= " AND (user_email = '$search_email' OR reservation_no = '$search_email')";
}
if($status_filter) {
    if($where_clause) $where_clause .= " AND status = '$status_filter'";
    else $where_clause .= " AND status = '$status_filter'";
}
?>

<div class="container mt-5 mb-5 p-lg-5 p-4 bg-white rounded shadow-sm custom-card">
    <h3 class="fw-bold mb-4 text-danger border-bottom pb-2">My Requisitions</h3>
    
    <form class="row g-3 mb-5 fade-in-up" method="GET">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control bg-light" placeholder="Enter Registration Email or Requisition No." value="<?= htmlspecialchars($search_email) ?>" required>
        </div>
        <div class="col-md-4">
            <select name="status" class="form-select bg-light">
                <option value="">All Statuses</option>
                <option value="Pending" <?= $status_filter == 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Approved" <?= $status_filter == 'Approved' ? 'selected' : '' ?>>Approved</option>
                <option value="Ongoing" <?= $status_filter == 'Ongoing' ? 'selected' : '' ?>>Ongoing</option>
                <option value="Completed" <?= $status_filter == 'Completed' ? 'selected' : '' ?>>Completed</option>
                <option value="Denied" <?= $status_filter == 'Denied' ? 'selected' : '' ?>>Denied</option>
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary w-100 shadow"><i class="bi bi-search me-2"></i>Search</button>
        </div>
    </form>

    <?php if($search_email): ?>
        <?php
        $q = "SELECT * FROM lab_reservations WHERE 1=1 $where_clause ORDER BY id DESC";
        $res = mysqli_query($con, $q);
        if(mysqli_num_rows($res) > 0) { ?>
            
            <!-- Desktop Table View -->
            <div class="table-responsive fade-in-up d-none d-md-block">
                <table class="table table-hover table-striped align-middle border">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th>Requisition No.</th>
                            <th>Date & Time</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        mysqli_data_seek($res, 0);
                        while($row = mysqli_fetch_assoc($res)) {
                            $status_bg = "bg-warning text-dark";
                            if(strtolower($row['status']) == 'approved') $status_bg = "bg-info text-white";
                            if(strtolower($row['status']) == 'ongoing') $status_bg = "bg-primary";
                            if(strtolower($row['status']) == 'completed') $status_bg = "bg-success";
                            if(strtolower($row['status']) == 'denied') $status_bg = "bg-danger";
                            
                            echo "
                            <tr>
                                <td class='fw-bold text-danger'>{$row['reservation_no']}</td>
                                <td>". date('M d, Y', strtotime($row['reservation_date'])) ." at {$row['reservation_time']}</td>
                                <td>{$row['subject']}</td>
                                <td><span class='badge {$status_bg}'>{$row['status']}</span></td>
                                <td>
                                    <button class='btn btn-sm btn-outline-danger shadow-sm' onclick='viewDetails({$row['id']})'>
                                        <i class='bi bi-eye'></i> View
                                    </button>
                                </td>
                            </tr>
                            ";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards View -->
            <div class="d-md-none fade-in-up">
                <?php
                mysqli_data_seek($res, 0);
                while($row = mysqli_fetch_assoc($res)) {
                    $status_bg = "bg-warning text-dark";
                    if(strtolower($row['status']) == 'approved') $status_bg = "bg-info text-white";
                    if(strtolower($row['status']) == 'ongoing') $status_bg = "bg-primary";
                    if(strtolower($row['status']) == 'completed') $status_bg = "bg-success";
                    if(strtolower($row['status']) == 'denied') $status_bg = "bg-danger";
                    ?>
                    <div class='card mb-3 shadow-sm border-0' style="border-radius: 12px; border: 1px solid #e5e7eb !important;">
                        <div class='card-header bg-white pt-3 pb-2 border-bottom d-flex justify-content-between align-items-center' style="border-radius: 12px 12px 0 0;">
                            <span class='fw-bold text-danger' style="font-family: 'DM Sans', sans-serif; font-size: 1rem;"><?= htmlspecialchars($row['reservation_no']) ?></span>
                            <span class='badge <?= $status_bg ?>' style="font-size: 0.75rem; padding: 5px 10px; border-radius: 20px;"><?= htmlspecialchars($row['status']) ?></span>
                        </div>
                        <div class='card-body pt-3'>
                            <div class='d-flex align-items-center mb-2 text-muted small'>
                                <i class="bi bi-calendar3 me-2 text-danger"></i>
                                <?= date('M d, Y', strtotime($row['reservation_date'])) ?> &bull; <?= htmlspecialchars($row['reservation_time']) ?>
                            </div>
                            <div class='d-flex align-items-center mb-3 text-dark small fw-bold'>
                                <i class="bi bi-book me-2 text-danger"></i>
                                <?= htmlspecialchars($row['subject']) ?>
                            </div>
                            <button class='btn btn-outline-danger w-100 shadow-sm' style="border-radius: 8px; font-weight: 600; padding: 10px;" onclick='viewDetails(<?= $row['id'] ?>)'>
                                <i class='bi bi-eye me-1'></i> View Details
                            </button>
                        </div>
                    </div>
                <?php } ?>
            </div>

        <?php } else { ?>
            <div class="alert border shadow-sm text-center py-5 fade-in-up" style="background:#fafafa; border-radius: 12px;">
                <i class="bi bi-inbox fs-1 d-block mb-3" style="color: #cbd5e1;"></i>
                <p class="text-muted mb-0 fw-medium">No requisitions found for current filter.</p>
            </div>
        <?php } ?>
    <?php elseif(empty($_GET['search'])): ?>
        <div class="alert alert-info border-0 shadow-sm text-center py-4 fade-in-up">
            <i class="bi bi-info-circle fs-4 d-block mb-2"></i>
            Please enter your email or requisition number to view your requisitions.
        </div>
    <?php endif; ?>
</div>

<!-- Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-card-text me-2"></i>Requisition Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="detailsModalBody">
                <div class="text-center py-5"><div class="spinner-border text-danger"></div></div>
            </div>
        </div>
    </div>
</div>

<script>
function viewDetails(id) {
    let modal = new bootstrap.Modal(document.getElementById('detailsModal'));
    modal.show();
    
    // We can fetch from an ajax script later if needed, but for simplicity, 
    // we'll make a quick ajax fetch here since the requirements didn't specify an ajax file
    fetch(`ajax/get_reservation_details.php?id=${id}`)
    .then(res => res.text())
    .then(html => {
        document.getElementById('detailsModalBody').innerHTML = html;
    })
    .catch(err => {
        document.getElementById('detailsModalBody').innerHTML = "<div class='alert alert-danger'>Failed to load details.</div>";
    });
}
</script>

<?php require('inc/footer.php'); ?>
