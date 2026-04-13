<?php
require 'config.php';
$res = mysqli_query($con, "SHOW COLUMNS FROM lab_reservations");
echo "<h1>Columns in lab_reservations:</h1><ul>";
while($row = mysqli_fetch_assoc($res)) {
    echo "<li>" . $row['Field'] . "</li>";
}
echo "</ul>";
?>
