<?php if (isset($trainer)): ?>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h1 class="h3 mb-0">Xóa Huấn luyện viên</h1>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?= $_SESSION['error'];
                        unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    Bạn có chắc chắn muốn xóa huấn luyện viên <strong><?= htmlspecialchars($trainer['fullName'] ?? 'Không xác định') ?></strong> không?
                    <br>
                    <small class="text-muted">Hành động này không thể hoàn tác. Tất cả dữ liệu liên quan sẽ bị xóa vĩnh viễn.</small>
                </div>

                <form action="/gym-php/admin/trainer/delete/<?= $trainer['id'] ?>" method="POST" class="delete-form">
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
<?php else: ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        Không tìm thấy thông tin huấn luyện viên
    </div>
<?php endif; ?>

<style>
    .delete-form {
        margin-bottom: 0;
    }

    .card-header {
        border-bottom: none;
    }

    .alert {
        margin-bottom: 1.5rem;
    }

    .btn {
        padding: 0.5rem 1rem;
    }

    .btn i {
        margin-right: 0.5rem;
    }
</style>