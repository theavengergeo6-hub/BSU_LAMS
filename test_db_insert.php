<?php
require 'c:\xampp\htdocs\BSU_Kitchen\config.php';
$item_res = mysqli_query($con, "SELECT id FROM lab_items LIMIT 1");
$row = mysqli_fetch_assoc($item_res);
$valid_id = $row['id'];
echo "Valid ID: $valid_id\n";
// Insert directly into reservation
$stmt = $con->prepare("INSERT INTO lab_reservations (reservation_no, user_name, user_email, user_contact, subject, course_section, station, batch, reservation_date, reservation_time) VALUES ('LAB-TEST-004', 'Test', 'test@test', '123', 'subj', 'cs', '1', '1', '2026-10-10', '07:00 AM')");
try {
    $stmt->execute();
    $res_id = $stmt->insert_id;
    echo "Res ID: $res_id\n";
    $item_stmt = $con->prepare("INSERT INTO lab_reservation_items (reservation_id, item_id, requested_quantity) VALUES (?, ?, ?)");
    $qty = 1;
    $item_stmt->bind_param("iii", $res_id, $valid_id, $qty);
    $item_stmt->execute();
    echo "Item inserted\n";
} catch(Exception $e) {
    echo "Caught Error: " . $e->getMessage() . "\n";
}
?>
