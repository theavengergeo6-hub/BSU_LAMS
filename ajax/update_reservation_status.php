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
        $update_fields = "status='$new_status'";
        
        // When setting to Ongoing, start the 3-hour timer
        if (strtolower($new_status) == 'ongoing') {
            $update_fields .= ", ongoing_at = NOW(), cooldown_until = DATE_ADD(NOW(), INTERVAL 3 HOUR), stock_restored = 0";
        }
        
        $con->query("UPDATE lab_reservations SET $update_fields WHERE id=$id");
        $admin_id = $_SESSION['adminId'] ?? 1;
        
        // Return stock only if items were actually deducted (Approved or Ongoing).
        // Pending reservations never deduct stock, so never return from them.
        if (strtolower($new_status) == 'denied' && (in_array(strtolower($old_status), ['approved', 'ongoing']))) {
            $items_q = $con->query("SELECT item_id, approved_quantity FROM lab_reservation_items WHERE reservation_id=$id AND approved_quantity > 0");
            while($item = $items_q->fetch_assoc()) {
                $item_id = $item['item_id'];
                $qty = $item['approved_quantity'];
                
                // Return stock immediately for Denied
                $con->query("UPDATE lab_items SET available_quantity = available_quantity + $qty WHERE id=$item_id");
                
                // Log return
                $remarks = "Requisition $res_no cancelled/denied after approval (returned +$qty)";
                $stmt = $con->prepare("INSERT INTO lab_item_logs (item_id, change_type, quantity, remarks, performed_by) VALUES (?, '+', ?, ?, ?)");
                // Fixed column name from quantity_change to quantity based on original schema
                $stmt->bind_param("iisi", $item_id, $qty, $remarks, $admin_id);
                $stmt->execute();
            }
            $con->query("UPDATE lab_reservations SET stock_restored = 1 WHERE id=$id");
        }
        
        $con->commit();
        echo json_encode(['status'=>'success']);
    } catch(Exception $e) {
        $con->rollback();
        echo json_encode(['status'=>'error', 'message'=>$e->getMessage()]);
    }
}
?>
