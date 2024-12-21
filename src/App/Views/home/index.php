<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold text-white mb-4">Phòng Gym Cao Cấp Việt Nam</h1>
                <p class="lead text-white mb-4">Nơi bạn tìm thấy sức khỏe và sự hoàn hảo</p>
                <a href="#services" class="btn btn-primary btn-lg">Khám Phá Dịch Vụ</a>
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
        background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7));
        background-size: cover;
        background-position: center;
        min-height: 100vh;
    }

    .service-card, .trainer-card, .testimonial-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .service-card:hover, .trainer-card:hover, .testimonial-card:hover {
        transform: translateY(-10px);
    }

    .service-card i, .trainer-card img {
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
    // Smooth scroll cho các anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Animation khi scroll
    window.addEventListener('scroll', function() {
        const elements = document.querySelectorAll('.service-card, .trainer-card, .testimonial-card');
        elements.forEach(element => {
            const position = element.getBoundingClientRect().top;
            const screenPosition = window.innerHeight;
            
            if(position < screenPosition) {
                element.classList.add('animate__animated', 'animate__fadeInUp');
            }
        });
    });
});
</script>