<?php
include 'config.php';
mysqli_query($con, 'DELETE FROM lab_items WHERE id = 1');
mysqli_query($con, 'DELETE FROM lab_items WHERE item_name = "Ice Cream Scooper"');
echo "DELETED ALL STANDALONE ICE CREAM SCOOPERS";
