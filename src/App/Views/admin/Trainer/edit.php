<?php if (isset($trainer)): ?>
    <div class="container-fluid px-4">
        <!-- <h1 class="mt-4">Sửa thông tin huấn luyện viên: <?= htmlspecialchars($trainer['fullName']) ?></h1> -->
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
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
                            <form action="/gym-php/admin/trainer/edit/<?= $item['id'] ?>" method="POST">
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
                                <div class="mb-3">
                                    <label class="form-label">Chuyên môn</label>
                                    <textarea name="specialization" class="form-control" required><?= htmlspecialchars($item['specialization']) ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Kinh nghiệm (năm)</label>
                                    <input type="number" name="experience" class="form-control" value="<?= htmlspecialchars($item['experience']) ?>" required>
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
                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $trainer['id'] ?>">Sửa thông tin huấn luyện viên</button> -->
    </div>
<?php else: ?>
    <div class="alert alert-danger">
    </div>
<?php endif; ?>