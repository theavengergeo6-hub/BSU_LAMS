<?php
require 'c:\xampp\htdocs\BSU_Kitchen\config.php';
$item_res = mysqli_query($con, "SELECT id FROM lab_items LIMIT 1");
$row = mysqli_fetch_assoc($item_res);
$valid_id = $row['id'];
$rand = "LAB-Q-" . time();
$sql = "INSERT INTO lab_reservations (reservation_no, user_name, user_email, user_contact, subject, course_section, station, batch, reservation_date, reservation_time) VALUES ('$rand', 'Test', 'test@test', '123', 'subj', 'cs', '1', '1', '2026-10-10', '07:00 AM')";
if (mysqli_query($con, $sql)) {
    echo "Inserted Q\n";
} else {
    echo "Error Q: " . mysqli_error($con) . "\n";
}
?>
