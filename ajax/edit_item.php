<?php
require('../config.php');
if(isset($_POST['item_id'])){
    $item_id = (int)$_POST['item_id'];
    $item_name = mysqli_real_escape_string($con, $_POST['item_name']);
    $category_id = (int)$_POST['category_id'];
    $unit = mysqli_real_escape_string($con, $_POST['unit']);
    
    // check if renaming or removing photo
    $remove_photo = isset($_POST['remove_photo']) && $_POST['remove_photo'] == '1';
    
    $q = "UPDATE lab_items SET item_name='$item_name', category_id=$category_id, unit='$unit'";
    
    if($remove_photo) {
        // optionally delete from server too, but usually fine to just un-link
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
