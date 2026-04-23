<?php
require('../config.php');
require('../inc/auth.php');
adminLogin();

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    
    $q = mysqli_query($con, "SELECT status, reservation_no, stock_restored FROM lab_reservations WHERE id=$id");
    if(mysqli_num_rows($q) == 0) {
        echo json_encode(['status'=>'error', 'message'=>'Requisition not found']);
        exit;
    }
    $row = mysqli_fetch_assoc($q);
    
    if($row['stock_restored']) {
        echo json_encode(['status'=>'error', 'message'=>'Stock already restored for this requisition']);
        exit;
    }

    $con->begin_transaction();
    try {
        // Return stock
        $items_q = $con->query("SELECT item_id, approved_quantity FROM lab_reservation_items WHERE reservation_id=$id AND approved_quantity > 0");
        while($item = $items_q->fetch_assoc()) {
            $item_id = $item['item_id'];
            $qty = $item['approved_quantity'];
            $con->query("UPDATE lab_items SET available_quantity = available_quantity + $qty WHERE id=$item_id");
            
            // Log
            $admin_id = $_SESSION['adminId'] ?? 1;
            $res_no = $row['reservation_no'];
            $remarks = "Early Return for $res_no (+ $qty)";
            $stmt = $con->prepare("INSERT INTO lab_item_logs (item_id, change_type, quantity_change, remarks, performed_by) VALUES (?, '+', ?, ?, ?)");
            $stmt->bind_param("iisi", $item_id, $qty, $remarks, $admin_id);
            $stmt->execute();
        }

        // Set status to Completed if it wasn't already, and mark stock as restored
        $con->query("UPDATE lab_reservations SET status='Completed', stock_restored=1 WHERE id=$id");
        
        $con->commit();
        echo json_encode(['status'=>'success']);
    } catch(Exception $e) {
        $con->rollback();
        echo json_encode(['status'=>'error', 'message'=>$e->getMessage()]);
    }
}
?>
