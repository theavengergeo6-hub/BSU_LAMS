<?php
require('header.php');

$cat_filter = isset($_GET['category_id']) ? (int) $_GET['category_id'] : '';
$search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';

$where = '1=1';
if ($cat_filter)
    $where .= " AND category_id = $cat_filter";
if ($search)
    $where .= " AND item_name LIKE '%$search%'";

$categories = mysqli_query($con, 'SELECT * FROM lab_categories');
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap');

    :root {
        --red: #C0392B;
        --red-soft: rgba(192, 57, 43, 0.09);
        --red-glow: rgba(192, 57, 43, 0.18);
        --blue-soft: rgba(29, 78, 216, 0.09);
        --teal-soft: rgba(15, 118, 110, 0.09);
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

    @keyframes spin {
        to {
            transform: rotate(360deg)
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
    .btn-prim {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 0.83rem;
        font-weight: 600;
        font-family: 'Sora', sans-serif;
        background: var(--red);
        color: #fff;
        border: none;
        cursor: pointer;
        text-decoration: none;
        box-shadow: 0 4px 14px var(--red-glow);
        transition: all .18s;
    }

    .btn-prim:hover {
        background: #a93226;
        transform: translateY(-1px);
        color: #fff;
        box-shadow: 0 6px 20px var(--red-glow);
    }

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
        animation: fadeUp .5s ease forwards .06s;
        opacity: 0;
    }

    .filter-field {
        display: flex;
        flex-direction: column;
        gap: 5px;
        flex: 1;
        min-width: 160px;
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

    .filter-input::placeholder {
        color: var(--text-3);
    }

    .filter-actions {
        display: flex;
        gap: 8px;
        align-items: flex-end;
    }

    /* ── Table panel ── */
    .inv-panel {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        animation: fadeUp .5s ease forwards .12s;
        opacity: 0;
    }

    /* ── Table ── */
    .inv-table {
        width: 100%;
        border-collapse: collapse;
    }

    .inv-table thead tr {
        border-bottom: 1px solid var(--border-2);
    }

    .inv-table thead th {
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--text-3);
        padding: 13px 20px;
        text-align: left;
        background: var(--surface-2);
        white-space: nowrap;
    }

    .inv-table thead th.center {
        text-align: center;
    }

    .inv-table tbody tr {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        transition: background .15s, box-shadow .15s;
        animation: fadeUp .4s ease forwards;
        opacity: 0;
    }

    .inv-table tbody tr:last-child {
        border-bottom: none;
    }

    .inv-table tbody tr:hover {
        background: rgba(0, 0, 0, 0.025);
        box-shadow: inset 3px 0 0 var(--red);
    }

    .inv-table tbody tr:nth-child(1) {
        animation-delay: .16s
    }

    .inv-table tbody tr:nth-child(2) {
        animation-delay: .20s
    }

    .inv-table tbody tr:nth-child(3) {
        animation-delay: .24s
    }

    .inv-table tbody tr:nth-child(4) {
        animation-delay: .28s
    }

    .inv-table tbody tr:nth-child(5) {
        animation-delay: .32s
    }

    .inv-table tbody tr:nth-child(6) {
        animation-delay: .36s
    }

    .inv-table tbody tr:nth-child(7) {
        animation-delay: .40s
    }

    .inv-table tbody tr:nth-child(8) {
        animation-delay: .44s
    }

    .inv-table tbody tr:nth-child(n+9) {
        animation-delay: .48s
    }

    .inv-table td {
        padding: 14px 20px;
        font-size: 0.84rem;
        color: var(--text-2);
        vertical-align: middle;
    }

    .td-img-wrap {
        width: 52px;
        height: 52px;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid var(--border-2);
        background: var(--surface-2);
        flex-shrink: 0;
    }

    .td-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .3s ease;
    }

    .td-img-wrap:hover img {
        transform: scale(1.12);
    }

    .td-item-name {
        font-weight: 600;
        color: var(--text);
        font-size: .9rem;
    }

    .td-cat {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 600;
        background: var(--surface-3);
        color: var(--text-2);
    }

    .td-center {
        text-align: center;
    }

    .qty-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 42px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .qty-ok {
        background: rgba(4, 120, 87, .10);
        color: #064e3b;
    }

    .qty-zero {
        background: rgba(192, 57, 43, .10);
        color: #7f1d1d;
    }

    /* ── Icon action buttons ── */
    .icon-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 7px;
        border: 1px solid var(--border-2);
        background: var(--surface-2);
        color: var(--text-2);
        font-size: 0.85rem;
        cursor: pointer;
        transition: all .15s;
        text-decoration: none;
    }

    .icon-btn:hover {
        transform: translateY(-2px);
    }

    .icon-btn.edit {
        color: #1e40af;
        border-color: rgba(29, 78, 216, .25);
        background: rgba(29, 78, 216, .07);
    }

    .icon-btn.edit:hover {
        background: rgba(29, 78, 216, .15);
    }

    .icon-btn.add {
        color: #064e3b;
        border-color: rgba(4, 120, 87, .25);
        background: rgba(4, 120, 87, .07);
    }

    .icon-btn.add:hover {
        background: rgba(4, 120, 87, .15);
    }

    .icon-btn.remove {
        color: #7f1d1d;
        border-color: rgba(192, 57, 43, .25);
        background: rgba(192, 57, 43, .07);
    }

    .icon-btn.remove:hover {
        background: rgba(192, 57, 43, .15);
    }

    .icon-btn.logs {
        color: var(--text-2);
    }

    .icon-btn.logs:hover {
        background: var(--surface-3);
        color: var(--text);
    }

    .icon-actions {
        display: flex;
        gap: 6px;
        justify-content: center;
    }

    /* ── Empty state ── */
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

    /* ── Modal ── */
    .modal-content {
        border: none !important;
        border-radius: 16px !important;
        overflow: hidden;
        font-family: 'Sora', sans-serif;
        background: var(--surface) !important;
        color: var(--text) !important;
        box-shadow: 0 24px 64px rgba(0, 0, 0, 0.14) !important;
    }

    .modal-header-custom {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .modal-header-icon {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .mhi-red {
        background: var(--red-soft);
        color: var(--red);
    }

    .mhi-blue {
        background: rgba(29, 78, 216, .1);
        color: #1e40af;
    }

    .mhi-teal {
        background: rgba(15, 118, 110, .1);
        color: #065f46;
    }

    .modal-header-title {
        font-weight: 700;
        font-size: 1rem;
        color: var(--text);
        flex: 1;
    }

    .modal-close-btn {
        background: var(--surface-2);
        border: 1px solid var(--border-2);
        border-radius: 7px;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--text-2);
        transition: all .15s;
        font-size: 0.9rem;
    }

    .modal-close-btn:hover {
        background: var(--red-soft);
        color: var(--red);
    }

    /* Modal form fields */
    .modal-body {
        padding: 24px;
        background: var(--surface);
    }

    .mfield {
        display: flex;
        flex-direction: column;
        gap: 5px;
        margin-bottom: 16px;
    }

    .mfield:last-of-type {
        margin-bottom: 0;
    }

    .mfield label {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--text-3);
    }

    .mfield input,
    .mfield select,
    .mfield textarea {
        padding: 10px 13px;
        border: 1.5px solid var(--border-2);
        border-radius: 8px;
        font-size: 0.88rem;
        font-family: 'Sora', sans-serif;
        color: var(--text);
        background: var(--surface-2);
        outline: none;
        transition: border-color .15s, box-shadow .15s;
        width: 100%;
    }

    .mfield input:focus,
    .mfield select:focus {
        border-color: var(--red);
        box-shadow: 0 0 0 3px rgba(192, 57, 43, .08);
        background: var(--surface);
    }

    .mfield input[type="file"] {
        padding: 8px 13px;
    }

    .mfield input::placeholder {
        color: var(--text-3);
    }

    /* Modal row grid */
    .mrow {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    /* Divider in modal */
    .mdivider {
        border: none;
        border-top: 1px solid var(--border);
        margin: 20px 0;
    }

    /* Modal footer */
    .modal-footer-custom {
        padding: 16px 24px;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        background: var(--surface);
    }

    /* Checkbox style */
    .mcheck {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 10px;
    }

    .mcheck input[type="checkbox"] {
        accent-color: var(--red);
        width: 15px;
        height: 15px;
        cursor: pointer;
    }

    .mcheck label {
        font-size: 0.82rem;
        color: var(--red);
        font-weight: 600;
        cursor: pointer;
    }

    /* Spinner */
    .dash-spinner {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 48px;
        gap: 12px;
        color: var(--text-3);
        font-size: 0.85rem;
    }

    .spin-ring {
        width: 22px;
        height: 22px;
        border: 2px solid var(--border-2);
        border-top-color: var(--red);
        border-radius: 50%;
        animation: spin .7s linear infinite;
    }

    /* Adjust qty big input */
    .qty-big-input {
        font-size: 1.8rem !important;
        font-weight: 700 !important;
        text-align: center !important;
        padding: 14px !important;
        font-family: 'Playfair Display', serif !important;
    }

    @media (max-width: 768px) {
        .page-wrap {
            padding: 20px 16px 40px;
        }

        .filter-bar {
            flex-direction: column;
        }

        .mrow {
            grid-template-columns: 1fr;
        }

        .inv-table {
            display: block;
            overflow-x: auto;
        }
    }
</style>

<div class="page-wrap">

    <!-- Header -->
    <div class="page-header">
        <div>
            <div class="page-eyebrow">Admin Panel</div>
            <h1 class="page-title">Manage Inventory</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="generate_disposal_report.php" target="_blank" class="btn-ghost" style="color:var(--text); border-color:var(--border-2);">
                <i class="bi bi-file-earmark-pdf"></i> Disposal Report
            </a>
            <button class="btn-prim" data-bs-toggle="modal" data-bs-target="#addItemModal">
                <i class="bi bi-plus-lg"></i> Add New Item
            </button>
        </div>
    </div>

    <!-- Filter bar -->
    <div class="filter-bar">
        <form id="filterForm" style="display:contents;" method="GET" onsubmit="event.preventDefault(); fetchResults();">
            <div class="filter-field">
                <label class="filter-label">Category</label>
                <select name="category_id" class="filter-select" onchange="autoFilter()">
                    <option value="">All Categories</option>
                    <?php while ($c = mysqli_fetch_assoc($categories)): ?>
                        <option value="<?= $c['id'] ?>" <?= $cat_filter == $c['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="filter-field" style="flex:2;">
                <label class="filter-label">Search</label>
                <input type="text" name="search" class="filter-input" placeholder="Search item name…"
                    value="<?= htmlspecialchars($search) ?>" oninput="autoSearch()">
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-prim" style="padding:9px 20px;">
                    <i class="bi bi-search"></i> Search
                </button>
                <?php if ($search || $cat_filter): ?>
                    <a href="inventory.php" class="btn-ghost" style="padding:9px 16px;">
                        <i class="bi bi-x-lg"></i> Clear
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="inv-panel">
        <div style="overflow-x:auto;">
            <table class="inv-table">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th class="center">Acq. Date</th>
                        <th class="center">Age</th>
                        <th class="center">Total</th>
                        <th class="center">Available</th>
                        <th class="center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q = "SELECT i.*, c.name as cat_name FROM lab_items i JOIN lab_categories c ON i.category_id = c.id WHERE $where ORDER BY i.item_name ASC";
                    $res = mysqli_query($con, $q);

                    if (mysqli_num_rows($res) > 0):
                        while ($row = mysqli_fetch_assoc($res)):
                            $img = $row['image_path'] ? "../uploads/lab_items/{$row['image_path']}" : '../assets/images/placeholder.png';
                            $avail_class = $row['available_quantity'] > 0 ? 'qty-ok' : 'qty-zero';
                            ?>
                            <tr>
                                <td>
                                    <div class="td-img-wrap">
                                        <img src="<?= $img ?>" alt="<?= htmlspecialchars($row['item_name']) ?>"
                                            onerror="this.src='../assets/images/placeholder.png'">
                                    </div>
                                </td>
                                <td class="td-item-name"><?= htmlspecialchars($row['item_name']) ?></td>
                                <td><span class="td-cat"><?= htmlspecialchars($row['cat_name']) ?></span></td>
                                <td class="td-center" style="color:var(--text-2);font-size:.82rem;">
                                    <?= $row['acquisition_date'] ? date('Y-m-d', strtotime($row['acquisition_date'])) : 'N/A' ?>
                                </td>
                                <td class="td-center">
                                    <?php
                                    if ($row['acquisition_date']) {
                                        $ad = new DateTime($row['acquisition_date']);
                                        $now = new DateTime();
                                        $diff = $now->diff($ad);
                                        $age_str = "";
                                        if ($diff->y > 0) $age_str .= $diff->y . "y ";
                                        if ($diff->m > 0) $age_str .= $diff->m . "m";
                                        if (empty($age_str)) $age_str = "New";

                                        $color = "text-success"; // < 1y
                                        if ($diff->y >= 5) $color = "text-danger";
                                        else if ($diff->y >= 3) $color = "text-warning"; // Using warning for orange-ish
                                        else if ($diff->y >= 1) $color = "text-info"; // Customizing colors below or using standard BS

                                        // Refined color coding based on 1.4 requirements
                                        $style = "font-weight:700;";
                                        if ($diff->y < 1) $style .= "color:#059669;"; // Green
                                        else if ($diff->y < 3) $style .= "color:#d97706;"; // Yellow/Orange
                                        else if ($diff->y < 5) $style .= "color:#ea580c;"; // Orange
                                        else $style .= "color:#dc2626;"; // Red

                                        echo "<span style='$style' title='{$diff->y} years, {$diff->m} months'>$age_str</span>";
                                    } else {
                                        echo "N/A";
                                    }
                                    ?>
                                </td>
                                <td class="td-center" style="font-weight:700;color:var(--text);"><?= $row['total_quantity'] ?>
                                </td>
                                <td class="td-center">
                                    <span class="qty-pill <?= $avail_class ?>"><?= $row['available_quantity'] ?></span>
                                </td>
                                <td>
                                    <div class="icon-actions">
                                        <button class="icon-btn edit" title="Edit item" onclick="editItem(<?= $row['id'] ?>)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="icon-btn add" title="Add quantity"
                                            onclick="adjustQty(<?= $row['id'] ?>, '+')">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>
                                        <button class="icon-btn remove" title="Remove quantity"
                                            onclick="adjustQty(<?= $row['id'] ?>, '-')">
                                            <i class="bi bi-dash-lg"></i>
                                        </button>
                                        <a href="item_logs.php?item_id=<?= $row['id'] ?>" class="icon-btn logs"
                                            title="View logs">
                                            <i class="bi bi-clock-history"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; else: ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="bi bi-box-seam"></i>
                                    <p>No items found<?= ($search || $cat_filter) ? ' matching your filter' : '' ?>.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- ═══════════════════════════════
     MODAL: Add Item
═══════════════════════════════ -->
<div class="modal fade" id="addItemModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header-custom">
                <div class="modal-header-icon mhi-red"><i class="bi bi-plus-circle"></i></div>
                <div class="modal-header-title">Add New Item</div>
                <button class="modal-close-btn" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-body">
                <form id="addItemForm" onsubmit="submitAddItem(event)">
                    <div class="mfield">
                        <label>Item Name *</label>
                        <input type="text" name="item_name" placeholder="e.g. Chef's Knife" required>
                    </div>
                    <div class="mrow">
                        <div class="mfield">
                            <label>Category *</label>
                            <select name="category_id" required>
                                <?php mysqli_data_seek($categories, 0);
                                while ($c = mysqli_fetch_assoc($categories)): ?>
                                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mfield">
                            <label>Unit *</label>
                            <input type="text" name="unit" placeholder="e.g. pcs, sets" required>
                        </div>
                    </div>
                    <div class="mrow">
                        <div class="mfield">
                            <label>Total Quantity *</label>
                            <input type="number" name="total_quantity" min="0" max="9999" placeholder="0" required>
                        </div>
                        <div class="mfield">
                            <label>Available Quantity *</label>
                            <input type="number" name="available_quantity" min="0" max="9999" placeholder="0" required>
                        </div>
                    </div>
                    <div class="mfield">
                        <label>Acquisition Date *</label>
                        <input type="date" name="acquisition_date" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <hr class="mdivider">
                    <div class="mfield">
                        <label>Photo (Optional)</label>
                        <input type="file" name="item_photo" accept=".jpg,.jpeg,.png">
                    </div>
                </form>
            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn-ghost" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addItemForm" class="btn-prim">
                    <i class="bi bi-check-lg"></i> Save Item
                </button>
            </div>
        </div>
    </div>
</div>


<!-- ═══════════════════════════════
     MODAL: Edit Item
═══════════════════════════════ -->
<div class="modal fade" id="editItemModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header-custom">
                <div class="modal-header-icon mhi-blue"><i class="bi bi-pencil"></i></div>
                <div class="modal-header-title">Edit Item Details</div>
                <button class="modal-close-btn" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-body">
                <form id="editItemForm" onsubmit="submitEditItem(event)">
                    <input type="hidden" name="item_id" id="edit_item_id">
                    <div class="mfield">
                        <label>Item Name *</label>
                        <input type="text" name="item_name" id="edit_item_name" required>
                    </div>
                    <div class="mrow">
                        <div class="mfield">
                            <label>Category *</label>
                            <select name="category_id" id="edit_category_id" required>
                                <?php mysqli_data_seek($categories, 0);
                                while ($c = mysqli_fetch_assoc($categories)): ?>
                                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    <div class="mrow">
                        <div class="mfield">
                            <label>Unit *</label>
                            <input type="text" name="unit" id="edit_unit" required>
                        </div>
                    </div>
                    <div class="mrow">
                        <div class="mfield">
                            <label>Total Quantity *</label>
                            <input type="number" name="total_quantity" id="edit_total_quantity" min="0" max="9999" required>
                        </div>
                        <div class="mfield">
                            <label>Available Quantity *</label>
                            <input type="number" name="available_quantity" id="edit_available_quantity" min="0" max="9999" required>
                        </div>
                    </div>
                    <div class="mfield">
                        <label>Acquisition Date *</label>
                        <input type="date" name="acquisition_date" id="edit_acquisition_date" required>
                    </div>
                    <hr class="mdivider">
                    <div class="mfield">
                        <label>Update Photo</label>
                        <input type="file" name="item_photo" accept=".jpg,.jpeg,.png">
                        <div class="mcheck">
                            <input type="checkbox" name="remove_photo" value="1" id="removePhotoCheck">
                            <label for="removePhotoCheck">Remove current photo</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn-ghost" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="editItemForm" class="btn-prim"
                    style="background:#1e40af;box-shadow:0 4px 14px rgba(29,78,216,.2);">
                    <i class="bi bi-check-lg"></i> Save Changes
                </button>
            </div>
        </div>
    </div>
</div>


<!-- ═══════════════════════════════
     MODAL: Adjust Quantity
═══════════════════════════════ -->
<div class="modal fade" id="adjustQtyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header-custom">
                <div class="modal-header-icon" id="adjHeaderIcon" style="background:rgba(4,120,87,.1);color:#064e3b;">
                    <i class="bi bi-calculator" id="adjHeaderIco"></i>
                </div>
                <div class="modal-header-title" id="adjModalTitle">Adjust Quantity</div>
                <button class="modal-close-btn" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-body">
                <form id="adjustQtyForm" onsubmit="submitAdjustQty(event)">
                    <input type="hidden" name="item_id" id="adj_item_id">
                    <input type="hidden" name="change_type" id="adj_change_type">
                    <div class="mfield">
                        <label id="adj_label">Quantity</label>
                        <input type="number" name="quantity_change" class="qty-big-input" min="1" placeholder="0"
                            required>
                    </div>
                    <div class="mfield">
                        <label>Remarks / Reason *</label>
                        <input type="text" name="remarks" placeholder="e.g. New delivery, Damaged, Lost" required>
                    </div>
                    <div id="disposal_section" style="display:none;">
                        <div class="mcheck">
                            <input type="checkbox" name="is_disposal" value="1" id="isDisposalCheck" onchange="toggleDisposalReason(this)">
                            <label for="isDisposalCheck">Mark as Disposed</label>
                        </div>
                        <div class="mfield mt-2" id="disposal_reason_field" style="display:none;">
                            <label>Disposal Reason *</label>
                            <input type="text" name="disposal_reason" id="disposal_reason_input" placeholder="e.g. Beyond repair, Obsolete">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn-ghost" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="adjustQtyForm" class="btn-prim" id="adj_btn">
                    <i class="bi bi-check-lg"></i> Confirm
                </button>
            </div>
        </div>
    </div>
</div>


<script>
    function editItem(id) {
        fetch('../ajax/get_item.php?id=' + id)
            .then(r => r.json())
            .then(data => {
                document.getElementById('edit_item_id').value = data.id;
                document.getElementById('edit_item_name').value = data.item_name;
                document.getElementById('edit_category_id').value = data.category_id;
                document.getElementById('edit_unit').value = data.unit;
                document.getElementById('edit_total_quantity').value = data.total_quantity;
                document.getElementById('edit_available_quantity').value = data.available_quantity;
                document.getElementById('edit_acquisition_date').value = data.acquisition_date ? data.acquisition_date.split(' ')[0] : '<?= date('Y-m-d') ?>';
                document.getElementById('removePhotoCheck').checked = false;
                new bootstrap.Modal(document.getElementById('editItemModal')).show();
            });
    }

    function submitEditItem(e) {
        e.preventDefault();
        fetch('../ajax/edit_item.php', { method: 'POST', body: new FormData(document.getElementById('editItemForm')) })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Updated', text: 'Item saved successfully', toast: true, position: 'top-end', timer: 2000, showConfirmButton: false })
                        .then(() => location.reload());
                } else alert(data.message);
            });
    }

    function adjustQty(id, type) {
        document.getElementById('adj_item_id').value = id;
        document.getElementById('adj_change_type').value = type;

        const isAdd = type === '+';
        const icon = document.getElementById('adjHeaderIcon');
        const btnEl = document.getElementById('adj_btn');

        document.getElementById('adjModalTitle').textContent = isAdd ? 'Add Quantity' : 'Remove Quantity';
        document.getElementById('adj_label').textContent = isAdd ? 'Quantity to Add' : 'Quantity to Remove';

        if (isAdd) {
            icon.style.background = 'rgba(4,120,87,.1)';
            icon.style.color = '#064e3b';
            btnEl.style.background = '#065f46';
            btnEl.style.boxShadow = '0 4px 14px rgba(4,120,87,.2)';
            btnEl.innerHTML = '<i class="bi bi-plus-lg"></i> Add';
            document.getElementById('disposal_section').style.display = 'none';
        } else {
            icon.style.background = 'rgba(192,57,43,.1)';
            icon.style.color = '#7f1d1d';
            btnEl.style.background = '#C0392B';
            btnEl.style.boxShadow = '0 4px 14px rgba(192,57,43,.2)';
            btnEl.innerHTML = '<i class="bi bi-dash-lg"></i> Remove';
            document.getElementById('disposal_section').style.display = 'block';
        }

        document.getElementById('isDisposalCheck').checked = false;
        document.getElementById('disposal_reason_field').style.display = 'none';
        document.getElementById('disposal_reason_input').required = false;

        new bootstrap.Modal(document.getElementById('adjustQtyModal')).show();
    }

    function toggleDisposalReason(chk) {
        const field = document.getElementById('disposal_reason_field');
        const input = document.getElementById('disposal_reason_input');
        if (chk.checked) {
            field.style.display = 'block';
            input.required = true;
        } else {
            field.style.display = 'none';
            input.required = false;
        }
    }

    function submitAdjustQty(e) {
        e.preventDefault();
        fetch('../ajax/update_inventory.php', { method: 'POST', body: new FormData(document.getElementById('adjustQtyForm')) })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Done', text: 'Quantity updated', toast: true, position: 'top-end', timer: 2000, showConfirmButton: false })
                        .then(() => location.reload());
                } else alert(data.message);
            });
    }

    function submitAddItem(e) {
        e.preventDefault();
        fetch('../ajax/add_item.php', { method: 'POST', body: new FormData(document.getElementById('addItemForm')) })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Added', text: 'Item saved successfully', toast: true, position: 'top-end', timer: 2000, showConfirmButton: false })
                        .then(() => location.reload());
                } else alert(data.message);
            });
    }

    let searchTimer;
    function autoSearch() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            fetchResults();
        }, 300);
    }

    function autoFilter() {
        fetchResults();
    }

    function fetchResults() {
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);
        const url = 'inventory.php?' + params.toString();

        // Update URL without reload
        window.history.pushState({}, '', url);

        // Show loading state if desired (optional)
        const panel = document.querySelector('.inv-panel');
        panel.style.opacity = '0.5';

        fetch(url)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTable = doc.querySelector('.inv-panel').innerHTML;
                panel.innerHTML = newTable;
                panel.style.opacity = '1';
                
                // Update Clear button visibility
                const oldActions = document.querySelector('.filter-actions');
                const newActions = doc.querySelector('.filter-actions');
                if (oldActions && newActions) {
                    oldActions.innerHTML = newActions.innerHTML;
                }
            })
            .catch(err => console.error('Error fetching results:', err));
    }
</script>

<?php require('footer.php'); ?>