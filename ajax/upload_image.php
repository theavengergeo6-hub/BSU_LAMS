<?php
function uploadImage($file_array) {
    if(isset($file_array['name']) && $file_array['name'] != '') {
        $file_name = $file_array['name'];
        $file_size = $file_array['size'];
        $file_tmp = $file_array['tmp_name'];
        $file_type = $file_array['type'];
        
        $tmp = explode('.', $file_name);
        $file_ext = strtolower(end($tmp));
        $extensions = array("jpeg", "jpg", "png", "gif");
        
        if(in_array($file_ext, $extensions) === false) {
            return ['status' => 'error', 'message' => 'Extension not allowed, please choose a JPEG, PNG or GIF file.'];
        }
        
        if($file_size > 2097152) {
            return ['status' => 'error', 'message' => 'File size must be exactly 2 MB or less.'];
        }
        
        $new_name = time() . '_' . rand(100, 999) . '.' . $file_ext;
        if(move_uploaded_file($file_tmp, "../uploads/lab_items/" . $new_name)) {
            return ['status' => 'success', 'image_path' => $new_name];
        } else {
            return ['status' => 'error', 'message' => 'Failed to upload image.'];
        }
    }
    return ['status' => 'empty'];
}
?>
