<?php if(isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<form action="<?= base_url('admin/member/update/'.$member['id']) ?>" method="POST" class="needs-validation" novalidate>
    <input type="hidden" name="id" value="<?= $member['id'] ?>">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="fullName" class="form-label">Họ tên</label>
            <input type="text" class="form-control" id="fullName" name="fullName" 
                value="<?= htmlspecialchars($member['fullName'] ?? '') ?>" required>
            <div class="invalid-feedback">
                Vui lòng nhập họ tên
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" 
                value="<?= htmlspecialchars($member['email'] ?? '') ?>" required>
            <div class="invalid-feedback">
                Vui lòng nhập email hợp lệ
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="tel" class="form-control" id="phone" name="phone" 
                value="<?= htmlspecialchars($member['phone'] ?? '') ?>" 
                pattern="[0-9]{10}" required>
            <div class="invalid-feedback">
                Vui lòng nhập số điện thoại hợp lệ (10 số)
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <label for="package" class="form-label">Gói hội viên</label>
            <select class="form-select" id="package" name="package" required>
                <option value="">Chọn gói hội viên</option>
                <?php foreach ($packages as $package): ?>
                <option value="<?= $package['id'] ?>" 
                    <?= ($member['package'] ?? '') == $package['id'] ? 'selected' : '' ?>
                    data-duration="<?= $package['duration'] ?>">
                    <?= htmlspecialchars($package['name']) ?> - 
                    <?= number_format($package['price']) ?> VNĐ
                </option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback">
                Vui lòng chọn gói hội viên
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="startDate" class="form-label">Ngày bắt đầu</label>
            <input type="date" class="form-control" id="startDate" name="startDate" 
                value="<?= htmlspecialchars($member['startDate'] ?? '') ?>" required>
            <div class="invalid-feedback">
                Vui lòng chọn ngày bắt đầu
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <label for="endDate" class="form-label">Ngày kết thúc</label>
            <input type="date" class="form-control" id="endDate" name="endDate" 
                value="<?= htmlspecialchars($member['endDate'] ?? '') ?>" required>
            <div class="invalid-feedback">
                Vui lòng chọn ngày kết thúc
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Trạng thái</label>
        <select class="form-select" id="status" name="status" required>
            <option value="active" <?= ($member['status'] ?? '') == 'active' ? 'selected' : '' ?>>
                Hoạt động
            </option>
            <option value="inactive" <?= ($member['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>
                Không hoạt động
            </option>
        </select>
        <div class="invalid-feedback">
            Vui lòng chọn trạng thái
        </div>
    </div>

    <div class="text-end">
        <a href="<?= base_url('admin/member') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Lưu thay đổi
        </button>
    </div>
</form>

<script>
// Form validation
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
})()

// Date validation
const startDate = document.getElementById('startDate');
const endDate = document.getElementById('endDate');
const packageSelect = document.getElementById('package');

if (startDate && endDate && packageSelect) {
    // Update end date when start date changes
    startDate.addEventListener('change', function() {
        if (this.value) {
            const selectedOption = packageSelect.options[packageSelect.selectedIndex];
            const duration = selectedOption.dataset.duration || 30; // Default to 30 days
            
            const start = new Date(this.value);
            const end = new Date(start);
            end.setDate(end.getDate() + parseInt(duration));
            
            endDate.value = end.toISOString().split('T')[0];
        }
    });
    
    // Validate end date is after start date
    endDate.addEventListener('change', function() {
        if (startDate.value && this.value) {
            const start = new Date(startDate.value);
            const end = new Date(this.value);
            
            if (end <= start) {
                this.setCustomValidity('Ngày kết thúc phải sau ngày bắt đầu');
            } else {
                this.setCustomValidity('');
            }
        }
    });
    
    // Update end date when package changes
    packageSelect.addEventListener('change', function() {
        if (startDate.value) {
            const selectedOption = this.options[this.selectedIndex];
            const duration = selectedOption.dataset.duration || 30;
            
            const start = new Date(startDate.value);
            const end = new Date(start);
            end.setDate(end.getDate() + parseInt(duration));
            
            endDate.value = end.toISOString().split('T')[0];
        }
    });
}
</script>