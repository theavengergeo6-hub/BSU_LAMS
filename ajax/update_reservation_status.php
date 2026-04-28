<?php
require('../config.php');
require('../inc/auth.php');
adminLogin();

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'], $_POST['status'])) {
    $id = (int)$_POST['id'];
    $new_status = mysqli_real_escape_string($con, $_POST['status']);
    
    // Check old status
    $q = mysqli_query($con, "SELECT status, reservation_no, stock_restored FROM lab_reservations WHERE id=$id");
    if(mysqli_num_rows($q) == 0) {
        echo json_encode(['status'=>'error', 'message'=>'Requisition not found']);
        exit;
    }
    $row_res = mysqli_fetch_assoc($q);
    $old_status = $row_res['status'];
    $res_no = $row_res['reservation_no'];
    
    $con->begin_transaction();
    try {
        $update_fields = "status='$new_status'";
        $admin_id = $_SESSION['adminId'] ?? 1;
        $needs_restoration = false;
        
        // When setting to Ongoing, start the 3-hour timer
        if (strtolower($new_status) == 'ongoing') {
            $update_fields .= ", ongoing_at = NOW(), cooldown_until = DATE_ADD(NOW(), INTERVAL 3 HOUR), stock_restored = 0";
        }
        
        // Determine if we need to restore stock immediately (for Denied or Completed)
        if (in_array(strtolower($new_status), ['denied', 'completed']) && 
            in_array(strtolower($old_status), ['approved', 'ongoing']) && 
            $row_res['stock_restored'] == 0) {
            
            $needs_restoration = true;
            $update_fields .= ", stock_restored = 1";
        }
        
        $con->query("UPDATE lab_reservations SET $update_fields WHERE id=$id");
        
        if ($needs_restoration) {
            $items_q = $con->query("SELECT item_id, approved_quantity FROM lab_reservation_items WHERE reservation_id=$id AND approved_quantity > 0");
            while($item = $items_q->fetch_assoc()) {
                $item_id = $item['item_id'];
                $qty = $item['approved_quantity'];
                
                // Return stock immediately
                $con->query("UPDATE lab_items SET available_quantity = available_quantity + $qty WHERE id=$item_id");
                
                // Log return
                $action_name = (strtolower($new_status) == 'denied') ? "cancelled/denied" : "completed";
                $remarks = "Requisition $res_no $action_name (returned +$qty)";
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
