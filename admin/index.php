<?php
require('header.php');

$today = date('Y-m-d');
$stats = ['pending' => 0, 'approved' => 0, 'ongoing' => 0, 'completed_today' => 0];

$res1 = mysqli_query($con, "SELECT status, reservation_date, COUNT(*) as cnt FROM lab_reservations GROUP BY status, reservation_date");
while ($r = mysqli_fetch_assoc($res1)) {
    $s = strtolower($r['status']);
    if ($s == 'pending')
        $stats['pending'] += $r['cnt'];
    if ($s == 'approved')
        $stats['approved'] += $r['cnt'];
    if ($s == 'ongoing')
        $stats['ongoing'] += $r['cnt'];
    if ($s == 'completed' && $r['reservation_date'] == $today)
        $stats['completed_today'] += $r['cnt'];
}

$recent_res = mysqli_query($con, "SELECT * FROM lab_reservations ORDER BY created_at DESC LIMIT 8");
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap');

    :root {
        --red: #C0392B;
        --red-soft: rgba(192, 57, 43, 0.09);
        --red-glow: rgba(192, 57, 43, 0.18);
        --amber: #b45309;
        --blue: #1d4ed8;
        --blue-soft: rgba(29, 78, 216, 0.09);
        --teal: #0f766e;
        --teal-soft: rgba(15, 118, 110, 0.09);
        --emerald: #047857;
        --emer-soft: rgba(4, 120, 87, 0.09);
        --bg: #ffffffde;
        --surface: #faf9f7;
        --surface-2: #f0ede8;
        --surface-3: #e8e4de;
        --border: rgba(0, 0, 0, 0.07);
        --border-2: rgba(0, 0, 0, 0.13);
        --text: #1a1714;
        --text-2: rgba(26, 23, 20, 0.60);
        --text-3: rgba(26, 23, 20, 0.38);
    }

    /* ── Reset body for dirty-white theme ── */
    body,
    .main-content,
    #main-content {
        background: var(--bg) !important;
        color: var(--text) !important;
        font-family: 'Sora', sans-serif !important;
    }

    /* ── Keyframes ── */
    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(24px)
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

    @keyframes countUp {
        from {
            opacity: 0;
            transform: translateY(8px)
        }

        to {
            opacity: 1;
            transform: translateY(0)
        }
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1
        }

        50% {
            opacity: 0.4
        }
    }

    @keyframes shimmer {
        0% {
            background-position: -600px 0;
        }

        100% {
            background-position: 600px 0;
        }
    }

    @keyframes barGrow {
        from {
            width: 0
        }

        to {
            width: var(--w)
        }
    }

    @keyframes dotPing {
        0% {
            transform: scale(1);
            opacity: 0.8;
        }

        100% {
            transform: scale(2.2);
            opacity: 0;
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
    .dash-wrap {
        padding: 32px 32px 48px;
        max-width: 1400px;
        animation: fadeIn 0.5s ease forwards;
    }

    /* ── Header row ── */
    .dash-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 36px;
        gap: 16px;
        flex-wrap: wrap;
        animation: fadeUp 0.5s ease forwards;
    }

    .dash-header-left {}

    .dash-greeting {
        font-size: 0.72rem;
        font-weight: 500;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: var(--red);
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .dash-greeting::before {
        content: '';
        display: inline-block;
        width: 24px;
        height: 1px;
        background: var(--red);
    }

    .dash-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--text);
        margin: 0;
        line-height: 1.1;
    }

    .dash-date {
        font-size: 0.82rem;
        color: var(--text-3);
        margin-top: 6px;
        font-weight: 300;
    }

    .dash-header-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-dash {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 0.83rem;
        font-weight: 600;
        font-family: 'Sora', sans-serif;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.18s;
    }

    .btn-dash-primary {
        background: var(--red);
        color: #fff;
        box-shadow: 0 4px 16px var(--red-glow);
    }

    .btn-dash-primary:hover {
        background: #a93226;
        transform: translateY(-1px);
        box-shadow: 0 8px 24px var(--red-glow);
        color: #fff;
    }

    .btn-dash-ghost {
        background: var(--surface-2);
        color: var(--text-2);
        border: 1px solid var(--border-2);
    }

    .btn-dash-ghost:hover {
        background: var(--surface-3);
        color: var(--text);
        border-color: rgba(0, 0, 0, 0.22);
    }

    /* ── Live indicator ── */
    .live-dot {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: 0.75rem;
        color: var(--text-3);
        padding: 6px 14px;
        background: var(--surface-2);
        border: 1px solid var(--border);
        border-radius: 20px;
    }

    .live-dot::before {
        content: '';
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #22c55e;
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4);
        animation: pulse 2s infinite;
    }

    /* ── Stat cards ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: var(--surface-2);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 24px 24px 20px;
        position: relative;
        overflow: hidden;
        transition: border-color 0.2s, transform 0.2s, box-shadow 0.2s;
        opacity: 0;
        animation: fadeUp 0.6s ease forwards;
    }

    .stat-card:nth-child(1) {
        animation-delay: 0.05s;
    }

    .stat-card:nth-child(2) {
        animation-delay: 0.12s;
    }

    .stat-card:nth-child(3) {
        animation-delay: 0.19s;
    }

    .stat-card:nth-child(4) {
        animation-delay: 0.26s;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--accent-color);
        transform-origin: left;
        animation: slideRight 0.6s ease forwards;
        animation-delay: inherit;
    }

    .stat-card:hover {
        border-color: var(--accent-color);
        transform: translateY(-3px);
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.3);
    }

    /* Glow orb behind each card */
    .stat-card::after {
        content: '';
        position: absolute;
        bottom: -30px;
        right: -30px;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: var(--accent-color);
        opacity: 0.07;
        pointer-events: none;
        transition: opacity 0.2s;
    }

    .stat-card:hover::after {
        opacity: 0.12;
    }

    .stat-card[data-type="pending"] {
        --accent-color: #d97706;
    }

    .stat-card[data-type="approved"] {
        --accent-color: #2563eb;
    }

    .stat-card[data-type="ongoing"] {
        --accent-color: #0d9488;
    }

    .stat-card[data-type="completed"] {
        --accent-color: #059669;
    }

    .stat-icon-wrap {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: var(--accent-color);
        opacity: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 18px;
        font-size: 1.1rem;
        color: #fff;
        animation: fadeIn 0.4s ease forwards;
        animation-delay: calc(var(--delay, 0s) + 0.3s);
        position: relative;
    }

    .stat-icon-wrap::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 10px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.15), transparent);
    }

    .stat-card:nth-child(1) .stat-icon-wrap {
        --delay: 0.05s;
    }

    .stat-card:nth-child(2) .stat-icon-wrap {
        --delay: 0.12s;
    }

    .stat-card:nth-child(3) .stat-icon-wrap {
        --delay: 0.19s;
    }

    .stat-card:nth-child(4) .stat-icon-wrap {
        --delay: 0.26s;
    }

    .stat-label {
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--text-3);
        margin-bottom: 8px;
    }

    .stat-number {
        font-family: 'Playfair Display', serif;
        font-size: 2.6rem;
        font-weight: 700;
        color: var(--text);
        line-height: 1;
        margin-bottom: 12px;
        opacity: 0;
        animation: countUp 0.5s ease forwards;
        animation-delay: calc(var(--delay, 0s) + 0.4s);
    }

    .stat-card:nth-child(1) .stat-number {
        --delay: 0.05s;
    }

    .stat-card:nth-child(2) .stat-number {
        --delay: 0.12s;
    }

    .stat-card:nth-child(3) .stat-number {
        --delay: 0.19s;
    }

    .stat-card:nth-child(4) .stat-number {
        --delay: 0.26s;
    }

    .stat-trend {
        font-size: 0.74rem;
        color: var(--text-3);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .stat-trend .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--accent-color);
    }

    /* ── Bottom grid ── */
    .bottom-grid {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 16px;
    }

    /* ── Panel base ── */
    .panel {
        background: var(--surface-2);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        opacity: 0;
        animation: fadeUp 0.6s ease forwards 0.35s;
    }

    .panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
    }

    .panel-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text);
    }

    .panel-title-icon {
        width: 30px;
        height: 30px;
        border-radius: 7px;
        background: var(--red-soft);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--red);
        font-size: 0.88rem;
    }

    .panel-link {
        font-size: 0.78rem;
        color: var(--red);
        text-decoration: none;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: gap 0.15s;
    }

    .panel-link:hover {
        gap: 8px;
        color: var(--red);
    }

    /* ── Table ── */
    .dash-table {
        width: 100%;
        border-collapse: collapse;
    }

    .dash-table thead tr {
        border-bottom: 1px solid var(--border);
    }

    .dash-table thead th {
        font-size: 0.68rem;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--text-3);
        padding: 12px 24px;
        text-align: left;
        background: var(--surface-2);
    }

    .dash-table tbody tr {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        transition: background 0.15s;
        opacity: 0;
        animation: fadeUp 0.4s ease forwards;
    }

    .dash-table tbody tr:last-child {
        border-bottom: none;
    }

    .dash-table tbody tr:hover {
        background: rgba(0, 0, 0, 0.03);
    }

    /* stagger rows */
    .dash-table tbody tr:nth-child(1) {
        animation-delay: 0.40s;
    }

    .dash-table tbody tr:nth-child(2) {
        animation-delay: 0.46s;
    }

    .dash-table tbody tr:nth-child(3) {
        animation-delay: 0.52s;
    }

    .dash-table tbody tr:nth-child(4) {
        animation-delay: 0.58s;
    }

    .dash-table tbody tr:nth-child(5) {
        animation-delay: 0.64s;
    }

    .dash-table tbody tr:nth-child(6) {
        animation-delay: 0.70s;
    }

    .dash-table tbody tr:nth-child(7) {
        animation-delay: 0.76s;
    }

    .dash-table tbody tr:nth-child(8) {
        animation-delay: 0.82s;
    }

    .dash-table td {
        padding: 14px 24px;
        font-size: 0.84rem;
        color: var(--text-2);
        vertical-align: middle;
    }

    .td-no {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        color: var(--red) !important;
        font-size: 0.95rem !important;
    }

    .td-name {
        color: var(--text) !important;
        font-weight: 500;
    }

    .td-date {
        color: var(--text-3) !important;
        font-size: 0.78rem !important;
    }

    /* Status badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.04em;
    }

    .status-badge::before {
        content: '';
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: currentColor;
        flex-shrink: 0;
    }

    .status-Pending, .status-pending {
        background: rgba(180, 83, 9, 0.10);
        color: #92400e;
    }

    .status-Approved, .status-approved {
        background: rgba(29, 78, 216, 0.10);
        color: #1e40af;
    }

    .status-Ongoing, .status-ongoing {
        background: rgba(15, 118, 110, 0.10);
        color: #065f46;
    }

    .status-Completed, .status-completed {
        background: rgba(4, 120, 87, 0.10);
        color: #064e3b;
    }

    .status-Rejected, .status-rejected, .status-Denied, .status-denied {
        background: rgba(192, 57, 43, 0.10);
        color: #7f1d1d;
    }

    /* ── Quick Actions panel ── */
    .quick-panel {
        background: var(--surface-2);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        opacity: 0;
        animation: fadeUp 0.6s ease forwards 0.45s;
        display: flex;
        flex-direction: column;
    }

    .quick-panel .panel-header {
        flex-shrink: 0;
    }

    .quick-actions-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        flex: 1;
    }

    .quick-btn {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 16px;
        border-radius: 10px;
        background: var(--surface-2);
        border: 1px solid var(--border-2);
        text-decoration: none;
        transition: all 0.18s;
        color: var(--text);
        position: relative;
        overflow: hidden;
    }

    .quick-btn::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, transparent 60%, rgba(255, 255, 255, 0.03));
    }

    .quick-btn:hover {
        background: var(--surface-3);
        border-color: var(--border-2);
        transform: translateX(4px);
        color: var(--text);
    }

    .quick-btn:hover .quick-btn-arrow {
        opacity: 1;
        transform: translateX(0);
    }

    .quick-btn-icon {
        width: 38px;
        height: 38px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .quick-btn-icon.red {
        background: var(--red-soft);
        color: var(--red);
    }

    .quick-btn-icon.blue {
        background: var(--blue-soft);
        color: #60a5fa;
    }

    .quick-btn-icon.teal {
        background: var(--teal-soft);
        color: #2dd4bf;
    }

    .quick-btn-icon.emer {
        background: var(--emer-soft);
        color: #34d399;
    }

    .quick-btn-text {
        flex: 1;
    }

    .quick-btn-label {
        font-size: 0.85rem;
        font-weight: 600;
        display: block;
    }

    .quick-btn-desc {
        font-size: 0.74rem;
        color: var(--text-3);
        margin-top: 1px;
        display: block;
    }

    .quick-btn-arrow {
        color: var(--text-3);
        font-size: 0.8rem;
        opacity: 0;
        transform: translateX(-4px);
        transition: all 0.18s;
    }

    /* ── Summary bar inside quick panel ── */
    .summary-bar {
        margin: 0 20px 20px;
        background: var(--surface-2);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 16px;
    }

    .summary-bar-title {
        font-size: 0.68rem;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--text-3);
        font-weight: 600;
        margin-bottom: 12px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        font-size: 0.78rem;
    }

    .summary-row:last-child {
        margin-bottom: 0;
    }

    .summary-row-label {
        color: var(--text-2);
    }

    .summary-row-bar-wrap {
        flex: 1;
        height: 4px;
        background: rgba(0, 0, 0, 0.08);
        border-radius: 4px;
        margin: 0 12px;
        overflow: hidden;
    }

    .summary-row-bar {
        height: 100%;
        border-radius: 4px;
        background: var(--bar-color, var(--red));
        width: var(--w, 0%);
        animation: barGrow 1s ease forwards 0.6s;
        transform-origin: left;
    }

    .summary-row-val {
        color: var(--text-3);
        font-weight: 600;
        min-width: 24px;
        text-align: right;
    }

    /* ── Responsive ── */
    @media (max-width: 1100px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .bottom-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .dash-wrap {
            padding: 20px 16px 40px;
        }

        .dash-title {
            font-size: 1.6rem;
        }
    }
