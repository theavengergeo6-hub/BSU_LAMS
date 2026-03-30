<?php
include 'config.php';
$res = mysqli_query($con, 'SELECT id, item_name, category_id FROM lab_items');
while ($row = mysqli_fetch_assoc($res)) {
    if (strpos(strtolower($row['item_name']), 'casserole') !== false ||
        strpos(strtolower($row['item_name']), 'scoop') !== false ||
        strpos($row['item_name'], "2'") !== false ||
        strpos($row['item_name'], "6'") !== false ||
        strpos($row['item_name'], "8'") !== false) {
        
        echo $row['id'] . ' | ' . $row['item_name'] . ' | Cat: ' . $row['category_id'] . "\n";
    }
}
