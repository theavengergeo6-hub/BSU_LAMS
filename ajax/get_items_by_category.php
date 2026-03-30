<?php
require('../config.php');

if(isset($_GET['category_id'])) {
    $cat_id = (int)$_GET['category_id'];
    
    $query = "SELECT * FROM lab_items WHERE category_id = $cat_id AND (available_quantity > 0 OR available_quantity = 0)"; // Get all
    $res = mysqli_query($con, $query);
    
    $data = [];
    while($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }
    
    header('Content-Type: application/json');
    echo json_encode($data);
}
?>
