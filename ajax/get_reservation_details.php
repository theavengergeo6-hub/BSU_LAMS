<?php
require('../config.php');

if(isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Fetch Reservation Info
    $res_q = mysqli_query($con, "SELECT * FROM lab_reservations WHERE id = $id");
    if(mysqli_num_rows($res_q) == 0) {
        echo "<div class='alert alert-danger'>Reservation not found.</div>";
        exit;
    }
    
    $res_row = mysqli_fetch_assoc($res_q);
    
    echo "<h5 class='border-bottom pb-2 fw-bold text-danger mb-3'>Reservation No: {$res_row['reservation_no']}</h5>";
    echo "<div class='row mb-4 bg-light p-3 rounded'>
            <div class='col-sm-6 mb-2'><strong>Name:</strong> {$res_row['student_name']}</div>
            <div class='col-sm-6 mb-2'><strong>Email:</strong> {$res_row['student_email']}</div>
            <div class='col-sm-6 mb-2'><strong>Contact:</strong> {$res_row['contact_number']}</div>
            <div class='col-sm-6 mb-2'><strong>Course & Sec:</strong> {$res_row['course_section']}</div>
            <div class='col-sm-6 mb-2'><strong>Subject:</strong> {$res_row['subject']}</div>
            <div class='col-sm-6 mb-2'><strong>Station:</strong> {$res_row['station']}</div>
            <div class='col-sm-6 mb-2'><strong>Batch:</strong> {$res_row['batch']}</div>
            <div class='col-sm-6 mb-2'><strong>Date:</strong> " . date('M d, Y', strtotime($res_row['reservation_date'])) . "</div>
            <div class='col-sm-6 mb-2'><strong>Time:</strong> {$res_row['reservation_time']}</div>
            <div class='col-sm-6 mb-2'><strong>Status:</strong> <span class='badge bg-secondary'>{$res_row['status']}</span></div>
          </div>";
    
    // Fetch Items
    echo "<h5 class='border-bottom pb-2 fw-bold text-danger mb-3'>Requested Items</h5>
          <table class='table table-bordered table-striped'>
            <thead class='bg-light'>
                <tr>
                    <th>Item Name</th>
                    <th>Requested Qty</th>
                    <th>Approved Qty</th>
                </tr>
            </thead>
            <tbody>";
            
    $items_q = mysqli_query($con, "SELECT ri.requested_quantity, ri.approved_quantity, i.item_name 
                                    FROM lab_reservation_items ri 
                                    JOIN lab_items i ON ri.item_id = i.id 
                                    WHERE ri.reservation_id = $id");
                                    
    if(mysqli_num_rows($items_q) > 0) {
        while($item = mysqli_fetch_assoc($items_q)) {
            echo "<tr>
                    <td>{$item['item_name']}</td>
                    <td>{$item['requested_quantity']}</td>
                    <td>{$item['approved_quantity']}</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='3' class='text-center text-muted'>No items found.</td></tr>";
    }
    
    echo "</tbody></table>";
}
?>
