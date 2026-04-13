<?php
require('inc/header.php');

$cat_res = mysqli_query($con, "SELECT * FROM lab_categories");
$categories = [];
while ($row = mysqli_fetch_assoc($cat_res)) {
    $categories[] = $row;
}
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Serif+Display&display=swap');

    :root {
        --red: #C0392B;
        --red-light: #FDECEA;
        --dark: #1a1a1a;
        --muted: #6b7280;
        --border: #e5e7eb;
        --bg: #f9f9f7;
        --white: #ffffff;
        --radius: 12px;
        --shadow: 0 2px 12px rgba(0, 0, 0, 0.07);
    }

    body {
        background: var(--bg);
        font-family: 'DM Sans', sans-serif;
        color: var(--dark);
    }

    /* ── Page header ── */
    .reserve-header {
        text-align: center;
        padding: 48px 0 32px;
    }

    .reserve-header h1 {
        font-family: 'DM Serif Display', serif;
        font-size: 2.4rem;
        color: var(--dark);
        margin-bottom: 6px;
        font-weight: 400;
    }

    .reserve-header p {
        color: var(--muted);
        font-size: 0.95rem;
        font-weight: 300;
    }

    /* ── Two-column layout ── */
    .reserve-layout {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 24px;
        max-width: 1180px;
        margin: 0 auto 60px;
        padding: 0 24px;
        align-items: start;
    }

    /* ── Cards ── */
    .r-card {
        background: var(--white);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .r-card:last-child {
        margin-bottom: 0;
    }

    .r-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .r-card-header .icon {
        width: 32px;
        height: 32px;
        background: var(--red-light);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--red);
        font-size: 15px;
        flex-shrink: 0;
    }

    .r-card-header h2 {
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
        color: var(--dark);
    }

    .r-card-body {
        padding: 24px;
    }

    /* ── Form fields ── */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .field label {
        font-size: 0.82rem;
        font-weight: 500;
        color: var(--muted);
        letter-spacing: 0.02em;
        text-transform: uppercase;
    }

    .field label .req {
        color: var(--red);
        margin-left: 2px;
    }

    .field input,
    .field select {
        padding: 10px 14px;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-size: 0.93rem;
        font-family: 'DM Sans', sans-serif;
        color: var(--dark);
        background: #fafafa;
        transition: border-color .15s, box-shadow .15s, background .15s;
        outline: none;
        width: 100%;
    }

    .field input:focus,
    .field select:focus {
        border-color: var(--red);
        background: var(--white);
        box-shadow: 0 0 0 3px rgba(192, 57, 43, .08);
    }

    .field input::placeholder {
        color: #b0b7c3;
    }

    /* ── Category tabs ── */
    .cat-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 16px 24px 0;
    }

    .cat-tab {
        padding: 6px 16px;
        border-radius: 20px;
        border: 1.5px solid var(--border);
        background: transparent;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--muted);
        cursor: pointer;
        transition: all .15s;
        white-space: nowrap;
    }

    .cat-tab:hover {
        border-color: var(--red);
        color: var(--red);
    }

    .cat-tab.active {
        background: var(--red);
        border-color: var(--red);
        color: #fff;
    }

    /* ── Search bar ── */
    .items-search-wrap {
        position: relative;
        padding: 14px 24px 0;
    }

    .items-search-wrap .srch-icon {
        position: absolute;
        left: 38px;
        top: 50%;
        transform: translateY(10%);
        color: var(--muted);
        font-size: 0.88rem;
        pointer-events: none;
    }

    .items-search {
        width: 100%;
        padding: 9px 14px 9px 34px;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-size: 0.9rem;
        font-family: 'DM Sans', sans-serif;
        color: var(--dark);
        background: #fafafa;
        outline: none;
        transition: border-color .15s, box-shadow .15s, background .15s;
    }

    .items-search:focus {
        border-color: var(--red);
        background: var(--white);
        box-shadow: 0 0 0 3px rgba(192, 57, 43, .08);
    }

    .items-search::placeholder {
        color: #b0b7c3;
    }

    /* ── Scrollable items container ── */
    .items-scroll-area {
        height: 460px;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 16px 24px 20px;
        scrollbar-width: thin;
        scrollbar-color: #d1d5db transparent;
    }

    .items-scroll-area::-webkit-scrollbar {
        width: 5px;
    }

    .items-scroll-area::-webkit-scrollbar-track {
        background: transparent;
    }

    .items-scroll-area::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 10px;
    }

    .items-scroll-area::-webkit-scrollbar-thumb:hover {
        background: #b0b7c3;
    }

    /* ── Items grid ── */
    .items-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        min-height: 200px;
    }

    /* ── Spinner / empty ── */
    .items-loading {
        grid-column: 1/-1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px 0;
        color: var(--muted);
        font-size: 0.9rem;
        gap: 10px;
    }

    .spinner {
        width: 20px;
        height: 20px;
        border: 2px solid var(--border);
        border-top-color: var(--red);
        border-radius: 50%;
        animation: spin .7s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* ── Wrong-category message ── */
    .wrong-cat-msg {
        grid-column: 1/-1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 36px 20px;
        text-align: center;
        gap: 6px;
    }

    .wrong-cat-msg .wc-icon {
        font-size: 2rem;
        color: #d1d5db;
        margin-bottom: 4px;
    }

    .wrong-cat-msg .wc-title {
        font-size: 0.92rem;
        font-weight: 600;
        color: var(--dark);
    }

    .wrong-cat-msg .wc-hint {
        font-size: 0.82rem;
        color: var(--muted);
        line-height: 1.5;
        margin: 0;
    }

    .wrong-cats {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        justify-content: center;
        margin-top: 6px;
    }

    .wrong-cat-pill {
        padding: 4px 14px;
        border-radius: 20px;
        background: var(--red-light);
        color: var(--red);
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        border: none;
        font-family: 'DM Sans', sans-serif;
        transition: background .15s, color .15s;
    }

    .wrong-cat-pill:hover {
        background: var(--red);
        color: #fff;
    }

    /* ── Item cards ── */
    .item-card {
        border-radius: 10px;
        border: 1.5px solid var(--border);
        background: #fafafa;
        overflow: hidden;
        transition: border-color .15s, box-shadow .15s, transform .15s;
    }

    .item-card:hover:not(.out-of-stock) {
        border-color: var(--red);
        box-shadow: 0 4px 16px rgba(192, 57, 43, .10);
        transform: translateY(-2px);
    }

    .item-card.out-of-stock {
        opacity: 0.55;
    }

    .item-card img {
        width: 100%;
        height: 140px;
        object-fit: contain;
        display: block;
        background: #f7f6f4;
        padding: 8px;
    }

    .item-card-body {
        padding: 10px 12px 12px;
    }

    .item-name {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 6px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .item-avail {
        font-size: 0.75rem;
        color: var(--muted);
        margin-bottom: 8px;
    }

    .badge-avail {
        display: inline-block;
        padding: 1px 8px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 600;
        background: #e8f5e9;
        color: #2e7d32;
    }

    .badge-avail.zero {
        background: var(--red-light);
        color: var(--red);
    }

    .qty-row {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 8px;
    }

    .qty-row label {
        font-size: 0.75rem;
        color: var(--muted);
        font-weight: 500;
        white-space: nowrap;
    }

    .qty-row input {
        width: 56px;
        padding: 4px 8px;
        border: 1.5px solid var(--border);
        border-radius: 6px;
        font-size: 0.82rem;
        font-family: 'DM Sans', sans-serif;
        text-align: center;
    }

    .btn-add {
        width: 100%;
        padding: 6px 0;
        border-radius: 7px;
        border: 1.5px solid var(--red);
        background: transparent;
        color: var(--red);
        font-size: 0.8rem;
        font-weight: 600;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
        transition: background .15s, color .15s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    .btn-add:hover:not(:disabled) {
        background: var(--red);
        color: #fff;
    }

    .btn-add:disabled {
        border-color: var(--border);
        color: var(--muted);
        cursor: not-allowed;
    }

    .btn-add.added {
        background: var(--red);
        color: #fff;
    }

    /* ── Sidebar ── */
    .sidebar {
        position: sticky;
        top: 20px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    /* ── Cart ── */
    .cart-empty {
        text-align: center;
        padding: 32px 0;
        color: var(--muted);
        font-size: 0.88rem;
    }

    .cart-empty i {
        font-size: 2rem;
        display: block;
        margin-bottom: 8px;
        opacity: 0.3;
    }

    .cart-list-scroll {
        max-height: 300px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #d1d5db transparent;
    }

    .cart-list-scroll::-webkit-scrollbar {
        width: 4px;
    }

    .cart-list-scroll::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 10px;
    }

    .cart-item {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid var(--border);
        gap: 8px;
        animation: fadeSlideIn .2s ease;
    }

    @keyframes fadeSlideIn {
        from {
            opacity: 0;
            transform: translateY(-6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .cart-item-name {
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--dark);
        flex: 1;
        line-height: 1.3;
    }

    .cart-item-qty {
        font-size: 0.78rem;
        color: var(--muted);
        margin-top: 2px;
    }

    .cart-item-remove {
        background: none;
        border: none;
        color: #d1d5db;
        cursor: pointer;
        font-size: 1rem;
        padding: 0;
        line-height: 1;
        flex-shrink: 0;
        transition: color .15s;
    }

    .cart-item-remove:hover {
        color: var(--red);
    }

    /* ── Alert ── */
    .r-alert {
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 0.85rem;
        font-weight: 500;
        display: none;
    }

    .r-alert.error {
        background: var(--red-light);
        color: var(--red);
    }

    .r-alert.success {
        background: #e8f5e9;
        color: #2e7d32;
    }

    /* ── Submit button ── */
    .btn-submit {
        width: 100%;
        padding: 14px;
        background: var(--red);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 600;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: background .15s, transform .1s, box-shadow .15s;
        box-shadow: 0 4px 14px rgba(192, 57, 43, .25);
    }

    .btn-submit:hover {
        background: #a93226;
        box-shadow: 0 6px 20px rgba(192, 57, 43, .30);
        transform: translateY(-1px);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    /* ── Responsive ── */
    /* ── Sticky Mobile Submit ── */
    .mobile-submit-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: var(--white);
        box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.1);
        padding: 14px 20px;
        display: none;
        /* JS toggles */
        z-index: 999;
        border-top: 1px solid var(--border);
        animation: slideUp .3s ease;
    }

    @keyframes slideUp {
        from {
            transform: translateY(100%);
        }

        to {
            transform: translateY(0);
        }
    }

    /* ── Mobile Cart Drawer ── */
    #mobile-cart-drawer {
        position: fixed;
        bottom: -100%;
        left: 0;
        width: 100%;
        height: 75vh;
        background: var(--white);
        z-index: 1001;
        border-radius: 24px 24px 0 0;
        box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.15);
        transition: bottom 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        padding: 20px;
    }

    #mobile-cart-drawer.open {
        bottom: 0;
    }

    .drawer-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(2px);
        display: none;
        z-index: 1000;
    }

    .drawer-overlay.open {
        display: block;
    }

    .drawer-handle {
        width: 40px;
        height: 4px;
        background: #e2e8f0;
        border-radius: 10px;
        margin: 0 auto 15px;
    }

    .drawer-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 20px;
        color: var(--dark);
        border-bottom: 2px solid var(--border);
        padding-bottom: 10px;
    }

    #drawer-list {
        flex: 1;
        overflow-y: auto;
    }

    @media (max-width: 1024px) {
        .reserve-layout {
            grid-template-columns: 1fr;
            padding: 0 16px;
        }

        .sidebar {
            order: 2;
        }
    }

    @media (max-width: 600px) {
        .reserve-header {
            padding: 32px 16px 20px;
        }

        .reserve-header h1 {
            font-size: 1.8rem;
        }

        .r-card-body {
            padding: 16px;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .items-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .item-card img {
            height: 110px;
            padding: 6px;
        }

        .btn-submit {
            padding: 12px;
            font-size: 0.95rem;
        }

        .variant-modal-content {
            padding: 24px 20px;
            width: 92%;
        }

        .mobile-submit-bar.active {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
    }

    @media (max-width: 400px) {
        .items-grid {
            grid-template-columns: 1fr;
        }
    }

    /* ── Multi-size Modal ── */
    .variant-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        backdrop-filter: blur(4px);
    }

    .variant-modal-content {
        background: var(--white);
        border-radius: var(--radius);
        width: 100%;
        max-width: 500px;
        padding: 32px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
        position: relative;
        animation: modalIn .3s ease;
        max-height: 85vh;
        overflow-y: auto;
    }

    @keyframes modalIn {
        from {
            opacity: 0;
            transform: translateY(20px) scale(0.95);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .variant-modal-close {
        position: absolute;
        top: 20px;
        right: 20px;
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--muted);
    }

    .variant-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-top: 20px;
    }

    .variant-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        transition: all .15s;
    }

    .variant-item:hover {
        border-color: var(--red);
        background: var(--red-light);
    }

    .variant-info {
        flex: 1;
    }

    .variant-title {
        font-weight: 600;
        font-size: 0.9rem;
    }

    .variant-stock {
        font-size: 0.78rem;
        color: var(--muted);
    }

    .variant-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .variant-qty {
        width: 45px;
        padding: 4px;
        border: 1px solid var(--border);
        border-radius: 5px;
        text-align: center;
        font-size: 0.82rem;
    }
