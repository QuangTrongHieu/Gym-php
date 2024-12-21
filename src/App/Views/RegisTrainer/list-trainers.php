<!DOCTYPE html>
<html lang="vi">
<head>
    <style>
        .trainer-grid {
            padding: 50px 0;
        }
        .trainer-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            overflow: hidden;
            transition: transform 0.3s;
        }
        .trainer-card:hover {
            transform: translateY(-5px);
        }
        .trainer-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }
        .trainer-info {
            padding: 20px;
        }
        .trainer-name {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #333;
        }
        .trainer-specialty {
            color: #ff4d4d;
            font-size: 1rem;
            margin-bottom: 15px;
        }
        .trainer-stats {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-top: 1px solid #eee;
            text-align: center;
        }
        .stat-item {
            flex: 1;
        }
        .stat-value {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
        }
        .stat-label {
            font-size: 0.8rem;
            color: #666;
        }
        .social-links {
            margin-top: 15px;
            text-align: center;
        }
        .social-links a {
            color: #666;
            margin: 0 10px;
            font-size: 1.2rem;
            transition: color 0.3s;
        }
        .social-links a:hover {
            color: #ff4d4d;
        }
    </style>
</head>
<body>
    <div class="container trainer-grid">
        <h1 class="text-center mb-5">Đội ngũ Huấn luyện viên</h1>

        <?php if (!empty($trainers)): ?>
            <div class="row">
                <?php foreach ($trainers as $trainer): ?>
                    <div class="col-md-4">
                        <div class="trainer-card">
                            <img src="<?= !empty($trainer['avatar']) ? '/gym-php/public/images/trainers/' . $trainer['avatar'] : '/gym-php/public/images/trainers/default.jpg' ?>" 
                                 class="trainer-image" 
                                 alt="<?= htmlspecialchars($trainer['fullName'] ?? '') ?>">
                            
                            <div class="trainer-info">
                                <h3 class="trainer-name"><?= htmlspecialchars($trainer['fullName'] ?? '') ?></h3>
                                <div class="trainer-specialty">
                                    <i class="fas fa-dumbbell"></i> 
                                    <strong>Chuyên môn:</strong> <?= htmlspecialchars($trainer['specialization'] ?? 'Chưa cập nhật') ?>
                                </div>
                                
                                <div class="trainer-certification mb-2">
                                    <i class="fas fa-certificate"></i>
                                    <strong>Chứng chỉ:</strong> <?= htmlspecialchars($trainer['certification'] ?? 'Chưa cập nhật') ?>
                                </div>
                                
                                <div class="trainer-stats">
                                    <div class="stat-item">
                                        <div class="stat-value">
                                            <?php 
                                            $experience = intval($trainer['experience'] ?? 0);
                                            if ($experience == 0) {
                                                echo 'Mới';
                                            } else {
                                                echo $experience . '+';
                                            }
                                            ?>
                                        </div>
                                        <div class="stat-label">Năm kinh nghiệm</div>
                                    </div>
                                </div>

                                <div class="social-links">
                                    <?php if (!empty($trainer['facebook'])): ?>
                                        <a href="<?= htmlspecialchars($trainer['facebook']) ?>" target="_blank">
                                            <i class="fab fa-facebook"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($trainer['instagram'])): ?>
                                        <a href="<?= htmlspecialchars($trainer['instagram']) ?>" target="_blank">
                                            <i class="fab fa-instagram"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>

                                <div class="text-center mt-3">
                                    <a href="/gym-php/trainer/profile/<?= $trainer['id'] ?? '' ?>" 
                                       class="btn btn-primary">
                                        <i class="fas fa-info-circle"></i> Xem chi tiết
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                Hiện chưa có huấn luyện viên nào.
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>