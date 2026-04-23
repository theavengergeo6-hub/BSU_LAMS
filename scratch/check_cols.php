<?php
require('config.php');
$tbls = ['lab_reservations', 'lab_items'];
foreach($tbls as $tbl) {
    echo "Columns for '$tbl':\n";
    $res = mysqli_query($con, "SHOW COLUMNS FROM $tbl");
    if ($res) {
        while($row = mysqli_fetch_assoc($res)) {
            echo "- " . $row['Field'] . "\n";
        }
    } else {
        echo "Error: " . mysqli_error($con) . "\n";
    }
    echo "\n";
}
?>