</style>


<!-- ════════════════════════════════════
     PAGE HEADER
════════════════════════════════════ -->
<div class="reserve-header">
    <h1>Lab Requisition</h1>
    <p>Fill in your details, pick your schedule, then select the tools you need.</p>
</div>

<div class="reserve-layout">

    <!-- ══ LEFT COLUMN ══ -->
    <div>

        <!-- Student Info -->
        <div class="r-card">
            <div class="r-card-header">
                <div class="icon"><i class="bi bi-person"></i></div>
                <h2>Student Information</h2>
            </div>
            <div class="r-card-body">
                <div class="form-row">
                    <div class="field">
                        <label>Full Name <span class="req">*</span></label>
                        <input type="text" id="req_name" oninput="saveFormData()" onblur="capitalizeInput(this); saveFormData()">
                    </div>
                    <div class="field">
                        <label>Email Address <span class="req">*</span></label>
                        <input type="email" id="req_email" oninput="saveFormData()">
                    </div>
                    <div class="field">
                        <label>Contact Number <span class="req">*</span></label>
                        <input type="text" id="req_contact" oninput="saveFormData()">
                    </div>
                    <div class="field">
                        <label>Course &amp; Section <span class="req">*</span></label>
                        <input type="text" id="req_course" oninput="saveFormData()" onblur="capitalizeInput(this); saveFormData()">
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule -->
        <div class="r-card">
            <div class="r-card-header">
                <div class="icon"><i class="bi bi-calendar3"></i></div>
                <h2>Schedule &amp; Activity Details</h2>
            </div>
            <div class="r-card-body">
                <div class="form-row">
                    <div class="field">
                        <label>Subject Code / Name <span class="req">*</span></label>
                        <input type="text" id="req_subject" oninput="saveFormData()" onblur="capitalizeInput(this); saveFormData()">
                    </div>
                    <div class="field">
                        <label>Station Setup <span class="req">*</span></label>
                        <input type="text" id="req_station" oninput="saveFormData()">
                    </div>
                    <div class="field">
                        <label>Batch No. <span class="req">*</span></label>
                        <input type="text" id="req_batch" oninput="saveFormData()">
                    </div>
                    <div class="field">
                        <label>Date <span class="req">*</span></label>
                        <input type="date" id="req_date" min="<?= date('Y-m-d') ?>" onchange="saveFormData()">
                    </div>
                    <div class="field">
                        <label>Time (7AM – 5PM) <span class="req">*</span></label>
                        <select id="req_time" onchange="saveFormData()">
                            <option value="" disabled selected>Select time slot</option>
                            <?php
                            $s = strtotime('07:00');
                            $e = strtotime('17:00');
                            while ($s <= $e) {
                                echo "<option value='" . date('h:i A', $s) . "'>" . date('h:i A', $s) . "</option>";
                                $s = strtotime('+30 minutes', $s);
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Select Items -->
        <div class="r-card">
            <div class="r-card-header">
                <div class="icon"><i class="bi bi-box-seam"></i></div>
                <h2>Select Items to Borrow</h2>
            </div>

            <!-- Category tabs -->
            <div class="cat-tabs">
                <?php $first = true;
                foreach ($categories as $cat): ?>
                    <button class="cat-tab <?= $first ? 'active' : '' ?>" data-cat="<?= $cat['id'] ?>"
                        onclick="switchCategory(<?= $cat['id'] ?>, this)">
                        <?= htmlspecialchars($cat['name']) ?>
                    </button>
                    <?php $first = false; endforeach; ?>
            </div>

            <!-- Search bar -->
            <div class="items-search-wrap" style="padding: 14px 16px 0;">
                <i class="bi bi-search srch-icon" style="left: 30px;"></i>
                <input type="text" id="items-search" class="items-search" placeholder="Search items…"
                    oninput="handleSearch(this.value)">
            </div>

            <!-- Scrollable items area -->
            <div class="items-scroll-area" style="padding: 16px 16px 20px;">
                <?php $first = true;
                foreach ($categories as $cat): ?>
                    <div class="items-grid" id="items-pane-<?= $cat['id'] ?>" style="<?= $first ? '' : 'display:none' ?>">
                        <div class="items-loading">
                            <div class="spinner"></div> Loading items…
                        </div>
                    </div>
                    <?php $first = false; endforeach; ?>
            </div>

        </div><!-- /.r-card items -->

    </div><!-- /.left column -->


    <!-- ══ RIGHT COLUMN ══ -->
    <div class="sidebar">

        <!-- Cart -->
        <div class="r-card" style="margin-bottom:0">
            <div class="r-card-header">
                <div class="icon"><i class="bi bi-cart3"></i></div>
                <h2>Selected Items</h2>
            </div>
            <div class="r-card-body" style="padding-top:4px;">
                <div id="cart-empty" class="cart-empty">
                    <i class="bi bi-cart"></i>
                    No items added yet
                </div>
                <div class="cart-list-scroll">
                    <div id="cart-list"></div>
                </div>
                <div id="cart-count-row" style="display:none;padding-top:10px;border-top:1px solid var(--border);
                            font-size:0.82rem;color:var(--muted);text-align:right;">
                    <span id="cart-count">0</span> item(s) selected
                </div>
            </div>
        </div>

        <!-- Alert -->
        <div id="form-alert" class="r-alert"></div>

        <!-- Submit -->
        <button class="btn-submit" onclick="submitReservation()">
            <i class="bi bi-check2-circle"></i>
            Submit Requisition
        </button>

        <p style="font-size:0.75rem;color:var(--muted);text-align:center;margin:0;">
            Your request will be reviewed by the lab custodian before approval.
        </p>

    </div><!-- /.sidebar -->

</div><!-- /.reserve-layout -->

<!-- ── Mobile Sticky Submit ── -->
<div id="mobile-submit-bar" class="mobile-submit-bar">
    <div style="font-size:0.85rem; font-weight:600; color:var(--dark); cursor:pointer;" onclick="toggleCartDrawer()">
        <span id="mobile-cart-count">0</span> items selected <i class="bi bi-chevron-up ms-1"></i>
    </div>
    <button class="btn-submit" onclick="submitReservation()"
        style="width:auto; padding:8px 16px; font-size:0.85rem; margin:0;">
        Submit Now <i class="bi bi-chevron-right ms-1"></i>
    </button>
</div>

<!-- ── Mobile Cart Drawer ── -->
<div id="cart-drawer-overlay" class="drawer-overlay" onclick="toggleCartDrawer()"></div>
<div id="mobile-cart-drawer">
    <div class="drawer-handle" onclick="toggleCartDrawer()"></div>
    <div class="drawer-title">Review Selection</div>
    <div id="drawer-list"></div>
    <button class="btn-submit w-100 mt-4" onclick="submitReservation()">
        <i class="bi bi-send-check me-2"></i> Confirm My Requisition
    </button>
</div>

<!-- ── Variant Modal ── -->
<div id="variant-modal" class="variant-modal">
    <div class="variant-modal-content">
        <button class="variant-modal-close" onclick="closeVariantModal()">&times;</button>
        <h2 id="modal-base-name" style="font-family:'DM Serif Display',serif; font-size:1.5rem; margin-bottom:4px;">
            Select Size</h2>
        <p style="font-size:0.85rem; color:var(--muted); margin-bottom:0;">Choose the specific size/type you need for
            this tool.</p>
        <div id="variant-list" class="variant-list"></div>
    </div>
</div>


<script>
    // ── State ─────────────────────────────────────────────
    let labCart = {};   // { itemId: { name, qty, max, catId } }
    let loadedCats = {};   // { catId: true }
    let catData = {};   // { catId: [...items] }  — raw cache for search
    let catNames = {};   // { catId: 'Display Name' }
    let activeCatId = null;

    function enforceMax(input) {
        let val = parseInt(input.value);
        let max = parseInt(input.max);
        if (val > max) input.value = max;
        if (val < 1 && input.value !== "") input.value = 1;
    }

    // Seed category names from PHP
    const allCategories = <?= json_encode(array_map(fn($c) => ['id' => (int) $c['id'], 'name' => $c['name']], $categories)) ?>;
    allCategories.forEach(c => { catNames[c.id] = c.name; });

    // ── Init ──────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        <?php if (count($categories) > 0): ?>
            activeCatId = <?= (int) $categories[0]['id'] ?>;
            loadCategory(activeCatId);
        <?php endif; ?>
        loadFormData();
    });

    // ── Category switch ───────────────────────────────────
    function switchCategory(catId, btn) {
        document.querySelectorAll('[id^="items-pane-"]').forEach(p => p.style.display = 'none');
        document.querySelectorAll('.cat-tab').forEach(b => b.classList.remove('active'));
        document.getElementById('items-pane-' + catId).style.display = 'grid';
        btn.classList.add('active');
        activeCatId = catId;

        // Clear search input on tab switch
        document.getElementById('items-search').value = '';

        loadCategory(catId);
    }

    // ── Load items via AJAX ───────────────────────────────
    function loadCategory(catId) {
        if (loadedCats[catId]) return;

        fetch(`ajax/get_items_by_category.php?category_id=${catId}`)
            .then(r => r.json())
            .then(data => {
                catData[catId] = data;
                loadedCats[catId] = true;
                renderItems(catId, data);
            })
            .catch(() => {
                document.getElementById('items-pane-' + catId).innerHTML =
                    '<div class="items-loading" style="color:var(--red)"><i class="bi bi-exclamation-circle me-2"></i> Failed to load items.</div>';
            });
    }

    // ── Search ────────────────────────────────────────────
    function handleSearch(raw) {
        const query = raw.trim().toLowerCase();
        if (!activeCatId) return;
        if (!loadedCats[activeCatId]) return;   // not loaded yet

        const data = catData[activeCatId] || [];

        // Empty query — restore full list
        if (!query) { renderItems(activeCatId, data); return; }

        // Filter within active category
        const matched = data.filter(item =>
            item.item_name.toLowerCase().includes(query)
        );

        if (matched.length > 0) { renderItems(activeCatId, matched); return; }

        // Not found here — look in other already-loaded categories for suggestions
        const foundIn = allCategories.filter(cat =>
            cat.id != activeCatId &&
            (catData[cat.id] || []).some(item =>
                item.item_name.toLowerCase().includes(query)
            )
        );

        showWrongCatMessage(query, foundIn);
    }

    function showWrongCatMessage(query, foundIn) {
        const pane = document.getElementById('items-pane-' + activeCatId);
        const activeName = catNames[activeCatId] || 'this category';

        const pillsHtml = foundIn.length
            ? `<p class="wc-hint" style="margin-top:6px;">Try looking in:</p>
           <div class="wrong-cats">
             ${foundIn.map(c =>
                `<button class="wrong-cat-pill" onclick="jumpToCategory(${c.id})">${c.name}</button>`
            ).join('')}
           </div>`
            : `<p class="wc-hint" style="margin-top:4px;">Not found in any loaded category.<br>Try switching tabs to load more.</p>`;

        pane.innerHTML = `
        <div class="wrong-cat-msg">
            <i class="bi bi-search wc-icon"></i>
            <div class="wc-title">No results in &ldquo;${activeName}&rdquo;</div>
            <p class="wc-hint">&ldquo;${query}&rdquo; was not found here.</p>
            ${pillsHtml}
        </div>`;
    }

    // Jump to suggested category and re-run search
    function jumpToCategory(catId) {
        const btn = document.querySelector(`.cat-tab[data-cat="${catId}"]`);
        if (!btn) return;

        const query = document.getElementById('items-search').value;

        document.querySelectorAll('[id^="items-pane-"]').forEach(p => p.style.display = 'none');
        document.querySelectorAll('.cat-tab').forEach(b => b.classList.remove('active'));
        document.getElementById('items-pane-' + catId).style.display = 'grid';
        btn.classList.add('active');
        activeCatId = catId;

        if (loadedCats[catId]) {
            handleSearch(query);
        } else {
            loadCategory(catId);
            const wait = setInterval(() => {
                if (loadedCats[catId]) { clearInterval(wait); handleSearch(query); }
            }, 100);
        }
    }

    // ── Render item cards ─────────────────────────────────
    function renderItems(catId, data) {
        const pane = document.getElementById('items-pane-' + catId);
        if (!data || !data.length) {
            pane.innerHTML = '<div class="items-loading">No items in this category.</div>';
            return;
        }

        // ── Grouping Logic ──
        let groups = {}; // { baseName: [items] }
        data.forEach(item => {
            // Robust prefix/suffix stripping
            // 1. Strip "(...)"
            let name = item.item_name.split(' (')[0].trim();
            // 2. Strip leading dimensions like "2' ", "6oz ", etc
            // Ensure we only strip if it's NOT just the dimension itself
            let base = name;
            if (name.match(/^\d+['"]\s+/)) {
                base = name.replace(/^\d+['"]\s+/, '').trim();
            } else if (name.match(/^\d+oz\s+/)) {
                base = name.replace(/^\d+oz\s+/, '').trim();
            }

            if (!groups[base]) groups[base] = [];
            groups[base].push(item);
        });

        pane.innerHTML = Object.entries(groups).map(([base, items]) => {
            if (items.length > 1) {
                // Render a single card for the group
                const item = items[0]; // representative data
                const img = item.image_path ? `uploads/lab_items/${item.image_path}` : 'assets/images/placeholder.png';
                const totalAvail = items.reduce((sum, i) => sum + parseInt(i.available_quantity), 0);
                const allOos = totalAvail <= 0;

                return `
                <div class="item-card${allOos ? ' out-of-stock' : ''}">
                    <img src="${img}" alt="${base}" onerror="this.src='assets/images/placeholder.png'">
                    <div class="item-card-body">
                        <div class="item-name" title="${base}">${base}</div>
                        <div class="item-avail">
                            <span class="badge-avail${allOos ? ' zero' : ''}">${items.length} Variants</span>
                            <span style="font-size:.72rem;color:var(--muted);margin-left:4px;">${totalAvail} total available</span>
                        </div>
                        <button class="btn-add" onclick="openVariantModal('${base.replace(/'/g, "\\'").replace(/"/g, '&quot;')}', ${catId})">
                            <i class="bi bi-list-ul"></i> Select Size
                        </button>
                    </div>
                </div>`;
            } else {
                // Render normal individual card
                const item = items[0];
                const img = item.image_path ? `uploads/lab_items/${item.image_path}` : 'assets/images/placeholder.png';
                const avail = parseInt(item.available_quantity);
                const oos = avail <= 0;
                const inCart = labCart[item.id] != null;
                const safe = item.item_name.replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '&quot;');
                return `
                <div class="item-card${oos ? ' out-of-stock' : ''}" id="item-card-${item.id}">
                    <img src="${img}" alt="${item.item_name}" onerror="this.src='assets/images/placeholder.png'">
                    <div class="item-card-body">
                        <div class="item-name" title="${item.item_name}">${item.item_name}</div>
                        <div class="item-avail">
                            <span class="badge-avail${oos ? ' zero' : ''}">${avail} ${item.unit}</span>
                            <span style="font-size:.72rem;color:var(--muted);margin-left:4px;">available</span>
                        </div>
                        ${!oos ? `
                        <div class="qty-row">
                            <label>Qty</label>
                            <input type="number" id="qty-${item.id}" value="1" min="1" max="${avail}" oninput="enforceMax(this)">
                        </div>
                        <button class="btn-add${inCart ? ' added' : ''}" id="addbtn-${item.id}"
                            onclick="toggleCart(${item.id}, '${safe}', ${avail}, ${catId})">
                            <i class="bi ${inCart ? 'bi-cart-check' : 'bi-cart-plus'}"></i>
                            ${inCart ? 'Added' : 'Add to Cart'}
                        </button>` : `
                        <button class="btn-add" disabled>Out of stock</button>`}
                    </div>
                </div>`;
            }
        }).join('');
    }

    // ── Variant Modal Logic ───────────────────────────────
    function openVariantModal(baseName, catId) {
        const modal = document.getElementById('variant-modal');
        const title = document.getElementById('modal-base-name');
        const list = document.getElementById('variant-list');

        title.textContent = baseName;
        list.innerHTML = '';

        // Find variants in catData by matching the base name (post-stripping)
        const variants = (catData[catId] || []).filter(item => {
            let name = item.item_name.split(' (')[0].trim();
            let base = name.replace(/^\d+['"]\s+/, '').replace(/^\d+oz\s+/, '').trim();
            return base === baseName;
        });

        list.innerHTML = variants.map(v => {
            const avail = parseInt(v.available_quantity);
            const inCart = labCart[v.id] != null;
            const oos = avail <= 0;
            const safe = v.item_name.replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '&quot;');

            // For the title in the modal, show the part that makes it unique (the size/spec)
            let uniquePart = v.item_name;
            if (v.item_name.includes('(')) {
                uniquePart = v.item_name.split('(')[1].replace(')', '').trim();
            } else if (v.item_name.match(/^\d+['"]\s+/)) {
                uniquePart = v.item_name.match(/^\d+['"]/)[0];
            } else if (v.item_name.match(/^\d+oz\s+/)) {
                uniquePart = v.item_name.match(/^\d+oz/)[0];
            }

            return `
            <div class="variant-item">
                <div class="variant-info">
                    <div class="variant-title">${uniquePart}</div>
                    <div class="variant-stock">${avail} ${v.unit} available</div>
                </div>
                <div class="variant-actions">
                    ${!oos ? `
                        <input type="number" id="vqty-${v.id}" class="variant-qty" value="1" min="1" max="${avail}" oninput="enforceMax(this)">
                        <button class="btn-add btn-sm${inCart ? ' added' : ''}" style="width: auto; padding: 6px 14px;" 
                            id="vaddbtn-${v.id}" onclick="toggleVariantInCart(${v.id}, '${safe}', ${avail}, ${catId})">
                            ${inCart ? 'Added' : 'Add'}
                        </button>
                    ` : `<span class="text-danger small fw-bold">Out of stock</span>`}
                </div>
            </div>`;
        }).join('');

        modal.style.display = 'flex';
    }

    function closeVariantModal() {
        document.getElementById('variant-modal').style.display = 'none';
    }

    function toggleVariantInCart(id, name, max, catId) {
        const qInput = document.getElementById('vqty-' + id);
        const qty = qInput ? parseInt(qInput.value) : 1;

        if (labCart[id]) {
            delete labCart[id];
            const btn = document.getElementById('vaddbtn-' + id);
            if (btn) { btn.classList.remove('added'); btn.innerHTML = 'Add'; }
        } else {
            labCart[id] = { name, qty: Math.min(qty, max), max, catId };
            const btn = document.getElementById('vaddbtn-' + id);
            if (btn) { btn.classList.add('added'); btn.innerHTML = 'Added'; }
        }
        saveFormData();
        renderCart();
    }

    window.onclick = function (event) {
        const modal = document.getElementById('variant-modal');
        if (event.target == modal) { modal.style.display = 'none'; }
    }

    // ── Cart ──────────────────────────────────────────────
    function toggleCart(itemId, itemName, maxQty, catId) {
        if (labCart[itemId]) { removeFromCart(itemId, event); }
        else { addToCart(itemId, itemName, maxQty, catId); }
    }

    function addToCart(itemId, itemName, maxQty, catId) {
        const qtyInput = document.getElementById('qty-' + itemId);
        const qty = qtyInput ? Math.min(parseInt(qtyInput.value) || 1, maxQty) : 1;
        labCart[itemId] = { name: itemName, qty, max: maxQty, catId };

        const btn = document.getElementById('addbtn-' + itemId);
        if (btn) { btn.classList.add('added'); btn.innerHTML = '<i class="bi bi-cart-check"></i> Added'; }
        saveFormData();
        renderCart();
    }

    function removeFromCart(itemId, e = null) {
        if (e) { e.preventDefault(); e.stopPropagation(); }
        delete labCart[itemId];

        const btn = document.getElementById('addbtn-' + itemId);
        if (btn) { btn.classList.remove('added'); btn.innerHTML = '<i class="bi bi-cart-plus"></i> Add to Cart'; }

        const vbtn = document.getElementById('vaddbtn-' + itemId);
        if (vbtn) { vbtn.classList.remove('added'); vbtn.innerHTML = 'Add'; }

        saveFormData();
        renderCart();
    }

    function renderCart() {
        const keys = Object.keys(labCart);
        const empty = document.getElementById('cart-empty');
        const list = document.getElementById('cart-list');
        const count = document.getElementById('cart-count-row');

        if (!keys.length) {
            empty.style.display = 'block';
            list.innerHTML = '';
            count.style.display = 'none';
            document.getElementById('mobile-cart-count').textContent = '0';
            document.getElementById('drawer-list').innerHTML = '';
            document.getElementById('mobile-submit-bar').classList.remove('active');
            document.getElementById('mobile-cart-drawer').classList.remove('open');
            document.getElementById('cart-drawer-overlay').classList.remove('open');
            return;
        }

        empty.style.display = 'none';
        count.style.display = 'block';
        document.getElementById('cart-count').textContent = keys.length;
        document.getElementById('mobile-cart-count').textContent = keys.length;

        // Toggle mobile bar
        const mBar = document.getElementById('mobile-submit-bar');
        if (keys.length > 0) mBar.classList.add('active');
        else {
            mBar.classList.remove('active');
            document.getElementById('mobile-cart-drawer').classList.remove('open');
            document.getElementById('cart-drawer-overlay').classList.remove('open');
        }

        const itemsHtml = keys.map(id => `
        <div class="cart-item">
            <div>
                <div class="cart-item-name">${labCart[id].name}</div>
                <div class="cart-item-qty">Qty: ${labCart[id].qty}</div>
            </div>
            <button type="button" class="cart-item-remove" onclick="removeFromCart(${id}, event)" title="Remove">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>`).join('');

        list.innerHTML = itemsHtml;
        document.getElementById('drawer-list').innerHTML = itemsHtml;
    }

    function toggleCartDrawer() {
        const drawer = document.getElementById('mobile-cart-drawer');
        const overlay = document.getElementById('cart-drawer-overlay');
        drawer.classList.toggle('open');
        overlay.classList.toggle('open');
    }

    // ── Validate & submit ─────────────────────────────────
    function showAlert(msg, type) {
        const el = document.getElementById('form-alert');
        el.textContent = msg;
        el.className = 'r-alert ' + type;
        el.style.display = 'block';
        setTimeout(() => { el.style.display = 'none'; }, 4500);
    }

    function submitReservation() {
        const submitBtns = document.querySelectorAll('.btn-submit');
        const enableBtns = () => {
            submitBtns.forEach(btn => {
                btn.disabled = false;
                if(btn.dataset.originalHTML) btn.innerHTML = btn.dataset.originalHTML;
            });
        };

        // Disable to prevent multiple clicks
        submitBtns.forEach(btn => {
            if(!btn.disabled) {
                btn.dataset.originalHTML = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';
            }
        });

        const fields = [
            { id: 'req_name', label: 'Full Name' },
            { id: 'req_email', label: 'Email Address' },
            { id: 'req_contact', label: 'Contact Number' },
            { id: 'req_course', label: 'Course & Section' },
            { id: 'req_subject', label: 'Subject' },
            { id: 'req_station', label: 'Station Setup' },
            { id: 'req_batch', label: 'Batch No.' },
            { id: 'req_date', label: 'Date' },
            { id: 'req_time', label: 'Time' },
        ];

        for (const f of fields) {
            const el = document.getElementById(f.id);
            if (!el.value.trim()) {
                el.focus();
                el.style.borderColor = 'var(--red)';
                setTimeout(() => el.style.borderColor = '', 2000);
                showAlert(`Please fill in: ${f.label}`, 'error');
                enableBtns();
                return;
            }
        }

        if (!Object.keys(labCart).length) {
            showAlert('Please add at least one item to your requisition.', 'error');
            enableBtns();
            return;
        }

        const formData = new FormData();
        formData.append('name', document.getElementById('req_name').value.trim());
        formData.append('email', document.getElementById('req_email').value.trim());
        formData.append('contact', document.getElementById('req_contact').value.trim());
        formData.append('course', document.getElementById('req_course').value.trim());
        formData.append('subject', document.getElementById('req_subject').value.trim());
        formData.append('station', document.getElementById('req_station').value.trim());
        formData.append('batch', document.getElementById('req_batch').value.trim());
        formData.append('date', document.getElementById('req_date').value);
        formData.append('time', document.getElementById('req_time').value);

        const cartObj = {};
        Object.entries(labCart).forEach(([id, v]) => {
            cartObj[id] = { quantity: v.qty };
        });
        formData.append('cart', JSON.stringify(cartObj));

        fetch('ajax/reservation_submit.php', {
            method: 'POST',
            body: formData
        })
            .then(async r => {
                const text = await r.text();
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Server response was not JSON:', text);
                    throw new Error('Invalid JSON response from server');
                }
            })
            .then(res => {
                enableBtns();
                if (res.status === 'success') {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Requisition Submitted!',
                            text: 'Your request has been sent to the lab custodian for review. You can track its status in the My Requisitions page.',
                            confirmButtonColor: '#C0392B'
                        });
                    } else {
                        showAlert('Requisition submitted! You will be notified once approved.', 'success');
                    }
                    
                    labCart = {};
                    sessionStorage.removeItem('reserve_form_data');
                    sessionStorage.removeItem('reserve_cart');
                    renderCart();
                    document.querySelectorAll('.btn-add.added').forEach(b => {
                        b.classList.remove('added');
                        b.innerHTML = '<i class="bi bi-cart-plus"></i> Add to Cart';
                    });
                    ['req_name', 'req_email', 'req_contact', 'req_course',
                        'req_subject', 'req_station', 'req_batch', 'req_date'].forEach(id => {
                            document.getElementById(id).value = '';
                        });
                    document.getElementById('req_time').selectedIndex = 0;
                } else {
                    showAlert(res.status === 'error' ? res.message : 'Something went wrong. Please try again.', 'error');
                }
            })
            .catch((err) => {
                enableBtns();
                console.error(err);
                showAlert('Submission failed: ' + err.message, 'error');
            });
    }
    function saveFormData() {
        const data = {
            name: document.getElementById('req_name').value,
            email: document.getElementById('req_email').value,
            contact: document.getElementById('req_contact').value,
            course: document.getElementById('req_course').value,
            subject: document.getElementById('req_subject').value,
            station: document.getElementById('req_station').value,
            batch: document.getElementById('req_batch').value,
            date: document.getElementById('req_date').value,
            time: document.getElementById('req_time').value
        };
        sessionStorage.setItem('reserve_form_data', JSON.stringify(data));
        sessionStorage.setItem('reserve_cart', JSON.stringify(labCart));
    }

    function loadFormData() {
        const rawData = sessionStorage.getItem('reserve_form_data');
        if (rawData) {
            const data = JSON.parse(rawData);
            document.getElementById('req_name').value = data.name || '';
            document.getElementById('req_email').value = data.email || '';
            document.getElementById('req_contact').value = data.contact || '';
            document.getElementById('req_course').value = data.course || '';
            document.getElementById('req_subject').value = data.subject || '';
            document.getElementById('req_station').value = data.station || '';
            document.getElementById('req_batch').value = data.batch || '';
            document.getElementById('req_date').value = data.date || '';
            document.getElementById('req_time').value = data.time || '';
        }
        const rawCart = sessionStorage.getItem('reserve_cart');
        if (rawCart) {
            labCart = JSON.parse(rawCart);
            renderCart();
        }
    }

    function capitalizeInput(el) {
        if (!el.value) return;
        el.value = el.value.toLowerCase().split(' ').map(w => w.charAt(0).toUpperCase() + w.substr(1)).join(' ');
    }
</script>

<?php require('inc/footer.php'); ?>