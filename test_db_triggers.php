<?php
require 'c:\xampp\htdocs\BSU_Kitchen\config.php';
$res = mysqli_query($con, "SHOW TRIGGERS LIKE 'lab_reservations'");
while($row = mysqli_fetch_assoc($res)) {
    echo "Trigger: " . $row['Trigger'] . "\nEvent: " . $row['Event'] . "\nStatement: " . $row['Statement'] . "\n";
}
echo "Done\n";
?>
