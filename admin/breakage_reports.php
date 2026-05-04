<?php
require('../config.php');
require('../inc/auth.php');
require('../inc/cron_cooldown.php');
require('../includes/breakage_logger.php');
adminLogin();
runCooldownCron($con);

$months = get_available_months();
$current_page = 'breakage_reports.php';
?>
<?php require('header.php'); ?>

<style>
    .report-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border: none;
    }
    .report-header {
        background: var(--bsu-red);
        color: white;
        border-radius: 12px 12px 0 0;
        padding: 15px 20px;
        font-weight: 600;
    }
    .report-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
    }
    .action-btns .btn {
        margin-right: 5px;
    }
</style>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0 font-weight-bold" style="color: #333;">Breakage Reports</h2>
    </div>

    <div class="card report-card mb-4">
        <div class="card-body">
            <form id="reportForm" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label font-weight-bold">Select Month</label>
                    <select name="filename" id="monthSelect" class="form-select border-danger">
                        <option value="">-- Select Month --</option>
                        <?php foreach ($months as $m): ?>
                            <option value="<?= htmlspecialchars($m['filename']) ?>"><?= htmlspecialchars($m['month_str']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-8 action-btns">
                    <button type="button" id="btnView" class="btn btn-danger">
                        <i class="bi bi-eye"></i> View Report
                    </button>
                    <button type="button" id="btnDownload" class="btn btn-success">
                        <i class="bi bi-file-excel"></i> Download Excel
                    </button>
                    <button type="button" id="btnPdf" class="btn btn-danger" style="background-color: #c82333;">
                        <i class="bi bi-file-pdf"></i> Generate PDF
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card report-card" id="reportResult" style="display:none;">
        <div class="report-header d-flex justify-content-between align-items-center">
            <h5 class="m-0" id="reportTitle">Report Details</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover report-table mb-0">
                    <thead>
                        <tr>
                            <th>ITEM NAME</th>
                            <th>UNIT</th>
                            <th>QUANTITY</th>
                            <th>REMARKS</th>
                        </tr>
                    </thead>
                    <tbody id="reportData">
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <td colspan="2" class="text-end fw-bold">TOTAL ITEMS REMOVED:</td>
                            <td id="totalRemoved" class="fw-bold text-danger" colspan="2">0</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('btnView').addEventListener('click', function() {
    const filename = document.getElementById('monthSelect').value;
    if(!filename) {
        alert('Please select a month first.');
        return;
    }
    
    fetch('ajax/get_breakage_data.php?filename=' + encodeURIComponent(filename))
    .then(res => res.json())
    .then(data => {
        document.getElementById('reportResult').style.display = 'block';
        document.getElementById('reportTitle').textContent = 'Breakage Report for ' + filename.replace('TOOLS AND EQUIPMENT BREAKAGE MONITORING REPORT_', '').replace('.xlsx', '').replace('_', ' ');
        
        let tbody = document.getElementById('reportData');
        tbody.innerHTML = '';
        let total = 0;
        
        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center">No data found or file is empty.</td></tr>';
            document.getElementById('totalRemoved').textContent = '0';
            return;
        }

        data.forEach(row => {
            let tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${row.item_name}</td>
                <td>${row.unit || ''}</td>
                <td>${row.quantity}</td>
                <td>${row.remarks || ''}</td>
            `;
            tbody.appendChild(tr);
            total += parseInt(row.quantity) || 0;
        });
        
        document.getElementById('totalRemoved').textContent = total;
    })
    .catch(err => {
        alert('Error loading data.');
        console.error(err);
    });
});

document.getElementById('btnDownload').addEventListener('click', function() {
    const filename = document.getElementById('monthSelect').value;
    if(filename) {
        window.location.href = 'ajax/download_breakage_report.php?filename=' + encodeURIComponent(filename);
    } else {
        alert('Please select a month first.');
    }
});

document.getElementById('btnPdf').addEventListener('click', function() {
    const filename = document.getElementById('monthSelect').value;
    if(filename) {
        window.open('ajax/generate_breakage_pdf.php?filename=' + encodeURIComponent(filename), '_blank');
    } else {
        alert('Please select a month first.');
    }
});
</script>
</div></div>
</body></html>
