<?php
require('header.php');

$start_date = isset($_GET['start']) ? mysqli_real_escape_string($con, $_GET['start']) : '';
$end_date = isset($_GET['end']) ? mysqli_real_escape_string($con, $_GET['end']) : '';
$item_filter = isset($_GET['item_id']) ? (int) $_GET['item_id'] : '';
$change_filter = isset($_GET['type']) ? mysqli_real_escape_string($con, $_GET['type']) : '';

$where = '1=1';
if ($start_date)
    $where .= " AND DATE(l.created_at) >= '$start_date'";
if ($end_date)
    $where .= " AND DATE(l.created_at) <= '$end_date'";
if ($item_filter)
    $where .= " AND l.item_id = $item_filter";
if ($change_filter)
    $where .= " AND l.change_type = '$change_filter'";

$items = mysqli_query($con, 'SELECT id, item_name FROM lab_items ORDER BY item_name');

// Summary counts for the stat strip
$total_q = mysqli_query($con, "SELECT COUNT(*) as c FROM lab_item_logs l WHERE $where");
$added_q = mysqli_query($con, "SELECT COALESCE(SUM(quantity_change),0) as s FROM lab_item_logs l WHERE $where AND l.change_type='+'");
$removed_q = mysqli_query($con, "SELECT COALESCE(SUM(quantity_change),0) as s FROM lab_item_logs l WHERE $where AND l.change_type='-'");
$total_logs = mysqli_fetch_assoc($total_q)['c'];
$total_added = mysqli_fetch_assoc($added_q)['s'];
$total_removed = mysqli_fetch_assoc($removed_q)['s'];
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap');

    :root {
        --red: #C0392B;
        --red-soft: rgba(192, 57, 43, 0.09);
        --red-glow: rgba(192, 57, 43, 0.18);
        --bg: #f0ede8;
        --surface: #faf9f7;
        --surface-2: #f0ede8;
        --surface-3: #e8e4de;
        --border: rgba(0, 0, 0, 0.07);
        --border-2: rgba(0, 0, 0, 0.13);
        --text: #1a1714;
        --text-2: rgba(26, 23, 20, 0.60);
        --text-3: rgba(26, 23, 20, 0.38);
    }

    body,
    .main-content,
    #main-content {
        background: var(--bg) !important;
        color: var(--text) !important;
        font-family: 'Sora', sans-serif !important;
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(20px)
        }

        to {
            opacity: 1;
            transform: translateY(0)
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0
        }

        to {
            opacity: 1
        }
    }

    @keyframes slideRight {
        from {
            transform: scaleX(0)
        }

        to {
            transform: scaleX(1)
        }
    }

    /* ── Page wrapper ── */
    .page-wrap {
        padding: 32px 32px 48px;
        max-width: 1400px;
        animation: fadeIn .45s ease;
    }

    /* ── Page header ── */
    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 28px;
        animation: fadeUp .5s ease forwards;
    }

    .page-eyebrow {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: var(--red);
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .page-eyebrow::before {
        content: '';
        display: inline-block;
        width: 20px;
        height: 1px;
        background: var(--red);
    }

    .page-title {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 700;
        color: var(--text);
        margin: 0;
        line-height: 1.1;
    }

    /* ── Buttons ── */
    .btn-ghost {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 18px;
        border-radius: 8px;
        font-size: 0.83rem;
        font-weight: 600;
        font-family: 'Sora', sans-serif;
        background: var(--surface);
        color: var(--text-2);
        border: 1px solid var(--border-2);
        cursor: pointer;
        transition: all .18s;
        text-decoration: none;
    }

    .btn-ghost:hover {
        background: var(--surface-2);
        color: var(--text);
    }

    .btn-prim {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 20px;
        border-radius: 8px;
        font-size: 0.83rem;
        font-weight: 600;
        font-family: 'Sora', sans-serif;
        background: var(--red);
        color: #fff;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 14px var(--red-glow);
        transition: all .18s;
        text-decoration: none;
    }

    .btn-prim:hover {
        background: #a93226;
        transform: translateY(-1px);
        color: #fff;
    }

    /* ── Summary strip ── */
    .summary-strip {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-bottom: 20px;
        animation: fadeUp .5s ease forwards .06s;
        opacity: 0;
    }

    .summary-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        position: relative;
        overflow: hidden;
        transition: border-color .2s, transform .2s;
    }

    .summary-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--sc-color, var(--red));
        transform-origin: left;
        animation: slideRight .6s ease forwards .3s;
        transform: scaleX(0);
    }

    .summary-card:hover {
        border-color: var(--sc-color, var(--border-2));
        transform: translateY(-2px);
    }

    .summary-card[data-c="total"] {
        --sc-color: #6b7280;
    }

    .summary-card[data-c="added"] {
        --sc-color: #059669;
    }

    .summary-card[data-c="removed"] {
        --sc-color: var(--red);
    }

    .sc-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .sc-icon.neutral {
        background: rgba(107, 114, 128, .1);
        color: #374151;
    }

    .sc-icon.green {
        background: rgba(5, 150, 105, .1);
        color: #064e3b;
    }

    .sc-icon.red {
        background: var(--red-soft);
        color: var(--red);
    }

    .sc-label {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--text-3);
        margin-bottom: 3px;
    }

    .sc-value {
        font-family: 'Playfair Display', serif;
        font-size: 1.9rem;
        font-weight: 700;
        color: var(--text);
        line-height: 1;
        animation: fadeUp .5s ease forwards .3s;
        opacity: 0;
    }

    /* ── Filter bar ── */
    .filter-bar {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 18px 20px;
        margin-bottom: 20px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: flex-end;
        animation: fadeUp .5s ease forwards .1s;
        opacity: 0;
    }

    .filter-field {
        display: flex;
        flex-direction: column;
        gap: 5px;
        flex: 1;
        min-width: 140px;
    }

    .filter-label {
        font-size: 0.68rem;
        font-weight: 600;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--text-3);
    }

    .filter-input,
    .filter-select {
        padding: 9px 13px;
        border: 1.5px solid var(--border-2);
        border-radius: 8px;
        font-size: 0.85rem;
        font-family: 'Sora', sans-serif;
        color: var(--text);
        background: var(--surface-2);
        outline: none;
        transition: border-color .15s, box-shadow .15s;
        width: 100%;
    }

    .filter-input:focus,
    .filter-select:focus {
        border-color: var(--red);
        box-shadow: 0 0 0 3px rgba(192, 57, 43, .08);
    }

    .filter-input[type="date"] {
        color: var(--text-2);
    }

    .filter-actions {
        display: flex;
        gap: 8px;
        align-items: flex-end;
    }

    /* ── Log panel ── */
    .log-panel {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        animation: fadeUp .5s ease forwards .16s;
        opacity: 0;
    }

    .log-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
        background: var(--surface-2);
    }

    .log-panel-title {
        display: flex;
        align-items: center;
        gap: 9px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text);
    }

    .log-panel-icon {
        width: 28px;
        height: 28px;
        border-radius: 7px;
        background: var(--red-soft);
        color: var(--red);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }

    .log-count {
        font-size: 0.72rem;
        color: var(--text-3);
        font-weight: 400;
    }

    /* ── Table ── */
    .log-table {
        width: 100%;
        border-collapse: collapse;
    }

    .log-table thead tr {
        border-bottom: 1px solid var(--border-2);
    }

    .log-table thead th {
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--text-3);
        padding: 12px 20px;
        text-align: left;
        background: var(--surface-2);
        white-space: nowrap;
    }

    .log-table thead th.center {
        text-align: center;
    }

    .log-table tbody tr {
        border-bottom: 1px solid rgba(0, 0, 0, 0.04);
        transition: background .12s;
        animation: fadeUp .35s ease forwards;
        opacity: 0;
    }

    .log-table tbody tr:last-child {
        border-bottom: none;
    }

    .log-table tbody tr:hover {
        background: rgba(0, 0, 0, 0.02);
    }

    /* Stagger first 12 rows */
    .log-table tbody tr:nth-child(1) {
        animation-delay: .20s
    }

    .log-table tbody tr:nth-child(2) {
        animation-delay: .23s
    }

    .log-table tbody tr:nth-child(3) {
        animation-delay: .26s
    }

    .log-table tbody tr:nth-child(4) {
        animation-delay: .29s
    }

    .log-table tbody tr:nth-child(5) {
        animation-delay: .32s
    }

    .log-table tbody tr:nth-child(6) {
        animation-delay: .35s
    }

    .log-table tbody tr:nth-child(7) {
        animation-delay: .38s
    }

    .log-table tbody tr:nth-child(8) {
        animation-delay: .41s
    }

    .log-table tbody tr:nth-child(9) {
        animation-delay: .44s
    }

    .log-table tbody tr:nth-child(10) {
        animation-delay: .47s
    }

    .log-table tbody tr:nth-child(11) {
        animation-delay: .50s
    }

    .log-table tbody tr:nth-child(12) {
        animation-delay: .53s
    }

    .log-table tbody tr:nth-child(n+13) {
        animation-delay: .55s
    }

    .log-table td {
        padding: 13px 20px;
        font-size: 0.83rem;
        color: var(--text-2);
        vertical-align: middle;
    }

    /* Date cell */
    .td-date-main {
        font-weight: 600;
        color: var(--text);
        font-size: .83rem;
    }

    .td-date-sub {
        font-size: .72rem;
        color: var(--text-3);
        margin-top: 1px;
    }

    /* Item name */
    .td-item {
        font-weight: 600;
        color: var(--text);
    }

    /* Change type pill */
    .change-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 11px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .change-add {
        background: rgba(5, 150, 105, .10);
        color: #065f46;
    }

    .change-remove {
        background: var(--red-soft);
        color: #7f1d1d;
    }

    /* Quantity cell */
    .td-qty {
        font-family: 'Playfair Display', serif;
        font-size: 1.15rem;
        font-weight: 700;
        text-align: center;
        color: var(--text);
    }

    /* Remarks */
    .td-remarks {
        color: var(--text-2);
        font-size: .8rem;
        max-width: 220px;
    }

    /* Performed by */
    .user-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.73rem;
        font-weight: 600;
        background: var(--surface-3);
        color: var(--text-2);
    }

    .user-pill i {
        font-size: .7rem;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 64px 24px;
        color: var(--text-3);
    }

    .empty-state i {
        font-size: 2.5rem;
        display: block;
        margin-bottom: 12px;
        opacity: .3;
    }

    .empty-state p {
        font-size: 0.88rem;
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 900px) {
        .summary-strip {
            grid-template-columns: 1fr;
        }

        .page-wrap {
            padding: 20px 16px 40px;
        }

        .log-table {
            display: block;
            overflow-x: auto;
        }
    }

    @media (max-width: 640px) {
        .filter-bar {
            flex-direction: column;
        }

        .filter-field {
            min-width: 100%;
        }
    }
