<?php
require('../config.php');
require('../inc/auth.php');
adminLogin();
header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['item_id'], $_POST['change_type'], $_POST['quantity_change'], $_POST['remarks'])) {
    
    $item_id = (int)$_POST['item_id'];
    $change = (int)$_POST['quantity_change'];
    $type = mysqli_real_escape_string($con, $_POST['change_type']);
    $remarks = mysqli_real_escape_string($con, $_POST['remarks']);
    
    if($change <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Quantity must be greater than 0']);
        exit;
    }
    
    // Validate Current
    $q = $con->query("SELECT total_quantity, available_quantity FROM lab_items WHERE id = $item_id");
    if($q->num_rows == 0) {
        echo json_encode(['status' => 'error', 'message' => 'Item not found']);
        exit;
    }
    $curr = $q->fetch_assoc();
    
    if($type == '-') {
        if($change > $curr['available_quantity']) {
            echo json_encode(['status'=>'error', 'message'=>"Cannot remove more than available qty ({$curr['available_quantity']})"]);
            exit;
        }
        $new_tot = $curr['total_quantity'] - $change;
        $new_avail = $curr['available_quantity'] - $change;
    } else if($type == '+') {
        $new_tot = $curr['total_quantity'] + $change;
        $new_avail = $curr['available_quantity'] + $change;
    } else {
        echo json_encode(['status'=>'error', 'message'=>"Invalid change type"]);
        exit;
    }
    
    $con->begin_transaction();
    try {
        $con->query("UPDATE lab_items SET total_quantity=$new_tot, available_quantity=$new_avail WHERE id=$item_id");
        
        $admin_id = $_SESSION['adminId'] ?? 1;
        $stmt = $con->prepare("INSERT INTO lab_item_logs (item_id, change_type, quantity, remarks, performed_by) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isisi", $item_id, $type, $change, $remarks, $admin_id);
        $stmt->execute();
        
        $con->commit();
        echo json_encode(['status'=>'success']);
    } catch (Exception $e) {
        $con->rollback();
        echo json_encode(['status'=>'error', 'message'=> $e->getMessage()]);
    }
}
?>
