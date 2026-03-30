<?php
require('../config.php');
require('../inc/auth.php');
adminLogin();
header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    $q = "SELECT * FROM lab_admin_notifications WHERE is_read = 0 ORDER BY created_at DESC LIMIT 10";
    $res = mysqli_query($con, $q);
    
    $notifs = [];
    while($row = mysqli_fetch_assoc($res)) {
        $notifs[] = $row;
    }
    
    // Also return count
    $c_q = mysqli_query($con, "SELECT COUNT(*) as unread_count FROM lab_admin_notifications WHERE is_read=0");
    $count = mysqli_fetch_assoc($c_q)['unread_count'];
    
    echo json_encode(['status' => 'success', 'count' => $count, 'notifications' => $notifs]);
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mark_read'])) {
    $id = (int)$_POST['id'];
    if($id > 0) {
        $con->query("UPDATE lab_admin_notifications SET is_read = 1 WHERE id = $id");
    } else {
        $con->query("UPDATE lab_admin_notifications SET is_read = 1 WHERE is_read = 0");
    }
    echo json_encode(['status' => 'success']);
}
?>
