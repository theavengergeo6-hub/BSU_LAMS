<?php
require('header.php');

$start_date = isset($_GET['start']) ? mysqli_real_escape_string($con, $_GET['start']) : '';
$end_date = isset($_GET['end']) ? mysqli_real_escape_string($con, $_GET['end']) : '';
$item_filter = isset($_GET['item_id']) ? (int)$_GET['item_id'] : '';
$change_filter = isset($_GET['type']) ? mysqli_real_escape_string($con, $_GET['type']) : '';

$where = "1=1";
if($start_date) $where .= " AND DATE(l.created_at) >= '$start_date'";
if($end_date) $where .= " AND DATE(l.created_at) <= '$end_date'";
if($item_filter) $where .= " AND l.item_id = $item_filter";
if($change_filter) $where .= " AND l.change_type = '$change_filter'";

$items = mysqli_query($con, "SELECT id, item_name FROM lab_items ORDER BY item_name");
?>

<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
    <h4 class="fw-bold mb-0 text-danger"><i class="bi bi-clock-history me-2"></i>Item Logs</h4>
    <a href="inventory.php" class="btn btn-outline-secondary shadow-sm fw-medium"><i class="bi bi-arrow-left me-2"></i> Back to Inventory</a>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm custom-card mb-4 bg-white p-3">
    <form class="row g-3" method="GET">
        <div class="col-md-3">
            <label class="form-label small text-muted mb-1">Start Date</label>
            <input type="date" name="start" class="form-control bg-light" value="<?= $start_date ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label small text-muted mb-1">End Date</label>
            <input type="date" name="end" class="form-control bg-light" value="<?= $end_date ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label small text-muted mb-1">Item Filter</label>
            <select name="item_id" class="form-select bg-light">
                <option value="">All Items</option>
                <?php while($c = mysqli_fetch_assoc($items)): ?>
                    <option value="<?= $c['id'] ?>" <?= $item_filter == $c['id'] ? 'selected' : '' ?>><?= $c['item_name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small text-muted mb-1">Change Type</label>
            <select name="type" class="form-select bg-light">
                <option value="">All Types</option>
                <option value="+" <?= $change_filter == '+' ? 'selected' : '' ?>>Added (+)</option>
                <option value="-" <?= $change_filter == '-' ? 'selected' : '' ?>>Removed (-)</option>
            </select>
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button class="btn btn-primary w-100 shadow p-2"><i class="bi bi-filter"></i></button>
        </div>
    </form>
</div>

<!-- Table -->
<div class="card border-0 shadow-sm custom-card table-responsive">
    <div class="card-body p-0">
        <table class="table table-hover table-striped align-middle mb-0">
            <thead class="bg-dark text-white">
                <tr>
                    <th>Date & Time</th>
                    <th>Item</th>
                    <th class="text-center">Action</th>
                    <th class="text-center">Qty</th>
                    <th>Remarks</th>
                    <th>Performed By</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $q = "SELECT l.*, i.item_name, a.username 
                      FROM lab_item_logs l 
                      JOIN lab_items i ON l.item_id = i.id 
                      LEFT JOIN lab_admin_users a ON l.performed_by = a.id 
                      WHERE $where 
                      ORDER BY l.created_at DESC LIMIT 500";
                $res = mysqli_query($con, $q);
                
                if(mysqli_num_rows($res) > 0):
                    while($row = mysqli_fetch_assoc($res)):
                        $badge = $row['change_type'] == '+' ? 'badge bg-success' : 'badge bg-danger';
                        $admin = $row['username'] ? $row['username'] : 'System';
                ?>
                <tr>
                    <td>
                        <div class="fw-bold"><?= date('M d, Y', strtotime($row['created_at'])) ?></div>
                        <div class="small text-muted"><?= date('h:i A', strtotime($row['created_at'])) ?></div>
                    </td>
                    <td class="fw-medium"><?= $row['item_name'] ?></td>
                    <td class="text-center"><span class="<?= $badge ?>"><?= $row['change_type'] ?></span></td>
                    <td class="text-center fw-bold fs-5"><?= $row['quantity'] ?></td>
                    <td class="text-secondary"><?= $row['remarks'] ?></td>
                    <td><span class="badge bg-secondary"><i class="bi bi-person me-1"></i><?= $admin ?></span></td>
                </tr>
                <?php 
                    endwhile; 
                else: 
                ?>
                <tr><td colspan="6" class="text-center py-5 text-muted">No logs found matching criteria.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require('footer.php'); ?>
