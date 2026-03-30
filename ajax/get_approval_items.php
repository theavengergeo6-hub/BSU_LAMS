<?php
require('../config.php');
require('../inc/auth.php');
adminLogin();

if(isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    echo "<form id='approvalForm' onsubmit='submitApproval(event)'>
            <input type='hidden' name='reservation_id' value='{$id}'>
            <div class='table-responsive mb-4'>
            <table class='table table-bordered table-striped align-middle'>
                <thead class='bg-light text-dark'>
                    <tr>
                        <th>Item Name</th>
                        <th class='text-center'>Requested Qty</th>
                        <th class='text-center'>Available Stock</th>
                        <th width='150' class='text-center'>Approve Qty</th>
                    </tr>
                </thead>
                <tbody>";
                
    $q = "SELECT ri.id as ri_id, ri.item_id, ri.requested_quantity, i.item_name, i.available_quantity 
          FROM lab_reservation_items ri 
          JOIN lab_items i ON ri.item_id = i.id 
          WHERE ri.reservation_id = $id";
          
    $res = mysqli_query($con, $q);
    $can_approve = true;
    
    while($row = mysqli_fetch_assoc($res)) {
        $max_approve = min($row['requested_quantity'], $row['available_quantity']);
        $warning = $row['requested_quantity'] > $row['available_quantity'] ? "<div class='text-danger small'>Not enough stock</div>" : "";
        if($row['available_quantity'] <= 0) $can_approve = false; // At least one item completely OOS might prevent full, but we let them partial approve
        
        echo "<tr>
                <td class='fw-medium'>{$row['item_name']} {$warning}</td>
                <td class='text-center fw-bold'>{$row['requested_quantity']}</td>
                <td class='text-center text-success fw-bold'>{$row['available_quantity']}</td>
                <td>
                    <input type='number' name='approve_qty[{$row['ri_id']}]' class='form-control form-control-sm text-center bg-light fw-bold' value='{$max_approve}' min='0' max='{$row['available_quantity']}' required>
                </td>
              </tr>";
    }
    
    echo "</tbody></table></div>
          <div class='text-end'>
            <button type='button' class='btn btn-secondary shadow-sm px-4 me-2' data-bs-dismiss='modal'>Cancel</button>
            <button type='submit' class='btn btn-danger shadow-sm px-5 fw-bold'>Approve Now</button>
          </div>
          </form>";
}
?>
