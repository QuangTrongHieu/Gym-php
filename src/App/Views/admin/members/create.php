            <title>Thêm Hội viên</title>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h1 class="h3 mb-0">Thêm Hội viên Mới</h1>
            </div>
            <div class="card-body">
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <form action="/gym-php/admin/member/create" method="POST" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fullName" class="form-label">Họ tên</label>
                            <input type="text" class="form-control" id="fullName" name="fullName" required>
                            <div class="invalid-feedback">
                                Vui lòng nhập họ tên
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback">
                                Vui lòng nhập email hợp lệ
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" id="phone" name="phone" pattern="[0-9]{10}" required>
                            <div class="invalid-feedback">
                                Vui lòng nhập số điện thoại hợp lệ (10 số)
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="package_id" class="form-label">Gói hội viên</label>
                            <select class="form-select" id="package_id" name="package_id" required>
                                <option value="">Chọn gói hội viên</option>
                                <?php foreach ($packages as $package): ?>
                                <option value="<?= $package['id'] ?>" 
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
                            <input type="date" class="form-control" id="startDate" name="startDate" required>
                            <div class="invalid-feedback">
                                Vui lòng chọn ngày bắt đầu
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="endDate" class="form-label">Ngày kết thúc</label>
                            <input type="date" class="form-control" id="endDate" name="endDate" required>
                            <div class="invalid-feedback">
                                Vui lòng chọn ngày kết thúc
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="/gym-php/admin/member" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Thêm mới
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

    // Auto calculate end date based on package duration
    document.getElementById('package_id').addEventListener('change', function() {
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        const selectedOption = this.options[this.selectedIndex];
        const duration = selectedOption.getAttribute('data-duration');
        
        if (startDateInput.value && duration) {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(startDate);
            endDate.setMonth(endDate.getMonth() + parseInt(duration));
            endDateInput.value = endDate.toISOString().split('T')[0];
        }
    });

    // Update end date when start date changes
    document.getElementById('startDate').addEventListener('change', function() {
        const packageSelect = document.getElementById('package_id');
        const selectedOption = packageSelect.options[packageSelect.selectedIndex];
        const duration = selectedOption.getAttribute('data-duration');
        const endDateInput = document.getElementById('endDate');
        
        if (this.value && duration) {
            const startDate = new Date(this.value);
            const endDate = new Date(startDate);
            endDate.setMonth(endDate.getMonth() + parseInt(duration));
            endDateInput.value = endDate.toISOString().split('T')[0];
        }
    });

    // Date validation
    const startDate = document.getElementById('startDate');
    const endDate = document.getElementById('endDate');

    endDate.addEventListener('change', function() {
        if (startDate.value && this.value) {
            if (new Date(this.value) <= new Date(startDate.value)) {
                alert('Ngày kết thúc phải sau ngày bắt đầu');
                this.value = '';
            }
        }
        startDate.max = this.value;
    });

    startDate.addEventListener('change', function() {
        if (endDate.value && this.value) {
            if (new Date(endDate.value) <= new Date(this.value)) {
                alert('Ngày bắt đầu phải trước ngày kết thúc');
                this.value = '';
            }
        }
        if (this.value) {
            endDate.min = this.value;
        }
    });
    </script>
</body>