<?php
require('header.php');

$status_tab = isset($_GET['tab']) ? $_GET['tab'] : 'Pending';
$statuses = ['Pending', 'Approved', 'Ongoing', 'Completed', 'Denied'];

if(!in_array($status_tab, $statuses)) $status_tab = 'Pending';
?>

<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
    <h4 class="fw-bold mb-0 text-danger"><i class="bi bi-calendar-check me-2"></i>Manage Reservations</h4>
</div>

<!-- Tabs -->
<ul class="nav nav-tabs fw-medium border-bottom-0 shadow-sm rounded-top bg-white px-3 pt-3" id="resTabs" role="tablist">
    <?php foreach($statuses as $st): ?>
        <li class="nav-item" role="presentation">
            <a class="nav-link <?= $status_tab == $st ? 'active border-danger border-bottom-0 text-danger bg-light fw-bold' : 'text-dark border-0' ?>" href="?tab=<?= $st ?>"><?= $st ?></a>
        </li>
    <?php endforeach; ?>
</ul>

<!-- Content -->
<div class="card border-0 shadow-sm custom-card rounded-0 rounded-bottom rounded-end table-responsive">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Res. No.</th>
                    <th>Student Info</th>
                    <th>Subject/Station</th>
                    <th>Schedule</th>
                    <th>Items</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $q = "SELECT * FROM lab_reservations WHERE status = '$status_tab' ORDER BY reservation_date ASC, reservation_time ASC";
                $res = mysqli_query($con, $q);
                
                if(mysqli_num_rows($res) > 0):
                    while($row = mysqli_fetch_assoc($res)):
                        $id = $row['id'];
                        // items string
                        $items_q = mysqli_query($con, "SELECT ri.requested_quantity, i.item_name FROM lab_reservation_items ri JOIN lab_items i ON ri.item_id = i.id WHERE ri.reservation_id = $id");
                        $items_str = "";
                        while($itm = mysqli_fetch_assoc($items_q)) {
                            $items_str .= "- {$itm['item_name']} ({$itm['requested_quantity']})<br>";
                        }
                ?>
                <tr>
                    <td class="fw-bold text-danger"><?= $row['reservation_no'] ?></td>
                    <td>
                        <div class="fw-bold"><?= $row['student_name'] ?></div>
                        <div class="small text-muted"><?= $row['student_email'] ?></div>
                        <div class="small text-muted"><?= $row['contact_number'] ?></div>
                        <span class="badge bg-secondary"><?= $row['course_section'] ?></span>
                    </td>
                    <td>
                        <div><?= $row['subject'] ?></div>
                        <div class="small text-muted"><?= $row['station'] ?> - <?= $row['batch'] ?></div>
                    </td>
                    <td>
                        <div class="fw-medium text-dark"><i class="bi bi-calendar me-1"></i><?= date('M d, Y', strtotime($row['reservation_date'])) ?></div>
                        <div class="small text-muted"><i class="bi bi-clock me-1"></i><?= $row['reservation_time'] ?></div>
                    </td>
                    <td class="small lh-sm text-secondary">
                        <?= $items_str ?>
                    </td>
                    <td class="text-center">
                        <?php if($status_tab == 'Pending'): ?>
                            <button class="btn btn-sm btn-success fw-bold shadow-sm mb-1 w-100" onclick="viewReservation(<?= $id ?>, '<?= $row['reservation_no'] ?>', true)"><i class="bi bi-check-lg me-1"></i>Review</button>
                            <button class="btn btn-sm btn-outline-danger fw-bold shadow-sm w-100" onclick="updateStatus(<?= $id ?>, 'Denied')"><i class="bi bi-x-lg me-1"></i>Deny</button>
                        <?php elseif($status_tab == 'Approved'): ?>
                            <button class="btn btn-sm btn-dark fw-bold shadow-sm mb-1 w-100" onclick="viewReservation(<?= $id ?>, '<?= $row['reservation_no'] ?>', false)"><i class="bi bi-eye me-1"></i>View Details</button>
                            <button class="btn btn-sm btn-primary fw-bold shadow-sm mb-1 w-100" onclick="updateStatus(<?= $id ?>, 'Ongoing')"><i class="bi bi-play-fill me-1"></i>Ongoing</button>
                            <button class="btn btn-sm btn-outline-danger fw-bold shadow-sm w-100" onclick="updateStatus(<?= $id ?>, 'Denied')"><i class="bi bi-x-lg me-1"></i>Deny</button>
                        <?php elseif($status_tab == 'Ongoing'): ?>
                            <button class="btn btn-sm btn-dark fw-bold shadow-sm mb-1 w-100" onclick="viewReservation(<?= $id ?>, '<?= $row['reservation_no'] ?>', false)"><i class="bi bi-eye me-1"></i>View Details</button>
                            <button class="btn btn-sm btn-success fw-bold shadow-sm w-100" onclick="updateStatus(<?= $id ?>, 'Completed')"><i class="bi bi-check-circle-fill me-1"></i>Complete</button>
                        <?php else: ?>
                            <button class="btn btn-sm btn-dark fw-bold shadow-sm mb-1 w-100" onclick="viewReservation(<?= $id ?>, '<?= $row['reservation_no'] ?>', false)"><i class="bi bi-eye me-1"></i>View Details</button>
                            <button class="btn btn-sm btn-secondary shadow-sm w-100 disabled text-white"><i class="bi bi-info-circle me-1"></i>Closed</button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php 
                    endwhile; 
                else: 
                ?>
                <tr><td colspan="6" class="text-center py-5 text-muted">No <?= $status_tab ?> reservations found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Approval -->
