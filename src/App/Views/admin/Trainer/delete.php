<?php if (!empty($trainers)): ?>
    <?php foreach ($trainers as $trainer): ?>
        <div class="container mt-4">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h1 class="h3 mb-0">Xóa Huấn luyện viên</h1>
                </div>
                <div class="card-body">
                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Bạn có chắc chắn muốn xóa huấn luyện viên <strong><?= htmlspecialchars($trainer['fullName']) ?></strong> không?
                        <br>
                        <small class="text-muted">Hành động này sẽ đánh dấu huấn luyện viên là không hoạt động.</small>
                    </div>

                    <form action="/gym-php/admin/trainer/destroy/<?= $trainer['id'] ?>" method="POST">
                        <input type="hidden" name="id" value="<?= $trainer['id'] ?>">
                        <div class="d-flex gap-2">
                            <a href="/gym-php/admin/trainer" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash-alt"></i> Xác nhận xóa
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
<?php endif; ?>