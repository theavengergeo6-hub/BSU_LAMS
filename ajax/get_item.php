<?php
require('../config.php');
if(isset($_GET['id'])){
    $id = (int)$_GET['id'];
    $res = mysqli_query($con, "SELECT * FROM lab_items WHERE id=$id");
    echo json_encode(mysqli_fetch_assoc($res));
}
?>
