<?php
require('../config.php');
if(isset($_POST['item_id'])){
    $item_id = (int)$_POST['item_id'];
    $item_name = mysqli_real_escape_string($con, $_POST['item_name']);
    $category_id = (int)$_POST['category_id'];
    $unit = mysqli_real_escape_string($con, $_POST['unit']);
    $acq_date = mysqli_real_escape_string($con, $_POST['acquisition_date']);
    
    // Add quantity validation
    $total_qty = isset($_POST['total_quantity']) ? (int)$_POST['total_quantity'] : -1;
    $avail_qty = isset($_POST['available_quantity']) ? (int)$_POST['available_quantity'] : -1;

    // Optional: if these are POSTed (they should be if we want full validation)
    if($total_qty != -1 && ($total_qty < 0 || $total_qty > 9999)) {
        echo json_encode(['status'=>'error', 'message'=>'Total quantity must be 0-9999']);
        exit;
    }
    if($avail_qty != -1 && $avail_qty > $total_qty && $total_qty != -1) {
        echo json_encode(['status'=>'error', 'message'=>'Available quantity cannot exceed total quantity']);
        exit;
    }

    // check if renaming or removing photo
    $remove_photo = isset($_POST['remove_photo']) && $_POST['remove_photo'] == '1';
    
    $q = "UPDATE lab_items SET item_name='$item_name', category_id=$category_id, unit='$unit', acquisition_date='$acq_date'";
    
    if($total_qty != -1) $q .= ", total_quantity=$total_qty";
    if($avail_qty != -1) $q .= ", available_quantity=$avail_qty";
    
    if($remove_photo) {
        $q .= ", image_path=NULL";
    } else if(isset($_FILES['item_photo']) && $_FILES['item_photo']['error'] == 0){
        $ext = pathinfo($_FILES['item_photo']['name'], PATHINFO_EXTENSION);
        $filename = "item_" . time() . "_".rand(100,999).".".$ext;
        if(move_uploaded_file($_FILES['item_photo']['tmp_name'], "../uploads/lab_items/$filename")) {
            $q .= ", image_path='$filename'";
        }
    }
    
    $q .= " WHERE id=$item_id";
    
    if(mysqli_query($con, $q)){
        echo json_encode(['status'=>'success']);
    } else {
        echo json_encode(['status'=>'error', 'message'=>mysqli_error($con)]);
    }
}
?>
