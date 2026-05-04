<?php require('inc/header.php'); ?>

<!-- Hero Section -->
<div class="hero-section position-relative overflow-hidden d-flex align-items-center" style="min-height: 90vh; background: #0f172a;">
    <!-- Animated background particles -->
    <div class="hero-particles position-absolute w-100 h-100 top-0 start-0">
        <div class="particle p1"></div>
        <div class="particle p2"></div>
        <div class="particle p3"></div>
    </div>
    
    <div class="container position-relative z-3">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="hero-content fade-in-up">
                    <span class="badge bg-danger mb-3 px-3 py-2 rounded-pill shadow-sm stagger-1">
                        <i class="bi bi-shield-check me-1"></i> BSU Official Requisition System
                    </span>
                    <h1 class="display-1 fw-bold text-white mb-4 stagger-2" style="letter-spacing: -2px;">
                        Kitchen <span class="gradient-text">Excellence</span> Starts Here
                    </h1>
                    <p class="fs-4 text-white-50 mb-5 stagger-3" style="max-width: 500px; font-weight: 300;">
                        The next-gen Laboratory Management System designed for culinary professionals and students.
                    </p>
                    <div class="d-flex flex-wrap gap-3 stagger-4">
                        <a href="<?= BASE_URL ?>/reserve.php" class="btn btn-primary btn-lg shadow-lg">
                            Get Started <i class="bi bi-arrow-right-short ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <div class="hero-image-wrapper position-relative fade-in-up stagger-5">
                    <div class="hero-blob bg-danger opacity-20 position-absolute top-50 start-50 translate-middle" style="width: 500px; height: 500px; filter: blur(80px); border-radius: 40% 60% 70% 30% / 40% 40% 60% 50%; animation: blob-morph 8s infinite alternate;"></div>
                    <img src="<?= BASE_URL ?>/assets/images/hero.png" alt="BSU KLRS" class="img-fluid rounded-5 shadow-2xl position-relative z-2" onerror="this.src='https://images.unsplash.com/photo-1556910103-1c02745aae4d?auto=format&fit=crop&w=1000&q=80'">
                    
                    <!-- Floating indicators -->
                    <div class="glass-card position-absolute p-3 rounded-4 shadow-lg floating" style="bottom: 10%; left: -5%; z-index: 3;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success rounded-circle p-2"><i class="bi bi-lightning-fill text-white"></i></div>
                            <div>
                                <small class="text-white-50 d-block">Booking</small>
                                <strong class="text-white">Instant Request</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Features Section -->
<div class="container py-5 overflow-hidden">
    <div class="text-center mb-5 fade-in-up">
        <h2 class="display-5 fw-bold mb-3">Why Use <span class="text-danger">KLRS?</span></h2>
        <p class="text-muted fs-5 mx-auto" style="max-width: 700px;">Our system is designed to provide the best possible experience for culinary students and instructors.</p>
    </div>
    
    <div class="feature-slider-container position-relative py-4 fade-in-up">
        <!-- Decoration background -->
        <div class="position-absolute translate-middle-y start-0 w-100 bg-danger opacity-5" style="height: 300px; top: 50%; z-index: -1; border-radius: 100px; filter: blur(50px);"></div>
        
        <div class="swiper mySwiper py-4 px-2">
            <div class="swiper-wrapper">
                <!-- Smart Inventory -->
                <div class="swiper-slide">
                    <div class="feature-card p-5 rounded-4 bg-white shadow-sm border-bottom border-danger border-4 h-100">
                        <div class="icon-box bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px;">
                            <i class="bi bi-search fs-2"></i>
                        </div>
                        <h3 class="fw-bold h4 mb-3">Online Catalog</h3>
                        <p class="text-muted fs-5 mb-0">Browse and select from our extensive collection of kitchen equipment and laboratory essentials.</p>
                    </div>
                </div>
                <!-- Easy Booking -->
                <div class="swiper-slide">
                    <div class="feature-card p-5 rounded-4 bg-white shadow-sm border-bottom border-warning border-4 h-100">
                        <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px;">
                            <i class="bi bi-cursor-fill fs-2"></i>
                        </div>
                        <h3 class="fw-bold h4 mb-3">Easy Requests</h3>
                        <p class="text-muted fs-5 mb-0">Select items from our interactive catalog and submit requests in seconds with a few simple clicks.</p>
                    </div>
                </div>
                <!-- Auto Forms -->
                <div class="swiper-slide">
                    <div class="feature-card p-5 rounded-4 bg-white shadow-sm border-bottom border-primary border-4 h-100">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px;">
                            <i class="bi bi-clock-history fs-2"></i>
                        </div>
                        <h3 class="fw-bold h4 mb-3">Live Status</h3>
                        <p class="text-muted fs-5 mb-0">Monitor the progress of your requests in real-time, from submission until your laboratory session begins.</p>
                    </div>
                </div>
                <!-- Secure Data -->
                <div class="swiper-slide">
                    <div class="feature-card p-5 rounded-4 bg-white shadow-sm border-bottom border-success border-4 h-100">
                        <div class="icon-box bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px;">
                            <i class="bi bi-person-badge fs-2"></i>
                        </div>
                        <h3 class="fw-bold h4 mb-3">My History</h3>
                        <p class="text-muted fs-5 mb-0">Easily access your personal requisition history and review details of your previous and upcoming sessions.</p>
                    </div>
                </div>
            </div>
            <!-- Pagination and Navigation -->
            <div class="swiper-pagination mt-5"></div>
            <div class="swiper-button-next text-danger"></div>
            <div class="swiper-button-prev text-danger"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var swiper = new Swiper(".mySwiper", {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        centeredSlides: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
            dynamicBullets: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        breakpoints: {
            640: { slidesPerView: 1.2 },
            768: { slidesPerView: 2 },
            1024: { slidesPerView: 3 },
        }
    });
});
</script>