</style>

<div class="page-wrap">

    <!-- Header -->
    <div class="page-header">
        <div>
            <div class="page-eyebrow">Admin Panel</div>
            <h1 class="page-title">Item Logs</h1>
        </div>
        <a href="inventory.php" class="btn-ghost">
            <i class="bi bi-arrow-left"></i> Back to Inventory
        </a>
    </div>

    <!-- Summary strip -->
    <div class="summary-strip">
        <div class="summary-card" data-c="total">
            <div class="sc-icon neutral"><i class="bi bi-journal-text"></i></div>
            <div>
                <div class="sc-label">Total Logs</div>
                <div class="sc-value"><?= number_format($total_logs) ?></div>
            </div>
        </div>
        <div class="summary-card" data-c="added">
            <div class="sc-icon green"><i class="bi bi-plus-circle"></i></div>
            <div>
                <div class="sc-label">Total Added</div>
                <div class="sc-value"><?= number_format($total_added) ?></div>
            </div>
        </div>
        <div class="summary-card" data-c="removed">
            <div class="sc-icon red"><i class="bi bi-dash-circle"></i></div>
            <div>
                <div class="sc-label">Total Removed</div>
                <div class="sc-value"><?= number_format($total_removed) ?></div>
            </div>
        </div>
    </div>

    <!-- Filter bar -->
    <div class="filter-bar">
        <form style="display:contents;" method="GET">
            <?php if ($item_filter): ?>
                <input type="hidden" name="item_id" value="<?= $item_filter ?>">
            <?php endif; ?>
            <div class="filter-field">
                <label class="filter-label">Start Date</label>
                <input type="date" name="start" class="filter-input" value="<?= htmlspecialchars($start_date) ?>">
            </div>
            <div class="filter-field">
                <label class="filter-label">End Date</label>
                <input type="date" name="end" class="filter-input" value="<?= htmlspecialchars($end_date) ?>">
            </div>
            <div class="filter-field">
                <label class="filter-label">Item</label>
                <select name="item_id" class="filter-select">
                    <option value="">All Items</option>
                    <?php while ($c = mysqli_fetch_assoc($items)): ?>
                        <option value="<?= $c['id'] ?>" <?= $item_filter == $c['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['item_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="filter-field">
                <label class="filter-label">Change Type</label>
                <select name="type" class="filter-select">
                    <option value="">All Types</option>
                    <option value="+" <?= $change_filter == '+' ? 'selected' : '' ?>>Added (+)</option>
                    <option value="-" <?= $change_filter == '-' ? 'selected' : '' ?>>Removed (−)</option>
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-prim">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                <?php if ($start_date || $end_date || $change_filter || ($item_filter && !isset($_GET['item_id_locked']))): ?>
                    <a href="item_logs.php<?= $item_filter ? '?item_id=' . $item_filter : '' ?>" class="btn-ghost">
                        <i class="bi bi-x-lg"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Log table -->
    <div class="log-panel">
        <div class="log-panel-header">
            <div class="log-panel-title">
                <div class="log-panel-icon"><i class="bi bi-clock-history"></i></div>
                Activity Log
            </div>
            <span class="log-count"><?= number_format($total_logs) ?> record<?= $total_logs != 1 ? 's' : '' ?></span>
        </div>

        <div style="overflow-x:auto;">
            <table class="log-table">
                <thead>
                    <tr>
                        <th>Date &amp; Time</th>
                        <th>Item</th>
                        <th class="center">Action</th>
                        <th class="center">Qty</th>
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

                    if (mysqli_num_rows($res) > 0):
                        while ($row = mysqli_fetch_assoc($res)):
                            $is_add = $row['change_type'] === '+';
                            $admin = $row['username'] ?: 'System';
                            ?>
                            <tr>
                                <td>
                                    <div class="td-date-main"><?= date('M d, Y', strtotime($row['created_at'])) ?></div>
                                    <div class="td-date-sub"><?= date('h:i A', strtotime($row['created_at'])) ?></div>
                                </td>
                                <td class="td-item"><?= htmlspecialchars($row['item_name']) ?></td>
                                <td style="text-align:center;">
                                    <span class="change-pill <?= $is_add ? 'change-add' : 'change-remove' ?>">
                                        <i class="bi <?= $is_add ? 'bi-plus-lg' : 'bi-dash-lg' ?>"></i>
                                        <?= $is_add ? 'Added' : 'Removed' ?>
                                    </span>
                                </td>
                                <td class="td-qty"><?= htmlspecialchars($row['quantity_change']) ?></td>
                                <td class="td-remarks"><?= htmlspecialchars($row['remarks']) ?></td>
                                <td>
                                    <span class="user-pill">
                                        <i class="bi bi-person"></i>
                                        <?= htmlspecialchars($admin) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; else: ?>
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="bi bi-clock-history"></i>
                                    <p>No logs found matching the selected criteria.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php require('footer.php'); ?>