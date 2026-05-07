<?php
require('../../config.php');
require('../../inc/auth.php');
adminLogin();

$status_tab = isset($_GET['tab']) ? $_GET['tab'] : 'Pending';
$date_filter = isset($_GET['date']) ? $_GET['date'] : '';
$statuses   = ['Pending', 'Approved', 'Ongoing', 'Completed', 'Denied'];
if (!in_array($status_tab, $statuses)) $status_tab = 'Pending';

// --- Get Counts for Tabs ---
$counts = [];
foreach ($statuses as $st) {
    $cr = mysqli_query($con, "SELECT COUNT(*) as c FROM lab_reservations WHERE status='".mysqli_real_escape_string($con,$st)."'");
    $counts[$st] = mysqli_fetch_assoc($cr)['c'];
}
?>

<!-- Tabs -->
<div class="res-tabs">
    <?php foreach ($statuses as $st): ?>
    <a class="res-tab <?= $status_tab == $st ? 'active' : '' ?>" href="?tab=<?= $st ?>" onclick="return false;" data-tab="<?= $st ?>">
        <?= $st ?> <span class="tab-count"><?= $counts[$st] ?></span>
    </a>
    <?php endforeach; ?>
</div>

<!-- Main Container -->
<div class="res-table-container">
    <?php
    $q   = "SELECT * FROM lab_reservations WHERE status = '$status_tab'";
    if ($date_filter) {
        $q .= " AND reservation_date = '".mysqli_real_escape_string($con, $date_filter)."'";
    }
    $q .= " ORDER BY id DESC";
    $res = mysqli_query($con, $q);

    if (mysqli_num_rows($res) > 0): ?>
        
        <!-- Desktop Table View -->
        <table class="res-table">
            <thead>
                <tr>
                    <th>Req. No.</th>
                    <th>Student</th>
                    <th>Subject / Station</th>
                    <th>Schedule</th>
                    <th>Items</th>
                    <th>Status</th>
                    <th class="center act-col">Actions</th>
                </tr>
            </thead>
            <tbody id="resTableBody">
            <?php 
            while ($row = mysqli_fetch_assoc($res)): 
                $id = $row['id'];
                $items_q  = mysqli_query($con, "SELECT ri.requested_quantity, i.item_name FROM lab_reservation_items ri JOIN lab_items i ON ri.item_id = i.id WHERE ri.reservation_id = $id");
                $items_str = '';
                while ($itm = mysqli_fetch_assoc($items_q)) $items_str .= '<span style="display:block">· '.htmlspecialchars($itm['item_name']).' <strong>×'.$itm['requested_quantity'].'</strong></span>';
            ?>
            <tr>
                <td><span class="td-no">#<?= htmlspecialchars($row['reservation_no']) ?></span></td>
                <td>
                    <div class="td-name"><?= htmlspecialchars($row['user_name']) ?></div>
                    <div class="td-sub"><?= htmlspecialchars($row['user_email']) ?></div>
                    <span class="td-badge"><?= htmlspecialchars($row['course_section']) ?></span>
                </td>
                <td>
                    <div style="font-weight:600;font-size:.84rem;color:var(--text);"><?= htmlspecialchars($row['subject']) ?></div>
                    <div class="td-sub"><?= htmlspecialchars($row['station']) ?> · <?= htmlspecialchars($row['batch']) ?></div>
                </td>
                <td style="white-space:nowrap;">
                    <div style="font-weight:600;font-size:.84rem;color:var(--text);"><i class="bi bi-calendar3" style="color:var(--red);margin-right:4px;"></i> <?= date('M d, Y', strtotime($row['reservation_date'])) ?></div>
                    <div class="td-sub"><i class="bi bi-clock" style="margin-right:4px;"></i> <?= htmlspecialchars($row['reservation_time']) ?></div>
                </td>
                <td class="td-items"><?= $items_str ?: '<span style="color:var(--text-3)">—</span>' ?></td>
                <td><span class="status-badge status-<?= htmlspecialchars($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></span></td>
                <td style="text-align:center;">
                    <div style="display:flex;flex-direction:column;gap:5px;min-width:130px;">
                    <?php if ($status_tab == 'Pending'): ?>
                        <button class="act-btn act-btn-review" onclick="viewReservation(<?= $id ?>, '<?= $row['reservation_no'] ?>', true)">
                            <i class="bi bi-check-lg"></i> Review
                        </button>
                        <button class="act-btn act-btn-deny" onclick="updateStatus(<?= $id ?>, 'Denied')">
                            <i class="bi bi-x-lg"></i> Deny
                        </button>
                    <?php elseif ($status_tab == 'Approved'): ?>
                        <button class="act-btn act-btn-view" onclick="viewReservation(<?= $id ?>, '<?= $row['reservation_no'] ?>', false)">
                            <i class="bi bi-eye"></i> View
                        </button>
                        <button class="act-btn act-btn-ongoing" onclick="updateStatus(<?= $id ?>, 'Ongoing')">
                            <i class="bi bi-play-fill"></i> Set Ongoing
                        </button>
                        <a class="act-btn act-btn-print" href="print_requisition.php?id=<?= $id ?>" target="_blank">
                            <i class="bi bi-printer-fill"></i> Print Form
                        </a>
                        <button class="act-btn act-btn-deny" onclick="updateStatus(<?= $id ?>, 'Denied')">
                            <i class="bi bi-x-lg"></i> Deny
                        </button>
                    <?php elseif ($status_tab == 'Ongoing'): ?>
                        <button class="act-btn act-btn-view" onclick="viewReservation(<?= $id ?>, '<?= $row['reservation_no'] ?>', false)">
                            <i class="bi bi-eye"></i> View
                        </button>
                        <button class="act-btn act-btn-complete" onclick="updateStatus(<?= $id ?>, 'Completed')">
                            <i class="bi bi-check-circle-fill"></i> Complete
                        </button>
                        <a class="act-btn act-btn-print" href="print_requisition.php?id=<?= $id ?>" target="_blank">
                            <i class="bi bi-printer-fill"></i> Print Form
                        </a>
                    <?php else: ?>
                        <button class="act-btn act-btn-view" onclick="viewReservation(<?= $id ?>, '<?= $row['reservation_no'] ?>', false)">
                            <i class="bi bi-eye"></i> View
                        </button>
                        <a class="act-btn act-btn-print" href="print_requisition.php?id=<?= $id ?>" target="_blank">
                            <i class="bi bi-printer-fill"></i> Print Form
                        </a>
                        <button class="act-btn act-btn-closed" disabled>
                            <i class="bi bi-lock"></i> Closed
                        </button>
                    <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endwhile; mysqli_data_seek($res, 0); ?>
            <tr class="no-results-row" id="noResultsRow" style="display:none;"><td colspan="7"><i class="bi bi-search" style="font-size:1.5rem;display:block;margin-bottom:8px;opacity:.3;"></i>No results match your search.</td></tr>
            </tbody>
        </table>

        <!-- Mobile Card View -->
        <div class="card-view" id="cardView">
            <?php while ($row = mysqli_fetch_assoc($res)): 
                $id = $row['id'];
                $items_q  = mysqli_query($con, "SELECT ri.requested_quantity, i.item_name FROM lab_reservation_items ri JOIN lab_items i ON ri.item_id = i.id WHERE ri.reservation_id = $id");
                $items_str = '';
                while ($itm = mysqli_fetch_assoc($items_q)) $items_str .= '· '.htmlspecialchars($itm['item_name']).' (x'.$itm['requested_quantity'].') ';
            ?>
            <div class="res-card">
                <div class="card-header">
                    <span class="td-no">#<?= htmlspecialchars($row['reservation_no']) ?></span>
                    <span class="status-badge status-<?= htmlspecialchars($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></span>
                </div>
                <div class="card-body-row">
                    <div class="card-label">Student</div>
                    <div class="card-value"><?= htmlspecialchars($row['user_name']) ?> (<?= htmlspecialchars($row['course_section']) ?>)</div>
                </div>
                <div class="card-body-row">
                    <div class="card-label">Schedule</div>
                    <div class="card-value"><?= date('M d, Y', strtotime($row['reservation_date'])) ?> @ <?= htmlspecialchars($row['reservation_time']) ?></div>
                </div>
                <div class="card-body-row">
                    <div class="card-label">Items</div>
                    <div class="card-value" style="font-size:0.75rem; color:var(--text-2);"><?= $items_str ?: 'None' ?></div>
                </div>
                <div style="margin-top:10px;">
                    <?php if ($status_tab == 'Pending'): ?>
                        <button class="act-btn act-btn-review" onclick="viewReservation(<?= $id ?>, '<?= $row['reservation_no'] ?>', true)">Review Request</button>
                    <?php else: ?>
                        <button class="act-btn act-btn-view" onclick="viewReservation(<?= $id ?>, '<?= $row['reservation_no'] ?>', false)">View Details</button>
                        <a class="act-btn act-btn-print" href="print_requisition.php?id=<?= $id ?>" target="_blank" style="margin-top:6px;">
                            <i class="bi bi-printer-fill"></i> Print Form
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

    <?php else: ?>
        <div class="empty-state">
            <i class="bi bi-calendar-x"></i>
            <p>No <strong><?= $status_tab ?></strong> requisitions found.</p>
        </div>
    <?php endif; ?>
</div>