<!-- How It Works Section -->
<div class="container py-5 my-5">
    <div class="text-center mb-5 fade-in-up">
        <h2 class="display-5 fw-bold mb-4">Streamlined <span class="text-danger">Process</span></h2>
        <p class="text-muted fs-5 mx-auto" style="max-width: 700px;">Our system simplifies the transition from planning to execution in the kitchen.</p>
    </div>
    
    <div class="row g-4 fade-in-up stagger-2">
        <div class="col-md-4">
            <div class="p-5 rounded-4 bg-white shadow-sm border-top border-danger border-4 h-100 hover-lift">
                <div class="step-num text-danger fw-bold fs-3 mb-4">01</div>
                <h4 class="fw-bold mb-3">Browse Catalog</h4>
                <p class="text-muted fs-5 mb-0">Explore our extensive collection of high-quality kitchen equipment and laboratory items.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-5 rounded-4 bg-white shadow-sm border-top border-danger border-4 h-100 hover-lift">
                <div class="step-num text-danger fw-bold fs-3 mb-4">02</div>
                <h4 class="fw-bold mb-3">Submit Request</h4>
                <p class="text-muted fs-5 mb-0">Select your items and provide laboratory session details in a few easy steps.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-5 rounded-4 bg-white shadow-sm border-top border-danger border-4 h-100 hover-lift">
                <div class="step-num text-danger fw-bold fs-3 mb-4">03</div>
                <h4 class="fw-bold mb-3">Pick Up & Cook</h4>
                <p class="text-muted fs-5 mb-0">Get your approved equipment and focus on your world-class culinary creation.</p>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div id="features" class="container my-5 py-5 fade-in-up">
    <div class="cta-banner rounded-5 p-5 text-center text-white shadow-lg position-relative overflow-hidden" style="background: linear-gradient(rgba(204, 0, 0, 0.9), rgba(153, 0, 0, 0.9)), url('https://images.unsplash.com/photo-1556910103-1c02745aae4d?auto=format&fit=crop&w=1500&q=80'); background-size: cover; background-position: center;">
        <div class="position-relative z-1 py-4">
            <h2 class="display-4 fw-bold mb-4">Ready to start your lab session?</h2>
            <p class="fs-4 mb-5 opacity-75 mx-auto" style="max-width: 600px;">Join hundreds of students already using KLRS to streamline their culinary education.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="<?= BASE_URL ?>/reserve.php" class="btn btn-light btn-lg px-5 py-3 fw-bold text-danger shadow-sm hover-scale">
                    Go to Request Page <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<?php require('inc/footer.php'); ?>