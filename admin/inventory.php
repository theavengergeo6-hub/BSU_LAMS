<?php
require('header.php');

$cat_filter = isset($_GET['category_id']) ? (int)$_GET['category_id'] : '';
$search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';

$where = "1=1";
if($cat_filter) $where .= " AND category_id = $cat_filter";
if($search) $where .= " AND item_name LIKE '%$search%'";

$categories = mysqli_query($con, "SELECT * FROM lab_categories");
?>
<style>
/* Premium Table & Card Styling */
.custom-card {
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03) !important;
    overflow: hidden;
    transition: all 0.3s ease;
}
.table > :not(caption) > * > * {
    padding: 1.2rem 1.25rem;
    border-bottom-color: #f1f3f5;
    vertical-align: middle;
}
.table-light th {
    background-color: #f8f9fa;
    color: #6c757d;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.8px;
    border-bottom: 2px solid #e9ecef !important;
}
.table-hover tbody tr {
    transition: all 0.2s ease;
}
.table-hover tbody tr:hover {
    background-color: #fffafb; 
    box-shadow: inset 4px 0 0 0 #dc3545;
}
.action-btn {
    border-radius: 8px;
    padding: 0.45rem 0.6rem;
    transition: all 0.2s ease;
}
.action-btn:hover {
    transform: translateY(-2px);
}
.item-thumbnail {
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.item-thumbnail:hover {
    transform: scale(1.3);
    z-index: 10;
    position: relative;
}
.filter-card {
    border-radius: 12px;
    border-left: 5px solid #dc3545 !important;
    background: linear-gradient(to right, #ffffff, #fcfcfc);
}
.qty-badge {
    padding: 0.55em 0.9em;
    border-radius: 8px;
    font-weight: 700;
    letter-spacing: 0.5px;
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
    <h4 class="fw-bold mb-0 text-danger"><i class="bi bi-box-seam me-2"></i>Manage Inventory</h4>
    <button class="btn btn-danger shadow fw-bold" data-bs-toggle="modal" data-bs-target="#addItemModal"><i class="bi bi-plus-lg me-2"></i>Add New Item</button>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm custom-card mb-4 bg-white p-3">
    <form class="row g-3" method="GET">
        <div class="col-md-4">
            <select name="category_id" class="form-select bg-light">
                <option value="">All Categories</option>
                <?php while($c = mysqli_fetch_assoc($categories)): ?>
                    <option value="<?= $c['id'] ?>" <?= $cat_filter == $c['id'] ? 'selected' : '' ?>><?= $c['name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-6">
            <input type="text" name="search" class="form-control bg-light" placeholder="Search item name..." value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100 shadow"><i class="bi bi-search me-1"></i> Search</button>
        </div>
    </form>
</div>

<!-- Table -->
<div class="card border-0 shadow-sm custom-card table-responsive">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Photo</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th class="text-center">Unit</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">Available</th>
                    <th class="text-center" width="250">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $q = "SELECT i.*, c.name as cat_name FROM lab_items i JOIN lab_categories c ON i.category_id = c.id WHERE $where ORDER BY i.item_name ASC";
                $res = mysqli_query($con, $q);
                
                if(mysqli_num_rows($res) > 0):
                    while($row = mysqli_fetch_assoc($res)):
                        $img = $row['image_path'] ? "../uploads/lab_items/{$row['image_path']}" : "../assets/images/placeholder.png";
                        $avail_badge = $row['available_quantity'] > 0 ? 'bg-success' : 'bg-danger';
                        $min_t = isset($row['min_threshold']) ? $row['min_threshold'] : 0;
                        if($row['available_quantity'] <= $min_t && $row['available_quantity'] > 0) $avail_badge = 'bg-warning text-dark';
                ?>
                <tr>
                    <td><img src="<?= $img ?>" class="item-thumbnail object-fit-cover bg-white p-1" width="55" height="55" onerror="this.src='../assets/images/placeholder.png'"></td>
                    <td class="fw-bold text-dark fs-6"><?= $row['item_name'] ?></td>
                    <td class="text-secondary fw-medium"><?= $row['cat_name'] ?></td>
                    <td class="text-center text-muted fw-medium"><?= $row['unit'] ?></td>
                    <td class="text-center fw-bold text-dark fs-6"><?= $row['total_quantity'] ?></td>
                    <td class="text-center"><span class="badge <?= $avail_badge ?> qty-badge shadow-sm"><?= $row['available_quantity'] ?></span></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary action-btn shadow-sm me-1" title="Edit Item" onclick="editItem(<?= $row['id'] ?>)"><i class="bi bi-pencil-square"></i></button>
                        <button class="btn btn-sm btn-outline-success action-btn shadow-sm me-1" title="Add Quantity" onclick="adjustQty(<?= $row['id'] ?>, '+')"><i class="bi bi-plus-lg"></i></button>
                        <button class="btn btn-sm btn-outline-danger action-btn shadow-sm me-1" title="Remove Quantity" onclick="adjustQty(<?= $row['id'] ?>, '-')"><i class="bi bi-dash-lg"></i></button>
                        <a href="item_logs.php?item_id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-secondary action-btn shadow-sm" title="View Logs"><i class="bi bi-clock-history"></i></a>
                    </td>
                </tr>
                <?php 
                    endwhile; 
                else: 
                ?>
                <tr><td colspan="7" class="text-center py-5 text-muted">No items found matching the filter.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Add Item -->
<div class="modal fade" id="addItemModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Add New Item</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="addItemForm" onsubmit="submitAddItem(event)">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Item Name *</label>
                        <input type="text" name="item_name" class="form-control bg-light" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Category *</label>
                        <select name="category_id" class="form-select bg-light" required>
                            <?php 
                            mysqli_data_seek($categories, 0);
                            while($c = mysqli_fetch_assoc($categories)): 
                            ?>
                                <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Unit *</label>
                            <input type="text" name="unit" class="form-control bg-light" value="piece" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Quantity *</label>
                            <input type="number" name="quantity" class="form-control bg-light" value="1" min="1" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-medium">Photo (Optional)</label>
                        <input type="file" name="item_photo" class="form-control bg-light" accept=".jpg,.jpeg,.png">
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary shadow-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger shadow-sm">Save Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Item -->
<div class="modal fade" id="editItemModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Item Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editItemForm" onsubmit="submitEditItem(event)">
                    <input type="hidden" name="item_id" id="edit_item_id">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Item Name *</label>
                        <input type="text" name="item_name" id="edit_item_name" class="form-control form-control-lg bg-light" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Category *</label>
                        <select name="category_id" id="edit_category_id" class="form-select form-select-lg bg-light" required>
                            <?php 
                            mysqli_data_seek($categories, 0);
                            while($c = mysqli_fetch_assoc($categories)): 
                            ?>
                                <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Unit Measurement *</label>
                        <input type="text" name="unit" id="edit_unit" class="form-control form-control-lg bg-light" required>
                    </div>
                    <div class="mb-4 bg-light p-3 rounded border">
                        <label class="form-label fw-bold text-secondary"><i class="bi bi-image me-1"></i> Update Item Photo</label>
                        <input type="file" name="item_photo" class="form-control bg-white" accept=".jpg,.jpeg,.png">
                        <div class="form-check mt-3">
                            <input class="form-check-input border-secondary shadow-sm" type="checkbox" name="remove_photo" value="1" id="removePhotoCheck">
                            <label class="form-check-label text-danger fw-bold" for="removePhotoCheck">Remove current photo</label>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-light border shadow-sm px-4 me-2 fw-bold" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary shadow px-4 fw-bold"><i class="bi bi-check-circle me-1"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Adjust Qty -->
<div class="modal fade" id="adjustQtyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header bg-danger text-white border-0" id="adjustHeader">
                <h5 class="modal-title fw-bold"><i class="bi bi-calculator me-2"></i>Adjust Quantity</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="adjustQtyForm" onsubmit="submitAdjustQty(event)">
                    <input type="hidden" name="item_id" id="adj_item_id">
                    <input type="hidden" name="change_type" id="adj_change_type">
                    <div class="mb-3">
                        <label class="form-label fw-medium" id="adj_label">Quantity to Add/Remove</label>
                        <input type="number" name="quantity_change" class="form-control form-control-lg bg-light fw-bold text-center" min="1" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-medium">Remarks/Reason *</label>
                        <input type="text" name="remarks" class="form-control bg-light" placeholder="e.g. New delivery, Damaged, Lost" required>
                    </div>
                    <button type="submit" class="btn btn-danger w-100 shadow fw-bold p-2" id="adj_btn">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function editItem(id) {
    fetch('../ajax/get_item.php?id=' + id)
    .then(res => res.json())
    .then(data => {
        document.getElementById('edit_item_id').value = data.id;
        document.getElementById('edit_item_name').value = data.item_name;
        document.getElementById('edit_category_id').value = data.category_id;
        document.getElementById('edit_unit').value = data.unit;
        document.getElementById('removePhotoCheck').checked = false;
        
        let modal = new bootstrap.Modal(document.getElementById('editItemModal'));
        modal.show();
    });
}

function submitEditItem(e) {
    e.preventDefault();
    let fd = new FormData(document.getElementById('editItemForm'));
    fetch('../ajax/edit_item.php', { method:'POST', body: fd })
    .then(r => r.json())
    .then(data => {
        if(data.status === 'success') {
            Swal.fire({
                icon: 'success', title: 'Updated', text: 'Item attributes saved securely', toast: true, position: 'top-end', timer: 2000, showConfirmButton: false
            }).then(() => location.reload());
        } else alert(data.message);
    });
}

function adjustQty(id, type) {
    document.getElementById('adj_item_id').value = id;
    document.getElementById('adj_change_type').value = type;
    
    let modal = new bootstrap.Modal(document.getElementById('adjustQtyModal'));
    
    if(type === '+') {
        document.getElementById('adjustHeader').className = 'modal-header bg-success text-white border-0';
        document.getElementById('adj_label').textContent = 'Quantity to Add';
        document.getElementById('adj_btn').textContent = 'Add Quantity';
        document.getElementById('adj_btn').className = 'btn btn-success w-100 shadow fw-bold p-2';
    } else {
        document.getElementById('adjustHeader').className = 'modal-header bg-danger text-white border-0';
        document.getElementById('adj_label').textContent = 'Quantity to Remove';
        document.getElementById('adj_btn').textContent = 'Remove Quantity';
        document.getElementById('adj_btn').className = 'btn btn-danger w-100 shadow fw-bold p-2';
    }
    
    modal.show();
}

function submitAdjustQty(e) {
    e.preventDefault();
    let fd = new FormData(document.getElementById('adjustQtyForm'));
    fetch('../ajax/update_inventory.php', { method:'POST', body: fd })
    .then(r => r.json())
    .then(data => {
        if(data.status === 'success') location.reload();
        else alert(data.message);
    });
}

function submitAddItem(e) {
    e.preventDefault();
    let fd = new FormData(document.getElementById('addItemForm'));
    fetch('../ajax/add_item.php', { method:'POST', body: fd })
    .then(r => r.json())
    .then(data => {
        if(data.status === 'success') {
            Swal.fire({
                icon: 'success', title: 'Added', text: 'Item saved successfully', toast: true, position: 'top-end', timer: 2000, showConfirmButton: false
            }).then(() => location.reload());
        } else alert(data.message);
    });
}
</script>

<?php require('footer.php'); ?>
