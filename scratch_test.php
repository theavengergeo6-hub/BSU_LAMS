<?php
require_once('config.php');

// Create test reservation
$res_no = 'TEST-' . time();
$name = "LONG NAME TEST STUDENT REPRESENTATIVE";
$email = "test@example.com";
$contact = "09123456789";
$subject = "ADVANCED KITCHEN OPERATIONS AND MANAGEMENT 101";
$course = "BSHM - MULTI-LINE LONG SECTION NAME TEST 4A";
$station = "STATION 99";
$batch = "BATCH BLUE VELVET 2026";
$date = date('Y-m-d');
$time = "08:30 AM";

$sql = "INSERT INTO lab_reservations (reservation_no, user_name, user_email, user_contact, subject, course_section, station, batch, reservation_date, reservation_time, status) 
        VALUES ('$res_no', '$name', '$email', '$contact', '$subject', '$course', '$station', '$batch', '$date', '$time', 'Approved')";

if (mysqli_query($con, $sql)) {
    $res_id = mysqli_insert_id($con);
    echo "Created reservation #$res_id <br>";

    // Add 24 items (some with very long names)
    $items_res = mysqli_query($con, "SELECT id, item_name FROM lab_items LIMIT 24");
    $i = 1;
    while ($item = mysqli_fetch_assoc($items_res)) {
        $item_id = $item['id'];
        $long_name_extra = ($i % 3 == 0) ? " - EXTRA LONG DESCRIPTION FOR TESTING FONT RESIZING" : "";
        
        // Update item name temporarily for this test if we want to see long names
        // But better to just find items or use the ones we have and append in the print script logic for testing if needed
        // For now let's just insert them.
        
        $req_qty = rand(1, 10);
        $app_qty = $req_qty;
        
        mysqli_query($con, "INSERT INTO lab_reservation_items (reservation_id, item_id, requested_quantity, approved_quantity) 
                            VALUES ($res_id, $item_id, $req_qty, $app_qty)");
        $i++;
    }
    echo "Added items. <br>";
    echo "Check reservation ID: $res_id in admin panel.<br>";
} else {
    echo "Error: " . mysqli_error($con);
}
?>
