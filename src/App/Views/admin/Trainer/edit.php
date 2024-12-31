<?php if (isset($trainer)): ?>
    <div class="container-fluid px-4">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php foreach ($trainer as $item): ?>
            <div class="modal fade" id="editModal<?= $item['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Sửa thông tin huấn luyện viên</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form action="/gym-php/admin/trainer/edit/<?= $item['id'] ?>" method="POST" enctype="multipart/form-data">
                                <div class="mb-3 text-center">
                                    <?php
                                    $avatarPath = '/gym-php/public/uploads/trainers/' . ($item['avatar'] ?? 'default.jpg');
                                    $defaultPath = '/gym-php/public/assets/images/default-avatar.png';
                                    $fullPath = ROOT_PATH . '/public/uploads/trainers/' . ($item['avatar'] ?? 'default.jpg');
                                    $imgSrc = file_exists($fullPath) ? $avatarPath : $defaultPath;
                                    ?>
                                    <img src="<?= htmlspecialchars($imgSrc) ?>"
                                        alt="Avatar"
                                        class="rounded-circle mb-3"
                                        style="width: 150px; height: 150px; object-fit: cover;"
                                        id="avatarPreview<?= $item['id'] ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Ảnh đại diện</label>
                                        <input type="file"
                                            name="avatar"
                                            class="form-control"
                                            accept="image/*"
                                            onchange="previewImage(this, 'avatarPreview<?= $item['id'] ?>')">
                                        <div class="form-text">Để trống nếu không muốn thay đổi ảnh</div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Họ tên</label>
                                    <input type="text" name="fullName" class="form-control" value="<?= htmlspecialchars($item['fullName']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($item['username']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($item['email']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mật khẩu mới (để trống nếu không đổi)</label>
                                    <input type="password" name="password" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Ngày sinh</label>
                                    <input type="date" name="dateOfBirth" class="form-control" value="<?= htmlspecialchars($item['dateOfBirth']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Giới tính</label>
                                    <select name="sex" class="form-select" required>
                                        <option value="Nam" <?= $item['sex'] === 'Nam' ? 'selected' : '' ?>>Nam</option>
                                        <option value="Nữ" <?= $item['sex'] === 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($item['phone']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Chuyên môn</label>
                                    <input type="text" name="specialization" class="form-control" value="<?= htmlspecialchars($item['specialization']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Kinh nghiệm</label>
                                    <textarea name="experience" class="form-control" required><?= htmlspecialchars($item['experience']) ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Chứng chỉ</label>
                                    <textarea name="certification" class="form-control" required><?= htmlspecialchars($item['certification']) ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Lương</label>
                                    <input type="number" name="salary" class="form-control" value="<?= htmlspecialchars($item['salary']) ?>" required>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="alert alert-danger">
        Không tìm thấy thông tin huấn luyện viên
    </div>
<?php endif; ?>

<script>
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        const file = input.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }
</script>