<?php
require('../config.php');
$where = "l.item_id = 6";
$q = "SELECT l.*, i.item_name, a.username
      FROM lab_item_logs l
      JOIN lab_items i ON l.item_id = i.id
      LEFT JOIN lab_admin_users a ON l.performed_by = a.id
      WHERE $where
      ORDER BY l.created_at DESC LIMIT 5";
$res = mysqli_query($con, $q);
while($row = mysqli_fetch_assoc($res)) {
    $is_add = $row['change_type'] === '+';
    echo $row['id'] . " | Type: " . $row['change_type'] . " | is_add: " . ($is_add ? 'Added' : 'Removed') . " | Qty: " . $row['quantity_change'] . "\n";
}
?>
