<?php
require('../config.php');
require('../inc/auth.php');
adminLogin();
require('upload_image.php');

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['item_name'])) {
    $name = mysqli_real_escape_string($con, $_POST['item_name']);
    $cat_id = (int)$_POST['category_id'];
    $unit = mysqli_real_escape_string($con, $_POST['unit']);
    $total_qty = (int)$_POST['total_quantity'];
    $avail_qty = (int)$_POST['available_quantity'];
    $acq_date = mysqli_real_escape_string($con, $_POST['acquisition_date']);
    
    // Feature 3.1: Validation
    if($total_qty < 0 || $total_qty > 9999) {
        echo json_encode(['status' => 'error', 'message' => 'Total quantity must be between 0 and 9999']);
        exit;
    }
    if($avail_qty < 0) {
        echo json_encode(['status' => 'error', 'message' => 'Available quantity cannot be negative']);
        exit;
    }
    if($avail_qty > $total_qty) {
        echo json_encode(['status' => 'error', 'message' => 'Available quantity cannot be greater than total quantity']);
        exit;
    }

    $img_path = null;
    if(isset($_FILES['item_photo']) && $_FILES['item_photo']['size'] > 0) {
        $upload_res = uploadImage($_FILES['item_photo']);
        if($upload_res['status'] == 'error') {
            echo json_encode($upload_res);
            exit;
        } elseif($upload_res['status'] == 'success') {
            $img_path = $upload_res['image_path'];
        }
    }
    
    $con->begin_transaction();
    try {
        $stmt = $con->prepare("INSERT INTO lab_items (category_id, item_name, unit, total_quantity, available_quantity, acquisition_date, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issii ss", $cat_id, $name, $unit, $total_qty, $avail_qty, $acq_date, $img_path);
        
        if($stmt->execute()) {
            $item_id = $stmt->insert_id;
            
            // log item creation
            $admin_id = $_SESSION['adminId'] ?? 1;
            $remarks = "Initial stock creation";
            $log = $con->prepare("INSERT INTO lab_item_logs (item_id, change_type, quantity_change, remarks, performed_by) VALUES (?, '+', ?, ?, ?)");
            $log->bind_param("iisi", $item_id, $total_qty, $remarks, $admin_id);
            $log->execute();
            
            $con->commit();
            echo json_encode(['status' => 'success', 'message' => 'Item saved']);
        } else {
            $con->rollback();
            echo json_encode(['status' => 'error', 'message' => 'DB error: ' . $stmt->error]);
        }
    } catch (Exception $e) {
        $con->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>
