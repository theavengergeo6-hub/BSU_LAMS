<?php
require('../config.php');

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $contact = mysqli_real_escape_string($con, $_POST['contact']);
    $subject = mysqli_real_escape_string($con, $_POST['subject']);
    $course = mysqli_real_escape_string($con, $_POST['course']);
    $station = mysqli_real_escape_string($con, $_POST['station']);
    $batch = mysqli_real_escape_string($con, $_POST['batch']);
    $date = mysqli_real_escape_string($con, $_POST['date']);
    $time = mysqli_real_escape_string($con, $_POST['time']);
    
    $cart = json_decode($_POST['cart'], true);
    
    if(!$name || !$email || !$date || empty($cart)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        exit;
    }
    
    // Generate Reservation No.
    $date_prefix = date('Ymd');
    // Get highest today
    $query = "SELECT reservation_no FROM lab_reservations WHERE reservation_no LIKE 'LAB-{$date_prefix}-%' ORDER BY id DESC LIMIT 1";
    $q_res = mysqli_query($con, $query);
    
    $seq = 1;
    if(mysqli_num_rows($q_res) > 0) {
        $row = mysqli_fetch_assoc($q_res);
        $parts = explode('-', $row['reservation_no']);
        $seq = (int)$parts[2] + 1;
    }
    
    $res_no = 'LAB-' . $date_prefix . '-' . str_pad($seq, 3, '0', STR_PAD_LEFT);
    
    // Insert reservation
    $stmt = $con->prepare("INSERT INTO lab_reservations (reservation_no, student_name, student_email, contact_number, subject, course_section, station, batch, reservation_date, reservation_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $res_no, $name, $email, $contact, $subject, $course, $station, $batch, $date, $time);
    
    if($stmt->execute()) {
        $res_id = $stmt->insert_id;
        
        // Insert items
        $item_stmt = $con->prepare("INSERT INTO lab_reservation_items (reservation_id, item_id, requested_quantity) VALUES (?, ?, ?)");
        
        foreach($cart as $item_id => $details) {
            $qty = (int)$details['quantity'];
            $item_stmt->bind_param("iii", $res_id, $item_id, $qty);
            $item_stmt->execute();
        }
        
        // Notification for admin
        $notif_title = "New Reservation: " . $res_no;
        $notif_msg = "Student {$name} requested a reservation for {$date} at {$time}.";
        $con->query("INSERT INTO lab_admin_notifications (title, message) VALUES ('$notif_title', '$notif_msg')");
        
        echo json_encode(['status' => 'success', 'res_no' => $res_no]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }
}
?>
