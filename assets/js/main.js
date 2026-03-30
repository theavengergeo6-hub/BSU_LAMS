// BSU LAMS Global Helpers
document.addEventListener("DOMContentLoaded", () => {
    console.log("BSU LAMS Global Helpers Loaded");
});

function showToast(type, message) {
    let icon = type === 'success' ? 'success' : 'error';
    let title = type === 'success' ? 'Success' : 'Error';
    
    if(typeof Swal !== 'undefined') {
        Swal.fire({
            icon: icon, title: title, text: message,
            toast: true, position: 'top-end', showConfirmButton: false,
            timer: 3000, timerProgressBar: true
        });
    } else {
        alert(title + ": " + message);
    }
}

function showLoader() {
    let loader = document.createElement('div');
    loader.className = 'loader-overlay';
    loader.id = 'pageLoader';
    loader.innerHTML = '<div class="spinner-border text-danger" role="status"></div>';
    document.body.appendChild(loader);
}

function hideLoader() {
    let loader = document.getElementById('pageLoader');
    if (loader) loader.remove();
}
