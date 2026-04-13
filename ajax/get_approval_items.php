<?php
/**
 * ajax/get_approval_items.php
 * Returns the approval modal body HTML for a given reservation.
 * Shows each item with: item name, requested qty (read-only),
 * approved qty (editable, defaults to requested), available stock (read-only).
 */
require_once('../config.php'); // pulls in $con

$id = (int) ($_GET['id'] ?? 0);
if (!$id) {
    echo '<p class="text-danger">Invalid requisition.</p>';
    exit;
}

// Fetch reservation details
$res = mysqli_query($con, "SELECT * FROM lab_reservations WHERE id = $id");
$r = mysqli_fetch_assoc($res);
if (!$r) {
    echo '<p class="text-danger">Requisition not found.</p>';
    exit;
}

// Fetch items with available stock
$items_q = mysqli_query($con, "
    SELECT ri.id        AS res_item_id,
           ri.item_id,
           ri.requested_quantity,
           ri.approved_quantity,
           i.item_name,
           i.available_quantity
    FROM lab_reservation_items ri
    JOIN lab_items i ON ri.item_id = i.id
    WHERE ri.reservation_id = $id
");
$items = [];
while ($row = mysqli_fetch_assoc($items_q))
    $items[] = $row;
?>

<style>
    /* ── Info grid ── */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        margin-bottom: 24px;
    }

    .info-block {
        background: var(--surface-2);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 12px 14px;
    }

    .info-block-label {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--text-3);
        margin-bottom: 4px;
    }

    .info-block-value {
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--text);
    }

    /* ── Items table in modal ── */
    .items-section-label {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--text-3);
        margin-bottom: 10px;
    }

    .items-approval-table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Sora', sans-serif;
        margin-bottom: 20px;
    }

    .items-approval-table thead th {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--text-3);
        padding: 8px 12px;
        background: var(--surface-2);
        border-bottom: 1px solid var(--border-2);
        text-align: left;
    }

    .items-approval-table thead th.center {
        text-align: center;
    }

    .items-approval-table tbody td {
        padding: 12px 12px;
        border-bottom: 1px solid var(--border);
        font-size: 0.84rem;
        color: var(--text-2);
        vertical-align: middle;
    }

    .items-approval-table tbody tr:last-child td {
        border-bottom: none;
    }

    .item-name-cell {
        font-weight: 600;
        color: var(--text) !important;
    }

    /* Qty pill (read-only display) */
    .qty-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.82rem;
    }

    .qty-requested {
        background: rgba(180, 83, 9, .10);
        color: #92400e;
    }

    .qty-stock {
        background: rgba(15, 118, 110, .10);
        color: #065f46;
    }

    .qty-stock.low {
        background: rgba(192, 57, 43, .10);
        color: #7f1d1d;
    }

    /* Approved qty input */
    .approved-qty-wrap {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .approved-qty-input {
        width: 70px;
        padding: 5px 8px;
        border: 1px solid var(--border-2);
        border-radius: 7px;
        font-family: 'Sora', sans-serif;
        font-size: 0.84rem;
        font-weight: 600;
        color: var(--text);
        background: var(--surface);
        text-align: center;
        transition: border-color .15s, box-shadow .15s;
    }

    .approved-qty-input:focus {
        outline: none;
        border-color: var(--red);
        box-shadow: 0 0 0 3px rgba(192, 57, 43, .12);
    }

    .approved-qty-input.input-error {
        border-color: var(--red) !important;
        background: rgba(192, 57, 43, .05);
    }

    .qty-error-msg {
        display: none;
        font-size: 0.7rem;
        color: var(--red);
        font-weight: 600;
        margin-top: 3px;
    }

    /* ── Footer actions ── */
    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 16px;
        border-top: 1px solid var(--border);
    }

    .btn-modal-cancel {
        padding: 9px 20px;
        border-radius: 8px;
        font-family: 'Sora', sans-serif;
        font-size: 0.82rem;
        font-weight: 600;
        background: var(--surface-2);
        color: var(--text-2);
        border: 1px solid var(--border-2);
        cursor: pointer;
        transition: all .15s;
    }

    .btn-modal-cancel:hover {
        background: var(--surface-3);
        color: var(--text);
    }

    .btn-modal-approve {
        padding: 9px 22px;
        border-radius: 8px;
        font-family: 'Sora', sans-serif;
        font-size: 0.82rem;
        font-weight: 600;
        background: #064e3b;
        color: #fff;
        border: 1px solid #064e3b;
        cursor: pointer;
        transition: all .15s;
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .btn-modal-approve:hover {
        background: #065f46;
    }

    .btn-modal-approve:disabled {
        opacity: .55;
        cursor: not-allowed;
    }
</style>

<!-- Reservation info -->
<div class="info-grid">
    <div class="info-block">
        <div class="info-block-label">Student</div>
        <div class="info-block-value"><?= htmlspecialchars($r['user_name'] ?? '') ?></div>
    </div>
    <div class="info-block">
        <div class="info-block-label">Subject / Station</div>
        <div class="info-block-value"><?= htmlspecialchars($r['subject']) ?> · <?= htmlspecialchars($r['station']) ?>
        </div>
    </div>
    <div class="info-block">
        <div class="info-block-label">Date & Time</div>
        <div class="info-block-value">
            <?= date('M d, Y', strtotime($r['reservation_date'])) ?>
            · <?= htmlspecialchars($r['reservation_time']) ?>
        </div>
    </div>
    <div class="info-block">
        <div class="info-block-label">Batch / Section</div>
        <div class="info-block-value"><?= htmlspecialchars($r['batch']) ?> ·
            <?= htmlspecialchars($r['course_section']) ?>
        </div>
    </div>
</div>

<?php if (empty($items)): ?>
    <p style="color:var(--text-3);font-size:.84rem;">No items requested for this requisition.</p>
<?php else: ?>

<?php 
$is_pending = strtolower($r['status']) === 'pending'; 
?>
    <div class="items-section-label">Requested Items <?= $is_pending ? '— Set Approved Quantities' : '— Approved Quantities' ?></div>

    <form id="approvalForm" onsubmit="submitApproval(event)">
        <input type="hidden" name="reservation_id" value="<?= $id ?>">

        <table class="items-approval-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="center">Requested</th>
                    <th class="center"><?= $is_pending ? 'Available Stock' : '' ?></th>
                    <th class="center">Approved Qty</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item):
                    $stock = (int) $item['available_quantity'];
                    $req = (int) $item['requested_quantity'];
                    $appr = (int) ($item['approved_quantity'] ?? 0);
                    $max = min($req, $stock); // can't approve more than requested OR more than stock
                    $low = $stock <= 5;
                    ?>
                    <tr>
                        <td class="item-name-cell"><?= htmlspecialchars($item['item_name']) ?></td>
                        <td style="text-align:center;">
                            <span class="qty-pill qty-requested"><?= $req ?></span>
                        </td>
                        <td style="text-align:center;">
                            <?php if ($is_pending): ?>
                                <span class="qty-pill qty-stock <?= $low ? 'low' : '' ?>">
                                    <?= $stock ?>
                                    <?= $low ? ' ⚠' : '' ?>
                                </span>
                            <?php else: ?>
                                <span style="color:var(--text-3);">-</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align:center;">
                            <?php if ($is_pending): ?>
                                <div class="approved-qty-wrap" style="justify-content:center;">
                                    <div>
                                        <input type="number" class="approved-qty-input" name="approved[<?= $item['res_item_id'] ?>]"
                                            data-item-id="<?= $item['res_item_id'] ?>" data-max-req="<?= $req ?>"
                                            data-max-stock="<?= $stock ?>" value="<?= $max ?>" min="0" max="<?= $max ?>"
                                            oninput="validateQty(this)">
                                        <div class="qty-error-msg" id="err-<?= $item['res_item_id'] ?>"></div>
                                    </div>
                                </div>
                                <?php if ($stock === 0): ?>
                                    <div style="font-size:.68rem;color:var(--red);margin-top:4px;font-weight:600;">Out of stock</div>
                                <?php elseif ($stock < $req): ?>
                                    <div style="font-size:.68rem;color:#92400e;margin-top:4px;">Max approvable: <?= $stock ?></div>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="qty-pill" style="background:var(--surface-3);color:var(--text);"><?= $appr ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="modal-actions">
            <?php if ($is_pending): ?>
                <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn-modal-approve" id="approveSubmitBtn">
                    <i class="bi bi-check-lg"></i> Approve Requisition
                </button>
            <?php else: ?>
                <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Close</button>
            <?php endif; ?>
        </div>
    </form>

    <?php if ($is_pending): ?>
    <script>
        function validateQty(input) {
            const maxReq = parseInt(input.dataset.maxReq);
            const maxStock = parseInt(input.dataset.maxStock);
            const val = parseInt(input.value) || 0;
            const id = input.dataset.itemId;
            const errEl = document.getElementById('err-' + id);

            input.classList.remove('input-error');
            errEl.style.display = 'none';
            errEl.textContent = '';

            if (val > maxReq) {
                input.classList.add('input-error');
                errEl.textContent = 'Cannot exceed requested qty (' + maxReq + ')';
                errEl.style.display = 'block';
            } else if (val > maxStock) {
                input.classList.add('input-error');
                errEl.textContent = 'Cannot exceed available stock (' + maxStock + ')';
                errEl.style.display = 'block';
            } else if (val < 0) {
                input.classList.add('input-error');
                errEl.textContent = 'Cannot be negative';
                errEl.style.display = 'block';
            }

            // Disable submit if any field has an error
            const hasErrors = document.querySelectorAll('.approved-qty-input.input-error').length > 0;
            document.getElementById('approveSubmitBtn').disabled = hasErrors;
        }

        document.querySelectorAll('.approved-qty-input').forEach(validateQty);
    </script>
    <?php endif; ?>

<?php endif; ?>