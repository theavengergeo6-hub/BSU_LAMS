<?php
require('../config.php');

header('Content-Type: application/json');

// Capture all errors into a buffer
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

function send_response($status, $message, $extra = []) {
    ob_end_clean(); // Discard any errors captured in the buffer
    echo json_encode(array_merge(['status' => $status, 'message' => $message], $extra));
    exit;
}

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_response('error', 'Invalid request method');
}

// Collect inputs
$name = isset($_POST['name']) ? mysqli_real_escape_string($con, $_POST['name']) : '';
$email = isset($_POST['email']) ? mysqli_real_escape_string($con, $_POST['email']) : '';
$contact = isset($_POST['contact']) ? mysqli_real_escape_string($con, $_POST['contact']) : '';
$subject = isset($_POST['subject']) ? mysqli_real_escape_string($con, $_POST['subject']) : '';
$course = isset($_POST['course']) ? mysqli_real_escape_string($con, $_POST['course']) : '';
$station = isset($_POST['station']) ? mysqli_real_escape_string($con, $_POST['station']) : '';
$batch = isset($_POST['batch']) ? mysqli_real_escape_string($con, $_POST['batch']) : '';
$date = isset($_POST['date']) ? mysqli_real_escape_string($con, $_POST['date']) : '';
$time = isset($_POST['time']) ? mysqli_real_escape_string($con, $_POST['time']) : '';

$raw_cart = isset($_POST['cart']) ? $_POST['cart'] : '';
$cart = json_decode($raw_cart, true);

if(empty($name) || empty($email) || empty($date) || empty($cart)) {
    send_response('error', 'Please fill in all required fields and add items to your cart.');
}

// Generate Reservation No.
$date_prefix = date('Ymd');
$query = "SELECT reservation_no FROM lab_reservations WHERE reservation_no LIKE 'LAB-{$date_prefix}-%' ORDER BY id DESC LIMIT 1";
$q_res = mysqli_query($con, $query);

$seq = 1;
if($q_res && mysqli_num_rows($q_res) > 0) {
    $row = mysqli_fetch_assoc($q_res);
    $parts = explode('-', $row['reservation_no']);
    if(isset($parts[2])) {
        $seq = (int)$parts[2] + 1;
    }
}
$res_no = 'LAB-' . $date_prefix . '-' . str_pad($seq, 3, '0', STR_PAD_LEFT);

try {
    // Insert reservation
    $stmt = $con->prepare("INSERT INTO lab_reservations (reservation_no, user_name, user_email, user_contact, subject, course_section, station, batch, reservation_date, reservation_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if(!$stmt) {
        throw new Exception("Reservation table error: " . $con->error);
    }
    $stmt->bind_param("ssssssssss", $res_no, $name, $email, $contact, $subject, $course, $station, $batch, $date, $time);
    
    if(!$stmt->execute()) {
        throw new Exception("Failed to save reservation: " . $stmt->error);
    }
    
    $res_id = $stmt->insert_id;
    
    // Insert items
    $item_stmt = $con->prepare("INSERT INTO lab_reservation_items (reservation_id, item_id, requested_quantity) VALUES (?, ?, ?)");
    if(!$item_stmt) {
        throw new Exception("Items table error: " . $con->error);
    }

    foreach($cart as $item_id => $details) {
        $qty = isset($details['quantity']) ? (int)$details['quantity'] : 0;
        if($qty > 0) {
            $item_id_numeric = (int)$item_id;
            $item_stmt->bind_param("iii", $res_id, $item_id_numeric, $qty);
            $item_stmt->execute();
        }
    }
    
    // Notification for admin
    $notif_msg = mysqli_real_escape_string($con, "New Requisition ($res_no): Student {$name} requested a requisition for {$date} at {$time}.");
    if(!$con->query("INSERT INTO lab_admin_notifications (reservation_id, message) VALUES ($res_id, '$notif_msg')")){
        // Non-fatal error, let it pass or log it
        error_log("Failed to insert notification: " . $con->error);
    }
    
    send_response('success', 'Requisition submitted', ['res_no' => $res_no]);

} catch (Exception $e) {
    send_response('error', $e->getMessage());
}
?>
