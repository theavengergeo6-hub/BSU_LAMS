<?php
require('../config.php');
require('../inc/auth.php');
adminLogin();

if(isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Fetch reservation details first
    $q_res = "SELECT * FROM lab_reservations WHERE id = $id";
    $res_query = mysqli_query($con, $q_res);
    if(mysqli_num_rows($res_query) == 0) exit("Reservation not found.");
    $res_info = mysqli_fetch_assoc($res_query);
    
    $is_pending = $res_info['status'] === 'Pending';
    
    // Render the beautiful info header
    ?>
    <form id='approvalForm' onsubmit='submitApproval(event)'>
        <input type='hidden' name='reservation_id' value='<?= $id ?>'>
        
        <div class="row mb-4 bg-light p-4 rounded mx-0 border border-secondary border-opacity-10 shadow-sm" style="border-left: 5px solid <?= $is_pending ? 'var(--red)' : '#0d6efd' ?> !important;">
            <div class="col-md-6 mb-3">
                <span class="text-muted small text-uppercase fw-bold"><i class="bi bi-person me-1"></i> Student Name</span>
                <div class="fw-bold fs-5 text-dark"><?= $res_info['student_name'] ?></div>
            </div>
            <div class="col-md-6 mb-3">
                <span class="text-muted small text-uppercase fw-bold"><i class="bi bi-telephone me-1"></i> Contact Info</span>
                <div class="fw-medium text-dark"><?= $res_info['contact_number'] ?></div>
                <div class="fw-medium text-dark"><a href="mailto:<?= $res_info['student_email'] ?>" class="text-decoration-none text-danger"><?= $res_info['student_email'] ?></a></div>
            </div>
            <div class="col-md-6 mb-3">
                <span class="text-muted small text-uppercase fw-bold"><i class="bi bi-journal-text me-1"></i> Subject & Course</span>
                <div class="fw-bold text-dark"><?= $res_info['subject'] ?></div>
                <div class="fw-medium text-dark"><span class="badge bg-secondary"><?= $res_info['course_section'] ?></span></div>
            </div>
            <div class="col-md-6 mb-3">
                <span class="text-muted small text-uppercase fw-bold"><i class="bi bi-diagram-3 me-1"></i> Station Setup</span>
                <div class="fw-bold text-dark"><?= $res_info['station'] ?></div>
                <div class="fw-medium text-muted small">Batch: <?= $res_info['batch'] ?></div>
            </div>
            <div class="col-12 mt-2 pt-3 border-top border-secondary border-opacity-25">
                <span class="text-muted small text-uppercase fw-bold"><i class="bi bi-calendar-event me-1"></i> Requested Schedule</span>
                <div class="fw-bold fs-5 text-danger"><?= date('l, F d, Y', strtotime($res_info['reservation_date'])) ?> &nbsp;&bull;&nbsp; <?= $res_info['reservation_time'] ?></div>
            </div>
        </div>

        <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-box-seam me-2"></i>Requested Items</h6>
        <div class='table-responsive mb-4 shadow-sm rounded'>
            <table class='table table-bordered table-hover align-middle mb-0'>
                <thead class='table-dark'>
                    <tr>
                        <th class="ps-3 border-0">Item Name</th>
                        <th class='text-center border-0' width="120">Requested</th>
                        <th class='text-center border-0' width="120">Stock</th>
                        <th width='150' class='text-center border-0 pe-3'><?= $is_pending ? 'Approve Qty' : 'Approved' ?></th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
    <?php
    $q = "SELECT ri.id as ri_id, ri.item_id, ri.requested_quantity, ri.approved_quantity, i.item_name, i.available_quantity 
          FROM lab_reservation_items ri 
          JOIN lab_items i ON ri.item_id = i.id 
          WHERE ri.reservation_id = $id";
          
    $res = mysqli_query($con, $q);
    $can_approve = true;
    
    while($row = mysqli_fetch_assoc($res)) {
        $max_approve = min($row['requested_quantity'], $row['available_quantity']);
        $warning = ($row['requested_quantity'] > $row['available_quantity']) && $is_pending ? "<div class='text-danger fw-bold small mt-1'><i class='bi bi-exclamation-triangle-fill'></i> Shortage Warning</div>" : "";
        if($row['available_quantity'] <= 0) $can_approve = false;
        
        $qty_cell = $is_pending 
            ? "<input type='number' name='approve_qty[{$row['ri_id']}]' class='form-control form-control-lg text-center bg-light border-secondary fw-bold shadow-none px-1' value='{$max_approve}' min='0' max='{$row['available_quantity']}' required>"
            : "<div class='fs-5 fw-bold text-primary text-center'>{$row['approved_quantity']}</div>";
        
        echo "<tr>
                <td class='fw-bold text-dark ps-3'>{$row['item_name']} {$warning}</td>
                <td class='text-center fw-bold fs-5 text-secondary'>{$row['requested_quantity']}</td>
                <td class='text-center text-success fs-5 fw-bold'>{$row['available_quantity']}</td>
                <td class='pe-3 py-3 align-middle'>
                    {$qty_cell}
                </td>
              </tr>";
    }
    
    echo "</tbody></table></div>
          <div class='text-end mt-4 pt-3 border-top'>
            <button type='button' class='btn btn-light shadow-sm px-4 me-2 border' data-bs-dismiss='modal'>Close Details</button>";
            
    if($is_pending) {
        echo "<button type='submit' class='btn btn-danger shadow px-5 fw-bold'><i class='bi bi-check-circle-fill me-2'></i>Approve Reservation</button>";
    }
    
    echo "</div></form>";
}
?>
