<!-- ════════════════════════════════════
     GLOBAL SYSTEM FOOTER
     ════════════════════════════════════ -->
<div class="container-fluid bg-white mt-5">
    <div class="row">
        <!-- Brand Summary Column -->
        <div class="col-lg-8 p-4">
            <h4 class="fw-bold fs-4 mb-2 text-danger">KTERS</h4>
            <p>
                BatStateU ARASOF-Nasugbu Kitchen Tools and Equipment Requisition System for the Hot Kitchen, Cold Kitchen, Food & Beverage Services, and
                Laundry facilities of Batangas State University.
            </p>
        </div>
        <!-- Footer Navigation Quicklinks -->
        <div class="col-lg-4 p-4">
            <h5 class="mb-3 text-dark">Links</h5>
            <a href="<?= BASE_URL ?>/index.php" class="d-inline-block mb-2 text-dark text-decoration-none">Home</a><br>
            <a href="<?= BASE_URL ?>/reserve.php" class="d-inline-block mb-2 text-dark text-decoration-none">Request Equipment</a><br>
            <a href="<?= BASE_URL ?>/my_reservations.php" class="d-inline-block mb-2 text-dark text-decoration-none">My Requisitions</a><br>
        </div>
    </div>
</div>

<!-- Copyright Section -->
<h6 class="text-center bg-danger text-white m-0 p-3">Designed and Developed for BSU - All Rights Reserved</h6>

<!-- Custom Global JavaScript Library (Time-busted to avoid proxy/client caching issues) -->
<script src="<?= BASE_URL ?>/assets/js/main.js?v=<?= time() ?>"></script>

<!-- Back to Top Smooth Scrolling Button -->
<button type="button" id="btn-back-to-top" title="Back to Top">
    <i class="bi bi-arrow-up"></i>
</button>

<script>
    // Access the floating back-to-top button DOM node
    const mybutton = document.getElementById("btn-back-to-top");

    if (mybutton) {
        // Show the button only when scrolling down 100px past document header top
        window.addEventListener("scroll", () => {
            const scrollPos = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
            if (scrollPos > 100) {
                mybutton.style.setProperty('display', 'flex', 'important');
            } else {
                mybutton.style.setProperty('display', 'none', 'important');
            }
        });

        // Intercept button click events to trigger animated smooth scroll back to header top coordinate
        mybutton.addEventListener("click", () => {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });
    }
</script>

</body>
</html>