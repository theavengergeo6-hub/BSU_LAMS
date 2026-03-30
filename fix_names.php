<?php
include 'config.php';
mysqli_query($con, "UPDATE lab_items SET item_name = 'Ice Cream Scooper (2\')' WHERE item_name = '2\' Casserole'");
mysqli_query($con, "UPDATE lab_items SET item_name = 'Ice Cream Scooper (6\')' WHERE item_name = '6\' Casserole'");
mysqli_query($con, "UPDATE lab_items SET item_name = 'Ice Cream Scooper (8\')' WHERE item_name = '8\' Casserole'");

$res = mysqli_query($con, 'SELECT id, item_name, category_id FROM lab_items WHERE item_name LIKE \'%Scoop%\'');
while ($row = mysqli_fetch_assoc($res)) {
    echo $row['id'] . ' | ' . $row['item_name'] . ' | Cat: ' . $row['category_id'] . "\n";
}
echo "\nDONE";
