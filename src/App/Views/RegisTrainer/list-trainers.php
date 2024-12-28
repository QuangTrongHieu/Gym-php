
<div class="container trainer-grid">
    <h1 class="text-center mb-5">Đội ngũ Huấn luyện viên</h1>

    <?php if (!empty($trainers)): ?>
        <div class="row g-4">
            <?php foreach ($trainers as $trainer): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="trainer-card">
                        <div class="trainer-image-container">
                            <?php
                            $avatar = $trainer['avatar'] ?? 'default.jpg';
                            $avatarFullPath = ROOT_PATH . '/public/uploads/trainers/' . $avatar;
                            $avatarUrl = file_exists($avatarFullPath) 
                                ? '/gym-php/public/uploads/trainers/' . $avatar 
                                : '/gym-php/public/assets/images/default-avatar.png';
                            ?>
                            <img src="<?= htmlspecialchars($avatarUrl) ?>" 
                                 class="trainer-image" 
                                 alt="<?= htmlspecialchars($trainer['fullName'] ?? 'Huấn luyện viên') ?>"
                                 loading="lazy">
                        </div>
                        
                        <div class="trainer-info">
                            <h3 class="trainer-name text-center"><?= htmlspecialchars($trainer['fullName'] ?? '') ?></h3>
                            <div class="trainer-specialty text-center">
                                <i class="fas fa-dumbbell"></i> 
                                <?= htmlspecialchars($trainer['specialization'] ?? 'Chuyên môn chưa cập nhật') ?>
                            </div>
                            
                            <div class="trainer-stats">
                                <div class="stat-item">
                                    <div class="stat-value">
                                        <?= intval($trainer['experience'] ?? 0) > 0 ? intval($trainer['experience']) . '+' : 'Mới' ?>
                                    </div>
                                    <div class="stat-label">Năm kinh nghiệm</div>
                                </div>
                                <?php if (!empty($trainer['certification'])): ?>
                                <div class="stat-item">
                                    <div class="stat-value">
                                        <i class="fas fa-certificate"></i>
                                    </div>
                                    <div class="stat-label">Chứng chỉ</div>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="social-links">
                                <?php if (!empty($trainer['facebook'])): ?>
                                    <a href="<?= htmlspecialchars($trainer['facebook']) ?>" 
                                       target="_blank" 
                                       title="Facebook"
                                       rel="noopener noreferrer">
                                        <i class="fab fa-facebook"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if (!empty($trainer['instagram'])): ?>
                                    <a href="<?= htmlspecialchars($trainer['instagram']) ?>" 
                                       target="_blank"
                                       title="Instagram"
                                       rel="noopener noreferrer">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                <?php endif; ?>
                            </div>

                            <div class="text-center mt-3">
                                <a href="/gym-php/trainer/trainerDetail/<?= $trainer['id'] ?? '' ?>" 
                                   class="btn btn-primary btn-view-details">
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
            <i class="fas fa-info-circle me-2"></i>
            Hiện chưa có huấn luyện viên nào.
        </div>
    <?php endif; ?>
</div>

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
        transition: all 0.3s ease;
        height: 100%;
        border: 1px solid rgba(0,0,0,0.1);
    }
    .trainer-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    }
    .trainer-image-container {
        position: relative;
        width: 100%;
        height: 300px;
        overflow: hidden;
        background: #f8f9fa;
    }
    .trainer-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .trainer-card:hover .trainer-image {
        transform: scale(1.05);
    }
    .trainer-info {
        padding: 20px;
        background: white;
    }
    .trainer-name {
        font-size: 1.5rem;
        margin-bottom: 10px;
        color: #333;
        font-weight: 600;
    }
    .trainer-specialty {
        color: #ff4d4d;
        font-size: 1rem;
        margin-bottom: 15px;
        line-height: 1.4;
    }
    .trainer-stats {
        display: flex;
        justify-content: center;
        gap: 20px;
        padding: 15px 0;
        border-top: 1px solid #eee;
        text-align: center;
    }
    .stat-item {
        flex: 1;
        max-width: 120px;
    }
    .stat-value {
        font-size: 1.2rem;
        font-weight: bold;
        color: #333;
    }
    .stat-label {
        font-size: 0.9rem;
        color: #666;
        margin-top: 5px;
    }
    .social-links {
        margin: 15px 0;
        text-align: center;
    }
    .social-links a {
        color: #666;
        margin: 0 10px;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        display: inline-block;
    }
    .social-links a:hover {
        color: #ff4d4d;
        transform: scale(1.2);
    }
    .btn-view-details {
        width: 100%;
        padding: 10px 20px;
        border-radius: 25px;
        transition: all 0.3s ease;
        background-color: #007bff;
        border: none;
        font-weight: 500;
    }
    .btn-view-details:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
    }
</style>