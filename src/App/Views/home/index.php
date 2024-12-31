<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-carousel">
        <div class="hero-slide active" style="background-image: url('/gym-php/public/images/banner3.jpg')"></div>
        <div class="hero-slide" style="background-image: url('/gym-php/public/images/banner2.png')"></div>
        <div class="hero-slide" style="background-image: url('/gym-php/public/images/banner4.png')"></div>
    </div>
    <div class="hero-content">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-md-6">
                    <h1 class="display-4 fw-bold text-white mb-4 animate__animated animate__fadeInDown">
                        Phòng Gym Cao Cấp Việt Nam
                    </h1>
                    <p class="lead text-white mb-4 animate__animated animate__fadeInUp">
                        Nơi bạn tìm thấy sức khỏe và sự hoàn hảo
                    </p>
                    <div class="animate__animated animate__fadeInUp animate__delay-1s">
                        <a href="#services" class="btn btn-primary btn-lg me-3">Khám Phá Dịch Vụ</a>
                        <a href="/gym-php/register" class="btn btn-outline-light btn-lg">Đăng Ký Ngay</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- About Section -->
<section class="about py-5">
    <div class="container">
        <h2 class="text-center mb-5">Giới Thiệu</h2>
        <p class="text-center">Phòng gym của chúng tôi cung cấp các dịch vụ tập luyện và chăm sóc sức khỏe hàng đầu, với trang thiết bị hiện đại và đội ngũ huấn luyện viên chuyên nghiệp.</p>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="services py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Dịch Vụ</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="service-card text-center p-4">
                    <i class="fas fa-heartbeat mb-3"></i>
                    <h3>Chăm Sóc Sức Khỏe</h3>
                    <p>Chương trình chăm sóc sức khỏe toàn diện</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card text-center p-4">
                    <i class="fas fa-spa mb-3"></i>
                    <h3>Thư Giãn & Spa</h3>
                    <p>Thư giãn sau giờ tập với dịch vụ spa cao cấp</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card text-center p-4">
                    <i class="fas fa-running mb-3"></i>
                    <h3>Huấn Luyện Cá Nhân</h3>
                    <p>Huấn luyện viên cá nhân giúp bạn đạt mục tiêu</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trainers Section -->
<section class="trainers py-5">
    <div class="container">
        <h2 class="text-center mb-5">Đội Ngũ Huấn Luyện Viên</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="trainer-card text-center p-4">
                    <img src="/path/to/trainer1.jpg" alt="Trainer 1" class="img-fluid rounded-circle mb-3">
                    <h3>Nguyễn Văn A</h3>
                    <p>Chuyên gia thể hình</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="trainer-card text-center p-4">
                    <img src="/path/to/trainer2.jpg" alt="Trainer 2" class="img-fluid rounded-circle mb-3">
                    <h3>Trần Thị B</h3>
                    <p>Chuyên gia dinh dưỡng</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="trainer-card text-center p-4">
                    <img src="/path/to/trainer3.jpg" alt="Trainer 3" class="img-fluid rounded-circle mb-3">
                    <h3>Lê Văn C</h3>
                    <p>Chuyên gia yoga</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Đánh Giá Từ Khách Hàng</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="testimonial-card text-center p-4">
                    <p>"Phòng gym tuyệt vời với dịch vụ chuyên nghiệp!"</p>
                    <h5>- Khách hàng 1</h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card text-center p-4">
                    <p>"Tôi rất hài lòng với các huấn luyện viên ở đây."</p>
                    <h5>- Khách hàng 2</h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card text-center p-4">
                    <p>"Thiết bị hiện đại và không gian thoải mái."</p>
                    <h5>- Khách hàng 3</h5>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .hero-section {
        position: relative;
        overflow: hidden;
        min-height: 100vh;
    }

    .hero-carousel {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .hero-slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        opacity: 0;
        transition: opacity 1s ease-in-out;
    }

    .hero-slide.active {
        opacity: 1;
    }

    .hero-slide::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7));
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .btn-primary {
        background-color: #ff4d4d;
        border-color: #ff4d4d;
        padding: 12px 30px;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 1px;
    }

    .btn-primary:hover {
        background-color: #ff3333;
        border-color: #ff3333;
        transform: translateY(-2px);
    }

    .btn-outline-light {
        padding: 12px 30px;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 1px;
    }

    .btn-outline-light:hover {
        background-color: #fff;
        color: #000;
        transform: translateY(-2px);
    }

    @keyframes zoom {
        from {
            transform: scale(1);
        }
        to {
            transform: scale(1.1);
        }
    }

    .hero-slide.active {
        animation: zoom 20s infinite alternate;
    }

    .service-card,
    .trainer-card,
    .testimonial-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .service-card:hover,
    .trainer-card:hover,
    .testimonial-card:hover {
        transform: translateY(-10px);
    }

    .service-card i,
    .trainer-card img {
        font-size: 2.5rem;
        color: #0d6efd;
    }

    .trainer-card img {
        width: 100px;
        height: 100px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Slider functionality
        const slides = document.querySelectorAll('.hero-slide');
        let currentSlide = 0;

        function nextSlide() {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        }

        // Change slide every 5 seconds
        setInterval(nextSlide, 5000);

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Animation when scrolling
        window.addEventListener('scroll', function() {
            const elements = document.querySelectorAll('.service-card, .trainer-card, .testimonial-card');
            elements.forEach(element => {
                const position = element.getBoundingClientRect().top;
                const screenPosition = window.innerHeight;

                if (position < screenPosition) {
                    element.classList.add('animate__animated', 'animate__fadeInUp');
                }
            });
        });
    });
</script>