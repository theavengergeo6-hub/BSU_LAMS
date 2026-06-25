<?php
/**
 * BSU Kitchen Laboratory Requisition System - AJAX Submission Handler
 * 
 * This script processes incoming POST requests from the client requisition booking page.
 * Key Actions:
 * 1. Sanitizes text inputs using mysqli_real_escape_string to mitigate SQL injection.
 * 2. Decodes cart JSON array sent by client.
 * 3. Generates a unique sequential reservation number matching the format 'LAB-YYYYMMDD-XXX'.
 * 4. Inserts the requisition record into `lab_reservations` using prepared statements.
 * 5. Iterates through the cart items and links requested quantities in `lab_reservation_items`.
 * 6. Dispatches an admin notification to alert lab custodians of a new pending request.
 */

require('../config.php');

// Define response headers to output correct content type for AJAX handlers
header('Content-Type: application/json');

// Capture all errors and runtime warnings into a buffer to prevent PHP warnings
// from polluting the JSON response structure.
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Discards error buffers, formats the payload, prints JSON, and terminates script execution.
 * 
 * @param string $status The outcome status: 'success' or 'error'.
 * @param string $message User-facing message detailing outcome.
 * @param array $extra Optional key-value parameters to merge into the response.
 */
function send_response($status, $message, $extra = []) {
    ob_end_clean(); // Clear error buffer and discard outputs
    echo json_encode(array_merge(['status' => $status, 'message' => $message], $extra));
    exit;
}

// Only permit POST requests
if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_response('error', 'Invalid request method');
}

// Collect and sanitize user inputs to prevent SQL Injection on parameters
$name = isset($_POST['name']) ? mysqli_real_escape_string($con, $_POST['name']) : '';
$email = isset($_POST['email']) ? mysqli_real_escape_string($con, $_POST['email']) : '';
$contact = isset($_POST['contact']) ? mysqli_real_escape_string($con, $_POST['contact']) : '';
$subject = isset($_POST['subject']) ? mysqli_real_escape_string($con, $_POST['subject']) : '';
$course = isset($_POST['course']) ? mysqli_real_escape_string($con, $_POST['course']) : '';
$station = isset($_POST['station']) ? mysqli_real_escape_string($con, $_POST['station']) : '';
$batch = isset($_POST['batch']) ? mysqli_real_escape_string($con, $_POST['batch']) : '';
$date = isset($_POST['date']) ? mysqli_real_escape_string($con, $_POST['date']) : '';
$start_time = isset($_POST['time']) ? mysqli_real_escape_string($con, $_POST['time']) : '';
$end_time = isset($_POST['end_time']) ? mysqli_real_escape_string($con, $_POST['end_time']) : '';

// Decode the serialized cart string into an associative array
$raw_cart = isset($_POST['cart']) ? $_POST['cart'] : '';
$cart = json_decode($raw_cart, true);

// Enforce presence of mandatory booking details
if(empty($name) || empty($date) || empty($start_time) || empty($end_time) || empty($cart)) {
    send_response('error', 'Please fill in all required fields (Name, Date, Time) and add items to your cart.');
}

// ── Generate Unique Reservation Code ─────────────────────────────────────────
// Codes match 'LAB-YYYYMMDD-XXX' where XXX is a daily incrementing sequence ID.
$date_prefix = date('Ymd');
$query = "SELECT reservation_no FROM lab_reservations WHERE reservation_no LIKE 'LAB-{$date_prefix}-%' ORDER BY id DESC LIMIT 1";
$q_res = mysqli_query($con, $query);

$seq = 1;
if($q_res && mysqli_num_rows($q_res) > 0) {
    $row = mysqli_fetch_assoc($q_res);
    $parts = explode('-', $row['reservation_no']);
    if(isset($parts[2])) {
        // Increment the sequence ID from the latest record of the day
        $seq = (int)$parts[2] + 1;
    }
}
// Build the final sequence code with leading zeros
$res_no = 'LAB-' . $date_prefix . '-' . str_pad($seq, 3, '0', STR_PAD_LEFT);

try {
    // ── Insert Requisition Master Record ──────────────────────────────────────
    // Use MySQL prepared statements to prevent injection issues.
    $stmt = $con->prepare("INSERT INTO lab_reservations (reservation_no, user_name, user_email, user_contact, subject, course_section, station, batch, reservation_date, reservation_time, reservation_end_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if(!$stmt) {
        throw new Exception("Reservation table error: " . $con->error);
    }
    $stmt->bind_param("sssssssssss", $res_no, $name, $email, $contact, $subject, $course, $station, $batch, $date, $start_time, $end_time);
    
    if(!$stmt->execute()) {
        throw new Exception("Failed to save reservation: " . $stmt->error);
    }
    
    $res_id = $stmt->insert_id; // Capture auto-incremented primary key for item association
    
    // ── Insert Requisition Items Details ──────────────────────────────────────
    $item_stmt = $con->prepare("INSERT INTO lab_reservation_items (reservation_id, item_id, requested_quantity) VALUES (?, ?, ?)");
    if(!$item_stmt) {
        throw new Exception("Items table error: " . $con->error);
    }

    // Populate each valid item added to the student's reservation cart
    foreach($cart as $item_id => $details) {
        $qty = isset($details['quantity']) ? (int)$details['quantity'] : 0;
        if($qty > 0) {
            $item_id_numeric = (int)$item_id;
            $item_stmt->bind_param("iii", $res_id, $item_id_numeric, $qty);
            $item_stmt->execute();
        }
    }
    
    // ── Dispatch Dashboard Notification for Lab Custodians ────────────────────
    $notif_msg = mysqli_real_escape_string($con, "New Requisition ($res_no): Student {$name} requested a requisition for {$date} at {$start_time}.");
    if(!$con->query("INSERT INTO lab_admin_notifications (reservation_id, message) VALUES ($res_id, '$notif_msg')")){
        // Non-fatal error: log failed notification insertion to database logs, do not crash requisition
        error_log("Failed to insert notification: " . $con->error);
    }
    
    // Respond back to client with success indicator and the generated booking code
    send_response('success', 'Requisition submitted', ['res_no' => $res_no]);

} catch (Exception $e) {
    // Return structured exception messages to UI
    send_response('error', $e->getMessage());
}
?>
