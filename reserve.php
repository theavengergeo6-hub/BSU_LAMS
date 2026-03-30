<?php
require('inc/header.php');

$cat_res = mysqli_query($con, "SELECT * FROM lab_categories");
$categories = [];
while($row = mysqli_fetch_assoc($cat_res)){
    $categories[] = $row;
}
?>

<div class="container mt-5 mb-5 px-4 bg-white shadow-sm custom-card p-5 border-0">
    <h2 class="fw-bold mb-4 text-center text-danger">Make a Reservation</h2>
    <div class="step-progress mt-4 px-md-5 mx-auto" style="max-width: 800px;">
        <div class="step-item active" data-step="1">1</div>
        <div class="step-item" data-step="2">2</div>
        <div class="step-item" data-step="3">3</div>
        <div class="step-item" data-step="4">4</div>
    </div>
    <div class="text-center text-muted mb-5">
        <span class="mx-2 fs-6">Student Info</span><span class="mx-2 fs-6">Schedule Details</span><span class="mx-2 fs-6">Select Items</span><span class="mx-2 fs-6">Summary</span>
    </div>

    <!-- Step 1: Student Info -->
    <div id="step-1" class="step-content fade-in-up">
        <h4 class="mb-4">Student Information</h4>
        <div class="row g-4">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-medium">Full Name <span class="text-danger">*</span></label>
                <input type="text" id="req_name" class="form-control form-control-lg bg-light" required placeholder="Juan Dela Cruz">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-medium">Email Address <span class="text-danger">*</span></label>
                <input type="email" id="req_email" class="form-control form-control-lg bg-light" required placeholder="juan@g.batstate-u.edu.ph">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-medium">Contact Number <span class="text-danger">*</span></label>
                <input type="text" id="req_contact" class="form-control form-control-lg bg-light" required placeholder="09123456789">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-medium">Course & Section <span class="text-danger">*</span></label>
                <input type="text" id="req_course" class="form-control form-control-lg bg-light" required placeholder="BSHM 3A">
            </div>
        </div>
        <div class="text-end mt-4">
            <button type="button" class="btn btn-primary px-5 py-2 fs-5 shadow" onclick="nextStep()">Next  <i class="bi bi-arrow-right ms-2"></i></button>
        </div>
    </div>

    <!-- Step 2: Reservation Details -->
    <div id="step-2" class="step-content d-none fade-in-up">
        <h4 class="mb-4">Reservation Details</h4>
        <div class="row g-4">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-medium">Subject Code/Name <span class="text-danger">*</span></label>
                <input type="text" id="req_subject" class="form-control form-control-lg bg-light" required placeholder="HM 101">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-medium">Station Setup <span class="text-danger">*</span></label>
                <input type="text" id="req_station" class="form-control form-control-lg bg-light" required placeholder="Station 1">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-medium">Batch No. <span class="text-danger">*</span></label>
                <input type="text" id="req_batch" class="form-control form-control-lg bg-light" required placeholder="Batch 1">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-medium">Date <span class="text-danger">*</span></label>
                <input type="date" id="req_date" class="form-control form-control-lg bg-light" required min="<?= date('Y-m-d') ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-medium">Time (7AM - 5PM) <span class="text-danger">*</span></label>
                <select id="req_time" class="form-select form-select-lg bg-light" required>
                    <option value="" disabled selected>Select Time</option>
                    <?php
                        $start = strtotime('07:00');
                        $end = strtotime('17:00');
                        while($start <= $end){
                            echo "<option value='".date('h:i A', $start)."'>".date('h:i A', $start)."</option>";
                            $start = strtotime('+30 minutes', $start);
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="d-flex justify-content-between mt-4">
            <button class="btn btn-secondary px-4 py-2 shadow-sm" onclick="prevStep()"><i class="bi bi-arrow-left me-2"></i> Previous</button>
            <button class="btn btn-primary px-5 py-2 shadow" onclick="nextStep()">Next <i class="bi bi-arrow-right ms-2"></i></button>
        </div>
    </div>

    <!-- Step 3: Select Items -->
    <div id="step-3" class="step-content d-none fade-in-up">
        <div class="row g-4">
            <div class="col-lg-8">
                <h4 class="mb-4">Select Items</h4>
                <!-- Category Tabs -->
                <ul class="nav nav-tabs nav-pills mb-4" id="categoryTabs" role="tablist">
                    <?php
                    $first = true;
                    foreach($categories as $cat) {
                        $active = $first ? 'active bg-danger text-white' : 'text-dark';
                        echo "<li class='nav-item mx-1' role='presentation'>
                            <button class='nav-link rounded-pill px-4 {$active} border border-danger fw-medium mb-2 w-100 shadow-sm transition' id='cat-btn-{$cat['id']}' data-bs-toggle='tab' data-bs-target='#cat-pane-{$cat['id']}' type='button' role='tab' onclick='loadItems({$cat['id']})'>{$cat['name']}</button>
                        </li>";
                        $first = false;
                    }
                    ?>
                </ul>
                <div class="tab-content" id="categoryTabsContent" style="min-height: 400px;">
                    <?php
                    $first = true;
                    foreach($categories as $cat) {
                        $show = $first ? 'show active' : '';
                        echo "<div class='tab-pane fade {$show}' id='cat-pane-{$cat['id']}' role='tabpanel'>
                            <div class='row' id='items-container-{$cat['id']}'>
                                <div class='col-12 text-center py-5'><div class='spinner-border text-danger'></div></div>
                            </div>
                        </div>";
                        $first = false;
                    }
                    ?>
                </div>
            </div>
            
            <!-- Cart Section -->
            <div class="col-lg-4">
                <div class="card bg-light shadow-sm sticky-top border-0 custom-card" style="top: 100px;">
                    <div class="card-header bg-danger text-white p-3 rounded-top">
                        <h4 class="m-0 fs-5 fw-bold"><i class="bi bi-cart3 me-2"></i>Selected Items</h4>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush" id="cart-items" style="max-height: 400px; overflow-y: auto;">
                            <li class="list-group-item text-muted text-center py-4 bg-transparent border-0">Cart is empty</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between mt-5 border-top pt-4">
            <button class="btn btn-secondary px-4 py-2 shadow-sm border-0" onclick="prevStep()"><i class="bi bi-arrow-left me-2"></i> Previous</button>
            <button class="btn btn-primary px-5 py-2 shadow border-0" onclick="nextStep()">Proceed to Summary <i class="bi bi-arrow-right ms-2"></i></button>
        </div>
    </div>

    <!-- Step 4: Summary -->
    <div id="step-4" class="step-content d-none fade-in-up">
        <h4 class="mb-4">Reservation Summary</h4>
        <div class="row g-5">
            <div class="col-md-6">
                <div class="card mb-4 border-0 shadow-sm custom-card bg-light">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4 border-bottom pb-2 fw-bold text-danger"><i class="bi bi-person me-2"></i>Student Details</h5>
                        <p class="mb-2"><strong class="me-2 text-secondary">Name:</strong> <span id="sum-name" class="fw-medium"></span></p>
                        <p class="mb-2"><strong class="me-2 text-secondary">Email:</strong> <span id="sum-email" class="fw-medium"></span></p>
                        <p class="mb-2"><strong class="me-2 text-secondary">Contact:</strong> <span id="sum-contact" class="fw-medium"></span></p>
                        <p class="m-0"><strong class="me-2 text-secondary">Course:</strong> <span id="sum-course" class="fw-medium"></span></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4 border-0 shadow-sm custom-card bg-light">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4 border-bottom pb-2 fw-bold text-danger"><i class="bi bi-calendar-event me-2"></i>Schedule</h5>
                        <p class="mb-2"><strong class="me-2 text-secondary">Subject:</strong> <span id="sum-subject" class="fw-medium"></span></p>
                        <p class="mb-2"><strong class="me-2 text-secondary">Station:</strong> <span id="sum-station" class="fw-medium"></span></p>
                        <p class="mb-2"><strong class="me-2 text-secondary">Batch:</strong> <span id="sum-batch" class="fw-medium"></span></p>
                        <p class="mb-2"><strong class="me-2 text-secondary">Date:</strong> <span id="sum-date" class="fw-medium"></span></p>
                        <p class="m-0"><strong class="me-2 text-secondary">Time:</strong> <span id="sum-time" class="fw-medium"></span></p>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-4">
                <div class="card border-0 shadow-sm custom-card bg-light">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4 border-bottom pb-2 fw-bold text-danger"><i class="bi bi-box-seam me-2"></i>Requested Items</h5>
                        <ul id="summary-cart" class="list-unstyled mb-0 lh-lg"></ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between mt-5 pt-4 border-top">
            <button class="btn btn-secondary shadow-sm px-4 py-2 border-0" onclick="prevStep()"><i class="bi bi-arrow-left me-2"></i> Previous</button>
            <button class="btn btn-success shadow px-5 py-2 border-0 fw-bold fs-5" onclick="submitReservation()"><i class="bi bi-check-circle me-2"></i> Submit Reservation</button>
        </div>
    </div>
</div>

<script>
let loadedCategories = {};

document.addEventListener('DOMContentLoaded', () => {
    // Load first category initially
    <?php if(count($categories) > 0) { echo "loadItems({$categories[0]['id']});"; } ?>
    
    // Add active styling dynamically to category tabs
    const triggerTabList = document.querySelectorAll('#categoryTabs button')
    triggerTabList.forEach(triggerEl => {
        triggerEl.addEventListener('click', event => {
            triggerTabList.forEach(btn => {
                btn.classList.remove('bg-danger', 'text-white');
                btn.classList.add('text-dark');
            });
            event.target.classList.add('bg-danger', 'text-white');
            event.target.classList.remove('text-dark');
        })
    })
});

function loadItems(catId) {
    if(loadedCategories[catId]) return;
    
    fetch(`ajax/get_items_by_category.php?category_id=${catId}`)
        .then(res => res.json())
        .then(data => {
            let container = document.getElementById(`items-container-${catId}`);
            container.innerHTML = '';
            
            if(data.length === 0) {
                container.innerHTML = '<div class="col-12 p-5 text-center text-muted border rounded bg-light">No items found in this category.</div>';
                loadedCategories[catId] = true;
                return;
            }
            
            data.forEach(item => {
                let img = item.image_path ? `uploads/lab_items/${item.image_path}` : 'assets/images/placeholder.png';
                let btnDisabled = item.available_quantity <= 0 ? 'disabled' : '';
                let btnText = item.available_quantity <= 0 ? 'Out of Stock' : 'Add to Cart';
                let bgClass = item.available_quantity <= 0 ? 'bg-light opacity-75' : '';
                
                let cardHTML = `
                <div class="col-md-6 mb-4 fade-in-up">
                    <div class="card h-100 shadow-sm border-0 ${bgClass} custom-card">
                        <img src="${img}" class="card-img-top" onerror="this.src='assets/images/placeholder.png'" style="height:150px;object-fit:cover;">
                        <div class="card-body p-3">
                            <h5 class="card-title fw-bold fs-6 mb-3 text-dark text-truncate" title="${item.item_name}">${item.item_name}</h5>
                            <div class="d-flex justify-content-between align-items-center mb-3 mb-2 small bg-light p-2 rounded">
                                <span class="text-secondary fw-medium">Available:</span> 
                                <span class="badge ${item.available_quantity > 0 ? 'bg-success' : 'bg-danger'} rounded-pill">${item.available_quantity} ${item.unit}</span>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text bg-white">Qty</span>
                                <input type="number" id="qty-${item.id}" class="form-control" value="1" min="1" max="${item.available_quantity}" ${btnDisabled}>
                            </div>
                            <button class="btn ${item.available_quantity > 0 ? 'btn-outline-danger' : 'btn-secondary'} w-100 btn-sm fw-medium shadow-sm" onclick="addToCart(${item.id}, '${item.item_name}', ${item.available_quantity})" ${btnDisabled}>
                                <i class="bi bi-cart-plus me-1"></i> ${btnText}
                            </button>
                        </div>
                    </div>
                </div>`;
                container.innerHTML += cardHTML;
            });
            loadedCategories[catId] = true;
        })
        .catch(err => {
            console.error(err);
            document.getElementById(`items-container-${catId}`).innerHTML = '<div class="col-12"><div class="alert alert-danger text-center">Failed to load items.</div></div>';
        });
}
</script>

<?php require('inc/footer.php'); ?>
