<div class="profile-content">
    <h2 class="fs-4 fw-bold mb-3">Hồ Sơ Của Tôi</h2>
    <p class="text-muted mb-4">Quản lý thông tin hồ sơ để bảo mật tài khoản</p>

    <div class="row">
        <div class="col-md-8">
            <form id="profileForm" action="/gym-php/user/updateProfile" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $this->csrf_token ?>" />
                <div class="mb-3">
                    <label class="form-label">Tên đăng nhập</label>
                    <input type="text"
                        class="form-control"
                        name="username"
                        value="<?= isset($username) ? htmlspecialchars($username) : '' ?>"
                        readonly />
                </div>

                <div class="mb-3">
                    <label class="form-label">Họ và tên</label>
                    <input type="text"
                        class="form-control"
                        name="fullName"
                        value="<?= isset($fullName) ? htmlspecialchars($fullName) : '' ?>"
                        required />
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <div class="input-group">
                        <input type="email"
                            class="form-control"
                            name="email"
                            value="<?= isset($email) ? htmlspecialchars($email) : '' ?>"
                            readonly />
                        <button type="button"
                            class="btn btn-outline-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#changeEmailModal">
                            Thay đổi
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <div class="input-group">
                        <input type="tel"
                            class="form-control"
                            name="phone"
                            value="<?= isset($phone) ? htmlspecialchars($phone) : '' ?>"
                            readonly />
                        <button type="button"
                            class="btn btn-outline-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#changePhoneModal">
                            Thay đổi
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Giới tính</label>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sex" value="Male"
                                <?= (isset($sex) && $sex == 'Male') ? 'checked' : '' ?> />
                            <label class="form-check-label">Nam</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sex" value="Female"
                                <?= (isset($sex) && $sex == 'Female') ? 'checked' : '' ?> />
                            <label class="form-check-label">Nữ</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sex" value="Other"
                                <?= (isset($sex) && $sex == 'Other') ? 'checked' : '' ?> />
                            <label class="form-check-label">Khác</label>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Ngày sinh</label>
                    <input type="date"
                        class="form-control"
                        name="dateOfBirth"
                        value="<?= isset($dateOfBirth) ? htmlspecialchars($dateOfBirth) : '' ?>"
                        required />
                </div>

                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            </form>
        </div>

        <div class="col-md-4">
            <div class="text-center">
                <div class="mb-3">
                    <img src="<?= $avatarUrl ?>"
                        alt="Avatar"
                        class="rounded-circle img-thumbnail"
                        style="width: 200px; height: 200px; object-fit: cover;" />
                </div>

                <form id="avatarForm" action="/gym-php/user/updateAvatar" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= $this->csrf_token ?>" />
                    <input type="file"
                        name="avatar"
                        id="avatarInput"
                        class="form-control mb-2"
                        accept=".jpg,.jpeg,.png,.gif,.webp"
                        hidden />
                    <button type="button"
                        class="btn btn-outline-secondary mb-2"
                        onclick="document.getElementById('avatarInput').click()">
                        Chọn ảnh
                    </button>
                    <div class="text-muted small">
                        <p class="mb-1">Dung lượng file tối đa 5 MB</p>
                        <p class="mb-0">Định dạng: JPG, PNG, GIF, WEBP</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('avatarInput').addEventListener('change', async function(e) {
        const file = this.files[0];
        if (!file) return;

        const form = document.getElementById('avatarForm');
        const submitBtn = form.querySelector('button[type="button"]');
        const preview = document.querySelector('.rounded-circle.img-thumbnail');
        const formData = new FormData(form);

        try {
            // File validations
            if (file.size > 5 * 1024 * 1024) {
                throw new Error('Kích thước file không được vượt quá 5MB');
            }

            const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                throw new Error('Chỉ chấp nhận file ảnh định dạng: JPG, PNG, GIF, WEBP');
            }

            // Preview image before upload
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang tải lên...';

            const response = await fetch('/gym-php/user/updateAvatar', {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();

            if (!result.success) {
                throw new Error(result.error || 'Có lỗi xảy ra khi cập nhật ảnh đại diện');
            }

            // Update preview with new image URL
            preview.src = result.avatarUrl;

            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: 'Cập nhật ảnh đại diện thành công'
            });

        } catch (error) {
            console.error('Upload error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: error.message || 'Có lỗi xảy ra khi cập nhật ảnh đại diện'
            });

            // Reset preview to original
            preview.src = preview.getAttribute('data-original');
        } finally {
            // Reset form and button state
            form.reset();
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Chọn ảnh';
        }
    });

    document.getElementById('profileForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                credentials: 'same-origin'
            });

            const data = await response.json();

            if (data.success) {
                alert('Cập nhật thông tin thành công!');
                window.location.reload();
            } else {
                alert(data.error || 'Có lỗi xảy ra khi cập nhật thông tin');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi cập nhật thông tin');
        }
    });
</script>