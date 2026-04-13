<?php
require('../config.php');
require('../inc/auth.php');
adminLogin();

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'], $_POST['status'])) {
    $id = (int)$_POST['id'];
    $new_status = mysqli_real_escape_string($con, $_POST['status']);
    
    // Check old status
    $q = mysqli_query($con, "SELECT status, reservation_no FROM lab_reservations WHERE id=$id");
    if(mysqli_num_rows($q) == 0) {
        echo json_encode(['status'=>'error', 'message'=>'Requisition not found']);
        exit;
    }
    $row_res = mysqli_fetch_assoc($q);
    $old_status = $row_res['status'];
    $res_no = $row_res['reservation_no'];
    
    $con->begin_transaction();
    try {
        $con->query("UPDATE lab_reservations SET status='$new_status' WHERE id=$id");
        $admin_id = $_SESSION['adminId'] ?? 1;
        
        // If completed or denied (from approved/ongoing stage), return items to inventory
        if ( (strtolower($new_status) == 'completed' || strtolower($new_status) == 'denied') && in_array(strtolower($old_status), ['approved', 'ongoing', 'pending']) ) 
        {
            $items_q = $con->query("SELECT item_id, approved_quantity FROM lab_reservation_items WHERE reservation_id=$id AND approved_quantity > 0");
            while($item = $items_q->fetch_assoc()) {
                $item_id = $item['item_id'];
                $qty = $item['approved_quantity'];
                
                // Return stock
                $con->query("UPDATE lab_items SET available_quantity = available_quantity + $qty WHERE id=$item_id");
                
                // Log return
                $action_word = $new_status == 'Completed' ? 'completed' : 'cancelled';
                $remarks = "Requisition $res_no $action_word (returned +$qty)";
                $stmt = $con->prepare("INSERT INTO lab_item_logs (item_id, change_type, quantity_change, remarks, performed_by) VALUES (?, '+', ?, ?, ?)");
                $stmt->bind_param("iisi", $item_id, $qty, $remarks, $admin_id);
                $stmt->execute();
            }
        }
        
        $con->commit();
        echo json_encode(['status'=>'success']);
    } catch(Exception $e) {
        $con->rollback();
        echo json_encode(['status'=>'error', 'message'=>$e->getMessage()]);
    }
}
?>
