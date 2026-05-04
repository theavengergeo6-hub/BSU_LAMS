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
    $is_disposal = isset($_POST['is_disposal']) ? (int)$_POST['is_disposal'] : 0;
    $disposal_reason = isset($_POST['disposal_reason']) ? mysqli_real_escape_string($con, $_POST['disposal_reason']) : null;
    
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

        if($new_tot > 9999) {
            echo json_encode(['status'=>'error', 'message'=>"Total quantity cannot exceed 9999"]);
            exit;
        }
    } else {
        echo json_encode(['status'=>'error', 'message'=>"Invalid change type"]);
        exit;
    }
    
    $q_item = $con->query("SELECT item_name, unit FROM lab_items WHERE id = $item_id");
    $item_info = $q_item->fetch_assoc();

    $con->begin_transaction();
    try {
        $con->query("UPDATE lab_items SET total_quantity=$new_tot, available_quantity=$new_avail WHERE id=$item_id");
        
        $admin_id = $_SESSION['adminId'] ?? 1;
        $stmt = $con->prepare("INSERT INTO lab_item_logs (item_id, change_type, quantity_change, remarks, performed_by, is_disposal, disposal_reason) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isisiis", $item_id, $type, $change, $remarks, $admin_id, $is_disposal, $disposal_reason);
        $stmt->execute();
        
        $con->commit();

        if ($type == '-') {
            try {
                if (file_exists('../includes/breakage_logger.php')) {
                    require_once('../includes/breakage_logger.php');
                    $item_name = $item_info['item_name'] ?? 'Unknown Item';
                    $unit = $item_info['unit'] ?? '';
                    append_to_breakage_report($item_name, $unit, $change, $remarks);
                }
            } catch (Throwable $e) {
                // Log error locally but don't crash the AJAX response
                error_log("Breakage logging failed: " . $e->getMessage());
            }
        }

        echo json_encode(['status'=>'success']);
    } catch (Exception $e) {
        $con->rollback();
        echo json_encode(['status'=>'error', 'message'=> $e->getMessage()]);
    }
}
?>
