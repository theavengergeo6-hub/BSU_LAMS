<?php
require('../config.php');
require('../inc/auth.php');
adminLogin();

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reservation_id'], $_POST['approve_qty'])) {
    $res_id = (int)$_POST['reservation_id'];
    $approve_qty = $_POST['approve_qty'];
    
    // Get reservation no
    $q_res = mysqli_query($con, "SELECT reservation_no FROM lab_reservations WHERE id=$res_id");
    if(mysqli_num_rows($q_res) == 0) {
        echo json_encode(['status'=>'error', 'message'=>'Reservation not found']);
        exit;
    }
    $res_no = mysqli_fetch_assoc($q_res)['reservation_no'];
    
    $con->begin_transaction();
    try {
        // Update status
        $con->query("UPDATE lab_reservations SET status='Approved' WHERE id=$res_id");
        
        $admin_id = $_SESSION['adminId'] ?? 1;
        
        foreach($approve_qty as $ri_id => $qty) {
            $qty = (int)$qty;
            if($qty < 0) continue;
            
            // Get original item info
            $q1 = $con->query("SELECT item_id, requested_quantity FROM lab_reservation_items WHERE id=$ri_id");
            if($q1->num_rows > 0) {
                $row = $q1->fetch_assoc();
                $item_id = $row['item_id'];
                
                // Update requested qty line
                $con->query("UPDATE lab_reservation_items SET approved_quantity=$qty WHERE id=$ri_id");
                
                if($qty > 0) {
                    // Deduct inventory
                    $con->query("UPDATE lab_items SET available_quantity = available_quantity - $qty WHERE id=$item_id");
                    
                    // Add log
                    $remarks = "Reservation $res_no approved (-$qty)";
                    $stmt = $con->prepare("INSERT INTO lab_item_logs (item_id, change_type, quantity, remarks, performed_by) VALUES (?, '-', ?, ?, ?)");
                    $stmt->bind_param("iisi", $item_id, $qty, $remarks, $admin_id);
                    $stmt->execute();
                }
            }
        }
        $con->commit();
        echo json_encode(['status'=>'success', 'message'=>"Reservation $res_no successfully approved."]);
    } catch(Exception $e) {
        $con->rollback();
        echo json_encode(['status'=>'error', 'message'=>$e->getMessage()]);
    }
}
?>
