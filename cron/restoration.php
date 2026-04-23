<?php
/**
 * cron/restoration.php
 * Automatically restores inventory for Ongoing or Completed requisitions 
 * that have passed their 3-hour window.
 */

// If including from elsewhere, $con might already exist
if (!isset($con)) {
    require_once __DIR__ . '/../config.php';
}

$now = date('Y-m-d H:i:s');

// Find all requisitions that should have their stock restored
// 1. Not already restored
// 2. Status is Ongoing or Completed
// 3. Cooldown has passed (cooldown_until <= NOW)
$q = mysqli_query($con, "
    SELECT id, reservation_no 
    FROM lab_reservations 
    WHERE stock_restored = 0 
      AND status IN ('Ongoing', 'Completed') 
      AND cooldown_until <= '$now'
      AND cooldown_until IS NOT NULL
");

if ($q && mysqli_num_rows($q) > 0) {
    while ($res = mysqli_fetch_assoc($q)) {
        $res_id = $res['id'];
        $res_no = $res['reservation_no'];
        
        // Start transaction for each restoration
        mysqli_begin_transaction($con);
        try {
            // Get items to restore
            $items_q = mysqli_query($con, "SELECT item_id, approved_quantity FROM lab_reservation_items WHERE reservation_id = $res_id AND approved_quantity > 0");
            
            while ($item = mysqli_fetch_assoc($items_q)) {
                $item_id = $item['item_id'];
                $qty = $item['approved_quantity'];
                
                // Restore stock
                mysqli_query($con, "UPDATE lab_items SET available_quantity = available_quantity + $qty WHERE id = $item_id");
                
                // Log the restoration
                $remarks = "Auto-restored items from $res_no (3h limit reached)";
                $stmt = $con->prepare("INSERT INTO lab_item_logs (item_id, change_type, quantity, remarks, performed_by) VALUES (?, '+', ?, ?, 0)");
                $stmt->bind_param("iis", $item_id, $qty, $remarks);
                $stmt->execute();
            }
            
            // Mark as restored
            mysqli_query($con, "UPDATE lab_reservations SET stock_restored = 1 WHERE id = $res_id");
            
            mysqli_commit($con);
        } catch (Exception $e) {
            mysqli_rollback($con);
            // Log error if needed: error_log("Failed to auto-restore $res_no: " . $e->getMessage());
        }
    }
}
?>
