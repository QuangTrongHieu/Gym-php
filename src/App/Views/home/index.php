<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold text-white mb-4">Xây Dựng Cơ Thể Hoàn Hảo</h1>
                <p class="lead text-white mb-4">Tập luyện với các huấn luyện viên chuyên nghiệp và thiết bị hiện đại</p>
                <a href="#packages" class="btn btn-primary btn-lg">Xem Gói Tập</a>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<section class="features py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card text-center p-4">
                    <i class="fas fa-dumbbell mb-3"></i>
                    <h3>Thiết Bị Hiện Đại</h3>
                    <p>Trang bị đầy đủ máy móc cao cấp từ các thương hiệu hàng đầu</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center p-4">
                    <i class="fas fa-user-friends mb-3"></i>
                    <h3>HLV Chuyên Nghiệp</h3>
                    <p>Đội ngũ huấn luyện viên giàu kinh nghiệm, tận tâm</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center p-4">
                    <i class="fas fa-clock mb-3"></i>
                    <h3>Mở Cửa 24/7</h3>
                    <p>Linh hoạt thời gian tập luyện theo lịch trình của bạn</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Packages Section -->
<section id="packages" class="packages py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Gói Tập Luyện</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="package-card">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h3 class="card-title">Gói Cơ Bản</h3>
                            <div class="price mb-3">
                                <span class="h2">499K</span> / tháng
                            </div>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success"></i> Tập không giới hạn thời gian</li>
                                <li><i class="fas fa-check text-success"></i> Tủ đồ miễn phí</li>
                                <li><i class="fas fa-check text-success"></i> Phòng tắm</li>
                            </ul>
                            <a href="#" class="btn btn-primary">Đăng Ký Ngay</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Thêm các gói tập khác tương tự -->
        </div>
    </div>
</section>

<style>
    .card {
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, .125);
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
    }

    .card img {
        transition: all 0.3s ease;
    }

    .card:hover img {
        transform: scale(1.02);
    }

    .hero-section {
        background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('/gym-php/public/assets/images/gym-hero.jpg');
        background-size: cover;
        background-position: center;
        min-height: 100vh;
    }

    .feature-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-10px);
    }

    .feature-card i {
        font-size: 2.5rem;
        color: #0d6efd;
    }

    .package-card {
        transition: transform 0.3s ease;
    }

    .package-card:hover {
        transform: translateY(-10px);
    }

    .price {
        color: #0d6efd;
    }

    .package-card .card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .package-card ul li {
        padding: 8px 0;
    }

    @media (max-width: 768px) {
        .hero-section {
            text-align: center;
        }
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
        const features = document.querySelectorAll('.feature-card');
        features.forEach(feature => {
            const featurePosition = feature.getBoundingClientRect().top;
            const screenPosition = window.innerHeight;
            
            if(featurePosition < screenPosition) {
                feature.classList.add('animate__animated', 'animate__fadeInUp');
            }
        });
    });
});
</script>