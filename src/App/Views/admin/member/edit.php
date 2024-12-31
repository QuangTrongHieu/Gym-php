<?php if (isset($members)): ?>
    <div class="container-fluid px-4">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php foreach ($members as $item): ?>
            <div class="modal fade" id="editModal<?= $item['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Sửa thông tin hội viên</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form action="/gym-php/admin/member/edit/<?= $item['id'] ?>" method="POST" enctype="multipart/form-data">
                                <div class="mb-3 text-center">
                                    <?php
                                    if (!empty($item['avatar']) && $item['avatar'] !== 'default.jpg') {
                                        $avatarPath = "/gym-php/public/uploads/members/avatars/" . $item['avatar'];
                                    } else {
                                        $avatarPath = $defaultAvatarBase64;
                                    }
                                    ?>
                                    <img src="<?= htmlspecialchars($avatarPath) ?>"
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
                                    <label class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($item['username']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Họ tên</label>
                                    <input type="text" name="fullName" class="form-control" value="<?= htmlspecialchars($item['fullName']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($item['email']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password (để trống nếu không thay đổi)</label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ngày sinh</label>
                                    <input type="date" name="dateOfBirth" class="form-control" value="<?= htmlspecialchars($item['dateOfBirth']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Giới tính</label>
                                    <select name="sex" class="form-control" required>
                                        <option value="Male" <?= $item['sex'] == 'Male' ? 'selected' : '' ?>>Nam</option>
                                        <option value="Female" <?= $item['sex'] == 'Female' ? 'selected' : '' ?>>Nữ</option>
                                        <option value="Other" <?= $item['sex'] == 'Other' ? 'selected' : '' ?>>Khác</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($item['phone']) ?>" required>
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
        Không tìm thấy thông tin hội viên
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