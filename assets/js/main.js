// Main JS file

document.addEventListener("DOMContentLoaded", () => {
    console.log("BSU LAMS Loaded");
});

function showToast(type, message) {
    let icon = type === 'success' ? 'success' : 'error';
    let title = type === 'success' ? 'Success' : 'Error';
    
    if(typeof Swal !== 'undefined') {
        Swal.fire({
            icon: icon,
            title: title,
            text: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    } else {
        alert(title + ": " + message);
    }
}

function showLoader() {
    let loader = document.createElement('div');
    loader.className = 'loader-overlay';
    loader.id = 'pageLoader';
    loader.innerHTML = '<div class="spinner-border text-danger" role="status"><span class="visually-hidden">Loading...</span></div>';
    document.body.appendChild(loader);
}

function hideLoader() {
    let loader = document.getElementById('pageLoader');
    if (loader) {
        loader.remove();
    }
}

// Multi-step form handling (reserve.php)
let currentStep = 1;
const totalSteps = 4;

function nextStep() {
    if(!validateStep(currentStep)) return;
    
    document.getElementById(`step-${currentStep}`).classList.add('d-none');
    document.querySelector(`.step-item[data-step="${currentStep}"]`).classList.add('completed');
    document.querySelector(`.step-item[data-step="${currentStep}"]`).classList.remove('active');
    
    currentStep++;
    
    document.getElementById(`step-${currentStep}`).classList.remove('d-none');
    document.querySelector(`.step-item[data-step="${currentStep}"]`).classList.add('active');
    
    if(currentStep === 4) {
        updateSummary();
    }
}

function prevStep() {
    document.getElementById(`step-${currentStep}`).classList.add('d-none');
    document.querySelector(`.step-item[data-step="${currentStep}"]`).classList.remove('active');
    
    currentStep--;
    
    document.getElementById(`step-${currentStep}`).classList.remove('d-none');
    document.querySelector(`.step-item[data-step="${currentStep}"]`).classList.add('active');
    document.querySelector(`.step-item[data-step="${currentStep}"]`).classList.remove('completed');
}

function validateStep(step) {
    let isValid = true;
    const formStep = document.getElementById(`step-${step}`);
    if(formStep) {
        const requiredInputs = formStep.querySelectorAll('[required]');
        requiredInputs.forEach(input => {
            if(!input.value) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
            }
        });
        
        if (step === 3) {
            // Check if cart is empty
            if (Object.keys(cart).length === 0) {
                showToast('error', 'Please select at least one item.');
                isValid = false;
            }
        }
    }
    return isValid;
}

// Cart logic for reservations
let cart = {};

function addToCart(itemId, itemName, maxQty) {
    let qtyInput = document.getElementById(`qty-${itemId}`);
    let qty = parseInt(qtyInput.value);
    
    if(isNaN(qty) || qty <= 0) {
        showToast('error', 'Please enter a valid quantity');
        return;
    }
    if(qty > maxQty) {
        showToast('error', `Only ${maxQty} available`);
        return;
    }
    
    cart[itemId] = {
        name: itemName,
        quantity: qty
    };
    
    showToast('success', `${itemName} added to cart`);
    renderCart();
}

function removeFromCart(itemId) {
    delete cart[itemId];
    renderCart();
}

function renderCart() {
    let cartContainer = document.getElementById('cart-items');
    if(!cartContainer) return;
    
    cartContainer.innerHTML = '';
    
    if(Object.keys(cart).length === 0) {
        cartContainer.innerHTML = '<li class="list-group-item text-muted text-center">Cart is empty</li>';
        return;
    }
    
    for (let id in cart) {
        let item = cart[id];
        cartContainer.innerHTML += `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                ${item.name}
                <div>
                    <span class="badge bg-danger rounded-pill me-2">${item.quantity}</span>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="removeFromCart('${id}')"><i class="bi bi-trash"></i></button>
                </div>
            </li>
        `;
    }
}

function updateSummary() {
    document.getElementById('sum-name').textContent = document.getElementById('req_name').value;
    document.getElementById('sum-email').textContent = document.getElementById('req_email').value;
    document.getElementById('sum-contact').textContent = document.getElementById('req_contact').value;
    document.getElementById('sum-subject').textContent = document.getElementById('req_subject').value;
    document.getElementById('sum-course').textContent = document.getElementById('req_course').value;
    document.getElementById('sum-station').textContent = document.getElementById('req_station').value;
    document.getElementById('sum-batch').textContent = document.getElementById('req_batch').value;
    document.getElementById('sum-date').textContent = document.getElementById('req_date').value;
    document.getElementById('sum-time').textContent = document.getElementById('req_time').value;
    
    let sumCart = document.getElementById('summary-cart');
    if(sumCart) {
        sumCart.innerHTML = '';
        for (let id in cart) {
            sumCart.innerHTML += `<li>${cart[id].name} - ${cart[id].quantity}x</li>`;
        }
    }
}

function submitReservation() {
    if(!validateStep(4)) return;
    
    showLoader();
    let formData = new FormData();
    formData.append('name', document.getElementById('req_name').value);
    formData.append('email', document.getElementById('req_email').value);
    formData.append('contact', document.getElementById('req_contact').value);
    formData.append('subject', document.getElementById('req_subject').value);
    formData.append('course', document.getElementById('req_course').value);
    formData.append('station', document.getElementById('req_station').value);
    formData.append('batch', document.getElementById('req_batch').value);
    formData.append('date', document.getElementById('req_date').value);
    formData.append('time', document.getElementById('req_time').value);
    formData.append('cart', JSON.stringify(cart));
    
    fetch('ajax/reservation_submit.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        hideLoader();
        if(data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Reservation Submitted',
                text: `Your reservation number is ${data.res_no}`,
            }).then(() => {
                window.location.href = 'my_reservations.php';
            });
        } else {
            showToast('error', data.message || 'Error occurred');
        }
    })
    .catch(err => {
        hideLoader();
        showToast('error', 'Server error');
        console.error(err);
    });
}
