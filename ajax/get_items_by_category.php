<?php
/**
 * ajax/get_items_by_category.php
 *
 * Returns items for a given category_id.
 * When `date` and `time` are supplied (from reserve.php), the available_quantity
 * returned is TIME-SLOT-AWARE: total_quantity minus what is already committed
 * (Approved or Ongoing) for that exact date+time combination.
 *
 * This means:
 *  - 8:30 AM batch using 5 Ice Cream Scoopers → 2:00 PM students still see FULL qty.
 *  - A 2nd group booking 8:30 AM will see REDUCED qty if items are already taken.
 */
require('../config.php');

if (isset($_GET['category_id'])) {
    $cat_id   = (int) $_GET['category_id'];
    $date     = isset($_GET['date']) ? mysqli_real_escape_string($con, $_GET['date']) : '';
    $start    = isset($_GET['time']) ? mysqli_real_escape_string($con, $_GET['time']) : '';
    $end      = isset($_GET['end_time']) ? mysqli_real_escape_string($con, $_GET['end_time']) : '';

    if ($date && $start) {
        // If end time is not yet selected, assume a 1-minute duration for initial availability check
        $calc_end = $end ? $end : date('H:i', strtotime($start) + 60);

        $query = "
            SELECT i.*,
                   GREATEST(0,
                       i.total_quantity - COALESCE((
                           SELECT SUM(ri.approved_quantity)
                           FROM lab_reservation_items ri
                           JOIN lab_reservations r ON ri.reservation_id = r.id
                           WHERE ri.item_id = i.id
                             AND r.reservation_date = '$date'
                             -- Item is occupied if not restored and status is valid
                             AND (r.stock_restored = 0 OR r.stock_restored IS NULL)
                             AND (LOWER(r.status) IN ('approved', 'ongoing', 'completed'))
                             AND (
                                 -- Overlap logic: (NewStart < ExistEnd+3h) AND (NewEnd > ExistStart)
                                 STR_TO_DATE('$start', '%H:%i') < DATE_ADD(STR_TO_DATE(r.reservation_end_time, '%H:%i'), INTERVAL 3 HOUR)
                                 AND 
                                 STR_TO_DATE('$calc_end', '%H:%i') > STR_TO_DATE(r.reservation_time, '%H:%i')
                             )
                       ), 0)
                   ) AS available_quantity
            FROM lab_items i
            WHERE i.category_id = $cat_id
            ORDER BY i.item_name
        ";
    } else {
        // When no specific timeslot is picked, show what's physically available in the warehouse NOW.
        // This means Total Quantity minus whatever is CURRENTLY marked as Ongoing/Approved (committed).
        // Actually, to avoid confusion, let's show Total Quantity and only deduct what is CURRENTLY Ongoing.
        $query = "
            SELECT i.*,
                   GREATEST(0,
                       i.total_quantity - COALESCE((
                           SELECT SUM(ri.approved_quantity)
                           FROM lab_reservation_items ri
                           JOIN lab_reservations r ON ri.reservation_id = r.id
                           WHERE ri.item_id = i.id
                             AND LOWER(r.status) = 'ongoing'
                             AND (r.stock_restored = 0 OR r.stock_restored IS NULL)
                       ), 0)
                   ) AS available_quantity
            FROM lab_items i
            WHERE i.category_id = $cat_id
            ORDER BY i.item_name
        ";
    }

    $res  = mysqli_query($con, $query);
    $data = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $row['available_quantity'] = (int) $row['available_quantity'];
        $data[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($data);
}
?>
