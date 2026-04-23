<?php
/**
 * cron_cooldown.php
 * Restores available_quantity for items after the 3-hour cooldown periods.
 */
function runCooldownCron($con) {
    // Find Approved or Completed reservations that have passed their cooldown
    // and haven't had their stock restored yet.
    // Window: reservation_end_time + 3 hours < NOW
    
    $now = date('Y-m-d H:i:s');
    
    $query = "
        SELECT id, reservation_no, user_name
        FROM lab_reservations
        WHERE (status IN ('Approved', 'Ongoing', 'Completed'))
          AND (stock_restored = 0 OR stock_restored IS NULL)
          AND DATE_ADD(STR_TO_DATE(CONCAT(reservation_date, ' ', reservation_end_time), '%Y-%m-%d %H:%i'), INTERVAL 3 HOUR) <= NOW()
    ";
    
    $res = mysqli_query($con, $query);
    if(!$res) return;

    while($row = mysqli_fetch_assoc($res)) {
        $res_id = $row['id'];
        $res_no = $row['reservation_no'];
        
        mysqli_begin_transaction($con);
        try {
            // Restore items
            $items_q = mysqli_query($con, "SELECT item_id, approved_quantity FROM lab_reservation_items WHERE reservation_id = $res_id AND approved_quantity > 0");
            while($itm = mysqli_fetch_assoc($items_q)) {
                $item_id = $itm['item_id'];
                $qty = $itm['approved_quantity'];
                
                mysqli_query($con, "UPDATE lab_items SET available_quantity = available_quantity + $qty WHERE id = $item_id");
                
                // Log the return
                $remarks = "Cooldown expired for $res_no (auto-returned +$qty)";
                $perf_by = 1; // System/Admin 1
                $log_stmt = mysqli_prepare($con, "INSERT INTO lab_item_logs (item_id, change_type, quantity_change, remarks, performed_by) VALUES (?, '+', ?, ?, ?)");
                mysqli_stmt_bind_param($log_stmt, "iisi", $item_id, $qty, $remarks, $perf_by);
                mysqli_stmt_execute($log_stmt);
            }
            
            // Mark as restored
            mysqli_query($con, "UPDATE lab_reservations SET stock_restored = 1 WHERE id = $res_id");
            
            mysqli_commit($con);
        } catch(Exception $e) {
            mysqli_rollback($con);
        }
    }
}
?>
