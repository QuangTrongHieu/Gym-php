<?php
// Kiểm tra session
$isLoggedIn = isset($_SESSION['user']);
$userRole = $_SESSION['user']['erole'] ?? '';
$userId = $_SESSION['user']['id'] ?? null;

if (!$isLoggedIn) {
    $_SESSION['error'] = 'Vui lòng đăng nhập để đăng ký gói tập';
    header('Location: /gym-php/login');
    exit;
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-dumbbell me-2"></i>Đăng ký gói tập
                    </h4>
                </div>
                <div class="card-body">
                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="/gym-php/membership/register/<?= $package['id'] ?>" method="POST" id="registerForm">
                        <div class="mb-4">
                            <h5 class="card-title">
                                <i class="fas fa-star text-warning me-2"></i>
                                <?= htmlspecialchars($package['name']) ?>
                            </h5>
                            <p class="text-muted">
                                <i class="fas fa-info-circle me-2"></i>
                                <?= htmlspecialchars($package['description']) ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                                    <strong>Thời hạn:</strong> <?= htmlspecialchars($package['duration']) ?> tháng
                                </span>
                                <span class="text-danger fw-bold">
                                    <i class="fas fa-tags me-2"></i>
                                    <?= number_format($package['price'], 0, ',', '.') ?> VNĐ
                                </span>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <label for="start_date" class="form-label">
                                <i class="fas fa-calendar me-2"></i>Ngày bắt đầu
                            </label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                   min="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="end_date" class="form-label">
                                <i class="fas fa-calendar-check me-2"></i>Ngày kết thúc
                            </label>
                            <input type="date" class="form-control" id="end_date" name="end_date" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="payment_method" class="form-label">
                                <i class="fas fa-money-bill-wave me-2"></i>Phương thức thanh toán
                            </label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="">Chọn phương thức thanh toán</option>
                                <option value="cash">Tiền mặt</option>
                                <option value="transfer">Chuyển khoản</option>
                                <option value="card">Thẻ tín dụng</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check-circle me-2"></i>Xác nhận đăng ký
                            </button>
                            <a href="/gym-php/packages" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const packageDuration = <?= $package['duration'] ?>;

    startDateInput.addEventListener('change', function() {
        if (this.value) {
            const startDate = new Date(this.value);
            const endDate = new Date(startDate);
            endDate.setMonth(endDate.getMonth() + packageDuration);
            
            // Format the date as YYYY-MM-DD
            const formattedDate = endDate.toISOString().split('T')[0];
            endDateInput.value = formattedDate;
        } else {
            endDateInput.value = '';
        }
    });

    // Set minimum date for start date
    const today = new Date();
    const formattedToday = today.toISOString().split('T')[0];
    startDateInput.min = formattedToday;
});
</script>

<style>
.card {
    border: none;
    border-radius: 15px;
}
.card-header {
    border-radius: 15px 15px 0 0 !important;
}
.form-control, .form-select {
    padding: 0.75rem;
}
.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}
.btn {
    padding: 0.75rem 1.25rem;
    font-weight: 500;
}
</style>
