<?php
require('header.php');

$status_tab = isset($_GET['tab']) ? $_GET['tab'] : 'Pending';
$statuses   = ['Pending', 'Approved', 'Ongoing', 'Completed', 'Denied'];
if (!in_array($status_tab, $statuses)) $status_tab = 'Pending';
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap');

:root {
    --red:       #C0392B;
    --red-soft:  rgba(192,57,43,0.09);
    --red-glow:  rgba(192,57,43,0.18);
    --bg:        #f0ede8;
    --surface:   #faf9f7;
    --surface-2: #f0ede8;
    --surface-3: #e8e4de;
    --border:    rgba(0,0,0,0.07);
    --border-2:  rgba(0,0,0,0.13);
    --text:      #1a1714;
    --text-2:    rgba(26,23,20,0.60);
    --text-3:    rgba(26,23,20,0.38);
}

body, .main-content, #main-content {
    background: var(--bg) !important;
    color: var(--text) !important;
    font-family: 'Sora', sans-serif !important;
}

@keyframes fadeUp  { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
@keyframes fadeIn  { from{opacity:0} to{opacity:1} }
@keyframes slideRight { from{transform:scaleX(0)} to{transform:scaleX(1)} }

/* ── Page wrapper ── */
.page-wrap { padding: 32px 32px 48px; max-width: 1400px; animation: fadeIn .45s ease; }

/* ── Page header ── */
.page-header {
    display: flex; align-items: flex-start;
    justify-content: space-between; flex-wrap: wrap;
    gap: 16px; margin-bottom: 32px;
    animation: fadeUp .5s ease forwards;
}
.page-eyebrow {
    font-size: 0.7rem; font-weight: 600;
    letter-spacing: 0.18em; text-transform: uppercase;
    color: var(--red); margin-bottom: 5px;
    display: flex; align-items: center; gap: 8px;
}
.page-eyebrow::before {
    content: ''; display: inline-block;
    width: 20px; height: 1px; background: var(--red);
}
.page-title {
    font-family: 'Playfair Display', serif;
    font-size: 2rem; font-weight: 700;
    color: var(--text); margin: 0; line-height: 1.1;
}

/* ── Search bar ── */
.search-wrap {
    position: relative;
    min-width: 240px;
    flex-shrink: 0;
    animation: fadeUp .5s ease forwards .04s;
    opacity: 0;
}
.search-wrap i {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    color: var(--text-3); font-size: 0.9rem; pointer-events: none;
}
.search-input {
    width: 100%;
    padding: 9px 14px 9px 36px;
    border-radius: 9px;
    border: 1px solid var(--border-2);
    background: var(--surface);
    font-family: 'Sora', sans-serif;
    font-size: 0.82rem;
    color: var(--text);
    transition: border-color .2s, box-shadow .2s;
    outline: none;
}
.search-input::placeholder { color: var(--text-3); }
.search-input:focus {
    border-color: var(--red);
    box-shadow: 0 0 0 3px var(--red-glow);
}
.no-results-row td {
    text-align: center; padding: 40px 20px;
    color: var(--text-3); font-size: 0.85rem;
}

/* ── Status tabs ── */
.res-tabs {
    display: flex; gap: 4px; flex-wrap: wrap;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px 12px 0 0;
    padding: 10px 10px 0;
    animation: fadeUp .5s ease forwards .08s;
    opacity: 0;
}
.res-tab {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 18px;
    border-radius: 8px 8px 0 0;
    font-size: 0.82rem; font-weight: 600;
    font-family: 'Sora', sans-serif;
    text-decoration: none;
    color: var(--text-2);
    border: 1px solid transparent;
    border-bottom: none;
    transition: all .15s;
    position: relative; bottom: -1px;
}
.res-tab:hover { color: var(--text); background: var(--surface-2); }
.res-tab.active {
    color: var(--red);
    background: var(--surface-3);
    border-color: var(--border-2);
    border-bottom-color: var(--surface-3);
}
.res-tab .tab-count {
    font-size: 0.68rem; font-weight: 700;
    padding: 1px 7px; border-radius: 10px;
    background: var(--red-soft); color: var(--red);
}
.res-tab.active .tab-count { background: var(--red); color: #fff; }

/* ── Responsive Grid Logic ── */
.res-table-container { 
    background: var(--surface);
    border: 1px solid var(--border-2);
    border-radius: 0 12px 12px 12px;
    overflow: hidden;
    animation: fadeUp .5s ease forwards .15s;
    opacity: 0;
}

/* ── Table ── */
.res-table { width: 100%; border-collapse: collapse; display: table; }
.card-view { display: none; }

.res-table thead tr { border-bottom: 1px solid var(--border-2); }
.res-table thead th {
    font-size: 0.68rem; font-weight: 700;
    letter-spacing: 0.12em; text-transform: uppercase;
    color: var(--text-3); padding: 14px 20px;
    text-align: left; background: var(--surface-2);
    white-space: nowrap;
}
.res-table thead th.center { text-align: center; }
.res-table tbody tr {
    border-bottom: 1px solid rgba(0,0,0,0.05);
    transition: background .15s;
    animation: fadeUp .4s ease forwards;
    opacity: 0;
}
.res-table tbody tr:last-child { border-bottom: none; }
.res-table tbody tr:hover { background: rgba(0,0,0,0.025); }
.res-table tbody tr:nth-child(1) { animation-delay:.18s }
.res-table tbody tr:nth-child(2) { animation-delay:.23s }
.res-table tbody tr:nth-child(3) { animation-delay:.28s }
.res-table tbody tr:nth-child(4) { animation-delay:.33s }
.res-table tbody tr:nth-child(5) { animation-delay:.38s }
.res-table tbody tr:nth-child(6) { animation-delay:.43s }
.res-table tbody tr:nth-child(7) { animation-delay:.48s }
.res-table tbody tr:nth-child(8) { animation-delay:.53s }

.res-table td {
    padding: 16px 20px; font-size: 0.84rem;
    color: var(--text-2); vertical-align: top;
}
.td-no {
    font-family: 'Playfair Display', serif;
    font-size: 1rem; font-weight: 700;
    color: var(--red) !important;
    white-space: nowrap;
}
.td-name { font-weight: 600; color: var(--text) !important; }
.td-sub  { font-size: 0.75rem; color: var(--text-3) !important; margin-top: 2px; }
.td-badge {
    display: inline-block; padding: 2px 9px;
    border-radius: 20px; font-size: 0.7rem; font-weight: 600;
    background: var(--surface-3); color: var(--text-2);
    margin-top: 4px;
}
.td-items { font-size: 0.78rem; color: var(--text-3) !important; line-height: 1.6; }

/* ── Status badge ── */
.status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px; border-radius: 20px;
    font-size: 0.72rem; font-weight: 600;
}
.status-badge::before {
    content: ''; width: 5px; height: 5px;
    border-radius: 50%; background: currentColor; flex-shrink: 0;
}
.status-Pending   { background: rgba(180,83,9,.10);   color: #92400e; }
.status-Approved  { background: rgba(29,78,216,.10);  color: #1e40af; }
.status-Ongoing   { background: rgba(15,118,110,.10); color: #065f46; }
.status-Completed { background: rgba(4,120,87,.10);   color: #064e3b; }
.status-Denied    { background: rgba(192,57,43,.10);  color: #7f1d1d; }

/* ── Action buttons ── */
.act-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 5px;
    padding: 7px 13px; border-radius: 7px;
    font-size: 0.76rem; font-weight: 600;
    font-family: 'Sora', sans-serif;
    border: 1px solid transparent;
    cursor: pointer; text-decoration: none;
    transition: all .15s; white-space: nowrap;
    width: 100%; margin-bottom: 5px;
}
.act-btn:last-child { margin-bottom: 0; }
.act-btn-review  { background: #064e3b; color: #fff; border-color: #064e3b; }
.act-btn-review:hover  { background: #065f46; color: #fff; }
.act-btn-view    { background: var(--surface-2); color: var(--text); border-color: var(--border-2); }
.act-btn-view:hover    { background: var(--surface-3); color: var(--text); }
.act-btn-ongoing { background: #1e3a8a; color: #fff; border-color: #1e3a8a; }
.act-btn-ongoing:hover { background: #1e40af; color: #fff; }
.act-btn-complete { background: #065f46; color: #fff; border-color: #065f46; }
.act-btn-complete:hover { background: #047857; color: #fff; }
.act-btn-deny    { background: transparent; color: var(--red); border-color: rgba(192,57,43,.3); }
.act-btn-deny:hover    { background: var(--red-soft); color: var(--red); }
.act-btn-closed  { background: var(--surface-2); color: var(--text-3); border-color: var(--border); cursor: not-allowed; }
.act-col { min-width: 130px; }

/* ── Empty state ── */
.empty-state {
    text-align: center; padding: 64px 24px;
    color: var(--text-3);
}
.empty-state i { font-size: 2.5rem; display: block; margin-bottom: 12px; opacity: .35; }
.empty-state p { font-size: 0.88rem; margin: 0; }

/* ── Modal ── */
.modal-content {
    border: none !important;
    border-radius: 16px !important;
    overflow: hidden;
    font-family: 'Sora', sans-serif;
    background: var(--surface) !important;
    color: var(--text) !important;
    box-shadow: 0 24px 64px rgba(0,0,0,0.14) !important;
}
.modal-header-custom {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 12px;
}
.modal-header-icon {
    width: 36px; height: 36px;
    border-radius: 9px; background: var(--red-soft);
    display: flex; align-items: center; justify-content: center;
    color: var(--red); font-size: 1rem; flex-shrink: 0;
}
.modal-header-title { font-weight: 700; font-size: 1rem; color: var(--text); flex: 1; }
.modal-close-btn {
    background: var(--surface-2); border: 1px solid var(--border-2);
    border-radius: 7px; width: 30px; height: 30px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: var(--text-2); transition: all .15s;
    font-size: 0.9rem; flex-shrink: 0;
}
.modal-close-btn:hover { background: var(--red-soft); color: var(--red); border-color: rgba(192,57,43,.2); }
.modal-body { padding: 24px; background: var(--surface); }

/* Spinner */
.dash-spinner {
    display: flex; align-items: center; justify-content: center;
    padding: 48px; gap: 12px; color: var(--text-3); font-size: 0.85rem;
}
.spin-ring {
    width: 22px; height: 22px;
    border: 2px solid var(--border-2);
    border-top-color: var(--red);
    border-radius: 50%;
    animation: spin .7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

@media (max-width: 992px) {
    .page-wrap { padding: 20px 16px 40px; }
    .res-table { display: none !important; }
    .card-view { 
        display: grid; 
        grid-template-columns: 1fr; 
        gap: 16px; 
        padding: 16px; 
    }
    
    .res-card {
        background: var(--surface);
        border: 1px solid var(--border-2);
        border-radius: 12px;
        padding: 16px;
        animation: fadeUp .4s ease forwards;
    }
    
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        border-bottom: 1px solid var(--border);
        padding-bottom: 10px;
    }
    
    .card-body-row {
        display: flex;
        flex-direction: column;
        gap: 4px;
        margin-bottom: 12px;
    }
    
    .card-label {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--text-3);
        letter-spacing: 0.05em;
    }
    
    .card-value {
        font-size: 0.84rem;
        color: var(--text);
        font-weight: 600;
    }
}
</style>

<div class="page-wrap">
    <div class="page-header">
        <div>
            <div class="page-eyebrow">Admin Panel</div>
            <h1 class="page-title">Manage Requisitions</h1>
        </div>
        <div class="search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" id="searchInput" class="search-input" placeholder="Search by student name…" oninput="filterRows()" autocomplete="off">
        </div>
    </div>

    <!-- Tabs -->
    <div class="res-tabs">
        <?php
        $counts = [];
        foreach ($statuses as $st) {
            $cr = mysqli_query($con, "SELECT COUNT(*) as c FROM lab_reservations WHERE status='".mysqli_real_escape_string($con,$st)."'");
            $counts[$st] = mysqli_fetch_assoc($cr)['c'];
        }
        foreach ($statuses as $st):
        ?>
        <a class="res-tab <?= $status_tab == $st ? 'active' : '' ?>" href="?tab=<?= $st ?>">
            <?= $st ?> <span class="tab-count"><?= $counts[$st] ?></span>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- Main Container -->
    <div class="res-table-container">
        <?php
        $q   = "SELECT * FROM lab_reservations WHERE status = '$status_tab' ORDER BY id DESC";
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
                        <?php else: ?>
                            <button class="act-btn act-btn-view" onclick="viewReservation(<?= $id ?>, '<?= $row['reservation_no'] ?>', false)">
                                <i class="bi bi-eye"></i> View
                            </button>
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
</div>

<!-- Approval Modal -->
<div class="modal fade" id="approvalModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header-custom">
                <div class="modal-header-icon"><i class="bi bi-card-checklist" id="modalIcon"></i></div>
                <div class="modal-header-title" id="modalTitleText">Requisition Details</div>
                <div style="font-size:.82rem;color:var(--red);font-weight:700;margin-right:8px;" id="approveResNo"></div>
                <button class="modal-close-btn" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-body" id="approvalModalBody">
                <div class="dash-spinner"><div class="spin-ring"></div> Loading…</div>
            </div>
        </div>
    </div>
</div>

<script>
function filterRows() {
    const q = document.getElementById('searchInput').value.trim().toLowerCase();

    // --- Desktop table ---
    const tbody = document.getElementById('resTableBody');
    if (tbody) {
        const rows = tbody.querySelectorAll('tr:not(#noResultsRow)');
        let visible = 0;
        rows.forEach(tr => {
            const nameTd = tr.querySelector('.td-name');
            const name   = nameTd ? nameTd.textContent.toLowerCase() : '';
            const show   = !q || name.includes(q);
            tr.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        const noRow = document.getElementById('noResultsRow');
        if (noRow) noRow.style.display = (visible === 0 && q) ? '' : 'none';
    }

    // --- Mobile cards ---
    const cardView = document.getElementById('cardView');
    if (cardView) {
        const cards = cardView.querySelectorAll('.res-card');
        cards.forEach(card => {
            const nameEl = card.querySelector('.card-value');
            const name   = nameEl ? nameEl.textContent.toLowerCase() : '';
            card.style.display = (!q || name.includes(q)) ? '' : 'none';
        });
    }
}

function viewReservation(id, resNo, isPending) {
    document.getElementById('approveResNo').textContent = '#' + resNo;
    document.getElementById('modalTitleText').textContent = isPending ? 'Approve Requisition' : 'Requisition Details';
    document.getElementById('modalIcon').className = isPending ? 'bi bi-card-checklist' : 'bi bi-card-text';

    let modal = new bootstrap.Modal(document.getElementById('approvalModal'));
    modal.show();
    document.getElementById('approvalModalBody').innerHTML = '<div class="dash-spinner"><div class="spin-ring"></div> Loading…</div>';

    fetch(`../ajax/get_approval_items.php?id=${id}`)
        .then(res => res.text())
        .then(html => { document.getElementById('approvalModalBody').innerHTML = html; });
}

function submitApproval(e) {
    e.preventDefault();
    let formData = new FormData(document.getElementById('approvalForm'));
    fetch('../ajax/approve_reservation.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({ icon:'success', title:'Approved', text: data.message, toast:true, position:'top-end', timer:3000, showConfirmButton:false })
                    .then(() => location.reload());
            } else alert('Error: ' + data.message);
        });
}

function updateStatus(id, newStatus) {
    const labels = { Ongoing:'Set as Ongoing?', Completed:'Mark as Completed?', Denied:'Deny this requisition?' };
    Swal.fire({
        title: labels[newStatus] || `Mark as ${newStatus}?`,
        icon: newStatus === 'Denied' ? 'warning' : 'question',
        showCancelButton: true,
        confirmButtonColor: '#C0392B',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, confirm',
        cancelButtonText: 'Cancel',
        customClass: { popup: 'swal-custom' }
    }).then(result => {
        if (result.isConfirmed) {
            let fd = new FormData();
            fd.append('id', id);
            fd.append('status', newStatus);
            fetch('../ajax/update_reservation_status.php', { method:'POST', body: fd })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') location.reload();
                    else alert(data.message);
                });
        }
    });
}
</script>


<?php require('footer.php'); ?>