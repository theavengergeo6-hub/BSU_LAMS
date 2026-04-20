        </div> <!-- End Content Area -->
        
        <!-- Visible Admin Footer -->
        <footer style="padding: 24px; text-align: center; color: #888; font-size: 0.85rem; border-top: 1px solid #eaeaea; background: transparent; margin-top: auto;">
            &copy; <?= date('Y') ?> BSU Kitchen Laboratory Requisition System. All Rights Reserved.
        </footer>
        
        <!-- Back to Top Button -->
        <div id="btn-back-to-top" style="position: fixed; bottom: 40px; right: 40px; width: 60px; height: 60px; border-radius: 50%; background: #cc0000; color: white; display: flex; align-items: center; justify-content: center; border: none; outline: none; cursor: pointer; z-index: 9999999; box-shadow: 0 4px 20px rgba(0,0,0,0.5); font-family: Arial, sans-serif; font-weight: bold; font-size: 30px; opacity: 0; pointer-events: none; transition: opacity 0.3s ease, transform 0.3s ease; transform: translateY(20px);">
            &uparrow;
        </div>
        
    </div> <!-- End Main Content -->
</div> <!-- End Admin Wrapper -->

<!-- Required JS Scripts for Admin -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>

<script>
    (function() {
        const btn = document.getElementById("btn-back-to-top");
        if (!btn) return;

        function refresh() {
            let y = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
            
            // Check potential containers
            const containers = document.querySelectorAll('.main-content, .admin-wrapper, .page-wrap, .res-table-container, #main-content');
            containers.forEach(c => {
                if (c.scrollTop > y) y = c.scrollTop;
            });

            if (y > 100) {
                btn.style.opacity = "1";
                btn.style.pointerEvents = "auto";
                btn.style.transform = "translateY(0)";
            } else {
                btn.style.opacity = "0";
                btn.style.pointerEvents = "none";
                btn.style.transform = "translateY(20px)";
            }
        }

        window.addEventListener("scroll", refresh, { passive: true });
        window.addEventListener("resize", refresh);
        setInterval(refresh, 500); // Aggressive backup check
        refresh();

        btn.addEventListener("click", function() {
            window.scrollTo({ top: 0, behavior: "smooth" });
            const containers = document.querySelectorAll('.main-content, .admin-wrapper, .page-wrap, #main-content');
            containers.forEach(c => {
                try { c.scrollTo({ top: 0, behavior: "smooth" }); } catch(e) { c.scrollTop = 0; }
            });
        });
    })();
</script>



</body>
</html>