</style>

<div class="dash-wrap">

    <!-- ── Header ── -->
    <div class="dash-header">
        <div class="dash-header-left">
            <div class="dash-greeting">Admin Panel</div>
            <h1 class="dash-title">Dashboard</h1>
            <div class="dash-date" id="dash-date"></div>
        </div>
        <div class="dash-header-right">
            <div class="live-dot">Live</div>
            <a href="reservations.php" class="btn-dash btn-dash-ghost">
                <i class="bi bi-list-ul"></i> All Requisitions
            </a>
            <a href="inventory.php" class="btn-dash btn-dash-primary">
                <i class="bi bi-plus-lg"></i> Add Item
            </a>
        </div>
    </div>

    <!-- ── Stat Cards ── -->
    <div class="stats-grid">
        <div class="stat-card" data-type="pending">
            <div class="stat-icon-wrap" style="background:#d97706;">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-label">Pending</div>
            <div class="stat-number" data-target="<?= $stats['pending'] ?>"><?= $stats['pending'] ?></div>
            <div class="stat-trend"><span class="dot"></span> Awaiting review</div>
        </div>

        <div class="stat-card" data-type="approved">
            <div class="stat-icon-wrap" style="background:#2563eb;">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div class="stat-label">Approved</div>
            <div class="stat-number" data-target="<?= $stats['approved'] ?>"><?= $stats['approved'] ?></div>
            <div class="stat-trend"><span class="dot"></span> Ready to use</div>
        </div>

        <div class="stat-card" data-type="ongoing">
            <div class="stat-icon-wrap" style="background:#0d9488;">
                <i class="bi bi-arrow-repeat"></i>
            </div>
            <div class="stat-label">Ongoing</div>
            <div class="stat-number" data-target="<?= $stats['ongoing'] ?>"><?= $stats['ongoing'] ?></div>
            <div class="stat-trend"><span class="dot"></span> Currently active</div>
        </div>

        <div class="stat-card" data-type="completed">
            <div class="stat-icon-wrap" style="background:#059669;">
                <i class="bi bi-check2-all"></i>
            </div>
            <div class="stat-label">Completed Today</div>
            <div class="stat-number" data-target="<?= $stats['completed_today'] ?>"><?= $stats['completed_today'] ?>
            </div>
            <div class="stat-trend"><span class="dot"></span> As of today</div>
        </div>
    </div>

    <!-- ── Bottom grid ── -->
    <div class="bottom-grid">

        <!-- Recent reservations table -->
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">
                    <div class="panel-title-icon"><i class="bi bi-clock-history"></i></div>
                    Recent Requisitions
                </div>
                <a href="reservations.php" class="panel-link">
                    View all <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div style="overflow-x:auto;">
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Student</th>
                            <th>Date &amp; Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rows = [];
                        while ($row = mysqli_fetch_assoc($recent_res)) {
                            $rows[] = $row;
                        }
                        if (count($rows) === 0): ?>
                            <tr>
                                <td colspan="4"
                                    style="text-align:center;padding:40px;color:var(--text-3);font-size:0.85rem;">No
                                    requisitions yet.</td>
                            </tr>
                        <?php else:
                            foreach ($rows as $row): ?>
                                <tr>
                                    <td class="td-no">#<?= htmlspecialchars($row['reservation_no']) ?></td>
                                    <td class="td-name"><?= htmlspecialchars($row['user_name']) ?></td>
                                    <td class="td-date">
                                        <?= date('M d, Y', strtotime($row['reservation_date'])) ?><br>
                                        <span
                                            style="color:var(--text-3)"><?= htmlspecialchars($row['reservation_time']) ?></span>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?= htmlspecialchars($row['status']) ?>">
                                            <?= htmlspecialchars($row['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right column -->
        <div style="display:flex;flex-direction:column;gap:16px;">

            <!-- Status summary mini chart -->
            <div class="quick-panel" style="animation-delay:0.4s;">
                <div class="panel-header">
                    <div class="panel-title">
                        <div class="panel-title-icon"><i class="bi bi-bar-chart"></i></div>
                        Status Overview
                    </div>
                </div>
                <?php
                $total = max(1, $stats['pending'] + $stats['approved'] + $stats['ongoing'] + $stats['completed_today']);
                $bars = [
                    ['label' => 'Pending', 'val' => $stats['pending'], 'color' => '#f59e0b'],
                    ['label' => 'Approved', 'val' => $stats['approved'], 'color' => '#60a5fa'],
                    ['label' => 'Ongoing', 'val' => $stats['ongoing'], 'color' => '#2dd4bf'],
                    ['label' => 'Completed', 'val' => $stats['completed_today'], 'color' => '#34d399'],
                ];
                ?>
                <div class="summary-bar" style="margin:20px 20px 0;">
                    <div class="summary-bar-title">Today's Breakdown</div>
                    <?php foreach ($bars as $b):
                        $pct = round($b['val'] / $total * 100); ?>
                        <div class="summary-row">
                            <span class="summary-row-label"><?= $b['label'] ?></span>
                            <div class="summary-row-bar-wrap">
                                <div class="summary-row-bar" style="--w:<?= $pct ?>%;--bar-color:<?= $b['color'] ?>;"></div>
                            </div>
                            <span class="summary-row-val"><?= $b['val'] ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions-body">
                    <a href="inventory.php" class="quick-btn">
                        <div class="quick-btn-icon red"><i class="bi bi-plus-circle"></i></div>
                        <div class="quick-btn-text">
                            <span class="quick-btn-label">Add New Item</span>
                            <span class="quick-btn-desc">Update inventory</span>
                        </div>
                        <i class="bi bi-arrow-right quick-btn-arrow"></i>
                    </a>
                    <a href="reservations.php" class="quick-btn">
                        <div class="quick-btn-icon blue"><i class="bi bi-calendar-week"></i></div>
                        <div class="quick-btn-text">
                            <span class="quick-btn-label">Manage Schedule</span>
                            <span class="quick-btn-desc">Review reservations</span>
                        </div>
                        <i class="bi bi-arrow-right quick-btn-arrow"></i>
                    </a>
                    <a href="reservations.php?status=Pending" class="quick-btn">
                        <div class="quick-btn-icon teal"><i class="bi bi-hourglass"></i></div>
                        <div class="quick-btn-text">
                            <span class="quick-btn-label">Pending Requests</span>
                            <span class="quick-btn-desc"><?= $stats['pending'] ?> awaiting action</span>
                        </div>
                        <i class="bi bi-arrow-right quick-btn-arrow"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>

</div><!-- /.dash-wrap -->

<script>
    // ── Live date/time ─────────────────────────────────────
    function updateDate() {
        const el = document.getElementById('dash-date');
        if (!el) return;
        const now = new Date();
        el.textContent = now.toLocaleDateString('en-US', {
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
        }) + ' · ' + now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
    }
    updateDate();
    setInterval(updateDate, 10000);

    // ── Animated count-up for stat numbers ────────────────
    document.querySelectorAll('.stat-number[data-target]').forEach(el => {
        const target = parseInt(el.dataset.target, 10);
        if (isNaN(target) || target === 0) return;
        let start = null;
        const duration = 900;
        function step(ts) {
            if (!start) start = ts;
            const progress = Math.min((ts - start) / duration, 1);
            const ease = 1 - Math.pow(1 - progress, 3); // cubic ease-out
            el.textContent = Math.round(ease * target);
            if (progress < 1) requestAnimationFrame(step);
        }
        setTimeout(() => requestAnimationFrame(step), 500);
    });
</script>

<?php // Note: closing tags handled by header.php's paired footer ?>