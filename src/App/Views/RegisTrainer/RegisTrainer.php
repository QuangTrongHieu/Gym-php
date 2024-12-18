<!DOCTYPE html>
<html lang="vi">
<head>
    <?php require_once ROOT_PATH . '/src/App/Views/layouts/header.php'; ?>
    <style>
        .trainer-card {
            transition: transform 0.3s ease;
            margin-bottom: 30px;
        }
        .trainer-card:hover {
            transform: translateY(-5px);
        }
        .trainer-image {
            height: 300px;
            object-fit: cover;
        }
        .trainer-specialties {
            color: #ff4d4d;
        }
        .social-links a {
            color: #1a1a1a;
            margin: 0 10px;
            font-size: 20px;
            transition: color 0.3s ease;
        }
        .social-links a:hover {
            color: #ff4d4d;
        }
    </style>
</head>
<body>
    <div class="container mt-5 mb-5">
        <h1 class="text-center mb-5">Đội Ngũ Huấn Luyện Viên</h1>
        
        <div class="row">
            <?php foreach ($trainers as $trainer): ?>
            <div class="col-md-4">
                <div class="card trainer-card">
                    <img src="<?= $trainer['avatar'] ?? '/gym-php/public/images/trainers/default.jpg' ?>" 
                         class="card-img-top trainer-image" 
                         alt="<?= htmlspecialchars($trainer['fullName']) ?>">
                    
                    <div class="card-body text-center">
                        <h5 class="card-title"><?= htmlspecialchars($trainer['fullName']) ?></h5>
                        <p class="trainer-specialties"><?= htmlspecialchars($trainer['specialties']) ?></p>
                        <p class="card-text"><?= htmlspecialchars($trainer['description']) ?></p>
                        
                        <div class="social-links mt-3">
                            <?php if (!empty($trainer['facebook'])): ?>
                                <a href="<?= $trainer['facebook'] ?>" target="_blank"><i class="fab fa-facebook"></i></a>
                            <?php endif; ?>
                            
                            <?php if (!empty($trainer['instagram'])): ?>
                                <a href="<?= $trainer['instagram'] ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                            <?php endif; ?>
                            
                            <?php if (!empty($trainer['twitter'])): ?>
                                <a href="<?= $trainer['twitter'] ?>" target="_blank"><i class="fab fa-twitter"></i></a>
                            <?php endif; ?>
                        </div>

                        <a href="/gym-php/trainer/<?= $trainer['id'] ?>" class="btn btn-primary mt-3">
                            Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>