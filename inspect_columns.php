<?php
require('config.php');
$tbl = isset($_GET['table']) ? $_GET['table'] : 'lab_reservations';
echo "Checking columns for '$tbl' table:<br>";
$res = mysqli_query($con, "SHOW COLUMNS FROM $tbl");
if ($res) {
    echo "<table>";
    while($row = mysqli_fetch_assoc($res)) {
        echo "<tr><td>".$row['Field']."</td></tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . mysqli_error($con);
}
?>
