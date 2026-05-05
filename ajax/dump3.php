<?php
require('../config.php');
$q = mysqli_query($con, "SELECT id, change_type FROM lab_item_logs WHERE item_id = 6 ORDER BY created_at DESC LIMIT 5");
while($row = mysqli_fetch_assoc($q)) {
    $ct = $row['change_type'];
    $is_add = $ct === '+';
    $is_add2 = $ct == '+';
    $is_add3 = trim($ct) === '+';
    echo "ID " . $row['id'] . " | raw: '" . $ct . "' | ===: " . ($is_add ? 'yes' : 'no') . " | ==: " . ($is_add2 ? 'yes' : 'no') . " | trim: " . ($is_add3 ? 'yes' : 'no') . " | length: " . strlen($ct) . "\n";
}
?>
