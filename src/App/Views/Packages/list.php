
<div class="container py-5">
    <h1 class="text-center mb-5"><?= $title ?></h1>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($packages as $package): ?>
            <div class="col">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($package['name']) ?></h5>
                        <p class="card-text">
                            <strong>Mô tả:</strong> <?= htmlspecialchars($package['description']) ?><br>
                            <strong>Thời hạn:</strong> <?= htmlspecialchars($package['duration']) ?> tháng<br>
                            <strong>Giá:</strong> <?= number_format($package['price'], 0, ',', '.') ?> VNĐ
                        </p>
                        
                        <?php if (isset($_SESSION['user'])): ?>
                            <a href="/gym-php/membership/register/<?= $package['id'] ?>" class="btn btn-primary">Đăng ký ngay</a>
                        <?php else: ?>
                            <a href="/gym-php/login" class="btn btn-secondary">Đăng nhập để đăng ký</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
