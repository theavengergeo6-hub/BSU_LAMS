<?php
require 'c:\xampp\htdocs\BSU_Kitchen\config.php';
$item_res = mysqli_query($con, "SELECT id FROM lab_items LIMIT 1");
$row = mysqli_fetch_assoc($item_res);
$valid_id = $row['id'];
$rand = "LAB-Q-" . uniqid();
$sql = "INSERT INTO lab_reservations (reservation_no, user_name, user_email, user_contact, subject, course_section, station, batch, reservation_date, reservation_time) VALUES ('$rand', 'Test', 'test@test', '123', 'subj', 'cs', '1', '1', '2026-10-10', '07:00 AM')";

try {
    mysqli_query($con, $sql);
    $res_id = mysqli_insert_id($con);
    echo "Res ID inserted: $res_id<br>";
    
    $item_sql = "INSERT INTO lab_reservation_items (reservation_id, item_id, requested_quantity) VALUES ($res_id, $valid_id, 1)";
    mysqli_query($con, $item_sql);
    echo "Item inserted! valid_id: $valid_id<br>";
} catch (Exception $e) {
    echo "Error inserting: " . $e->getMessage() . "<br>";
}
?>
