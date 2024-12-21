<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-5">
    <h1 class="text-center mb-5"><?= $title ?></h1>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($trainers as $trainer): ?>
            <div class="col">
                <div class="card h-100">
                    <?php if (!empty($trainer['avatar'])): ?>
                        <img src="/gym-php/<?= htmlspecialchars($trainer['avatar']) ?>" class="card-img-top" alt="<?= htmlspecialchars($trainer['fullName']) ?>">
                    <?php else: ?>
                        <img src="/gym-php/public/assets/images/default-avatar.png" class="card-img-top" alt="Default Avatar">
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($trainer['fullName']) ?></h5>
                        <p class="card-text">
                            <strong>Chuyên môn:</strong> <?= htmlspecialchars($trainer['specialization']) ?><br>
                            <strong>Kinh nghiệm:</strong> <?= htmlspecialchars($trainer['experience']) ?> năm<br>
                            <strong>Chứng chỉ:</strong> <?= htmlspecialchars($trainer['certification']) ?>
                        </p>
                        
                        <?php if (isset($_SESSION['user'])): ?>
                            <a href="/gym-php/trainer/register/<?= $trainer['id'] ?>" class="btn btn-primary">Đăng ký huấn luyện</a>
                        <?php else: ?>
                            <a href="/gym-php/login" class="btn btn-secondary">Đăng nhập để đăng ký</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>