<div class="modal fade" id="approvalModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title fw-bold"><span id="modalTitleText"><i class="bi bi-card-checklist me-2"></i>Approve Reservation </span><span id="approveResNo"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="approvalModalBody">
                <div class="text-center py-5"><div class="spinner-border text-danger"></div></div>
            </div>
        </div>
    </div>
</div>

<script>
function viewReservation(id, resNo, isPending) {
    document.getElementById('approveResNo').textContent = resNo;
    document.getElementById('modalTitleText').innerHTML = isPending ? '<i class="bi bi-card-checklist me-2"></i>Approve Reservation ' : '<i class="bi bi-card-text me-2"></i>Reservation Details ';
    
    let modal = new bootstrap.Modal(document.getElementById('approvalModal'));
    modal.show();
    
    document.getElementById('approvalModalBody').innerHTML = '<div class="text-center py-5"><div class="spinner-border text-danger"></div></div>';
    
    fetch(`../ajax/get_approval_items.php?id=${id}`) // New ajax strictly for form
    .then(res => res.text())
    .then(html => {
        document.getElementById('approvalModalBody').innerHTML = html;
    });
}

function submitApproval(e) {
    e.preventDefault();
    let formData = new FormData(document.getElementById('approvalForm'));
    
    fetch('../ajax/approve_reservation.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            Swal.fire({
                icon: 'success', title: 'Reservation Approved', text: data.message, toast: true, position: 'top-end', timer: 3000, showConfirmButton: false
            }).then(() => location.reload());
        } else {
            alert("Error: " + data.message);
        }
    });
}

function updateStatus(id, newStatus) {
    Swal.fire({
        title: `Mark as ${newStatus}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#b71c1c',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, update it!'
    }).then((result) => {
        if(result.isConfirmed) {
            let fd = new FormData();
            fd.append('id', id);
            fd.append('status', newStatus);
            fetch('../ajax/update_reservation_status.php', {
                method: 'POST', body: fd
            }).then(res => res.json()).then(data => {
                if(data.status === 'success') location.reload();
                else alert(data.message);
            });
        }
    });
}
</script>

<?php require('footer.php'); ?>
