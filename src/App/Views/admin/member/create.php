<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm Hội viên Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="/gym-php/admin/member/create" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Ảnh đại diện</label>
                        <input type="file" name="avatar" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Họ tên</label>
                        <input type="text" name="fullName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ngày sinh</label>
                        <input type="date" name="dateOfBirth" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Giới tính</label>
                        <select name="sex" class="form-control" required>
                            <option value="Male">Nam</option>
                            <option value="Female">Nữ</option>
                            <option value="Other">Khác</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="tel" name="phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gói tập</label>
                        <select name="package_id" class="form-select">
                            <option value="">Chọn gói tập</option>
                            <?php foreach($packages as $package): ?>
                                <option value="<?= $package['id'] ?>">
                                    <?= htmlspecialchars($package['name']) ?> 
                                    (<?= $package['duration'] ?> tháng - <?= number_format($package['price'], 0, ',', '.') ?> VNĐ)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ngày bắt đầu</label>
                        <input type="date" name="startDate" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ngày kết thúc</label>
                        <input type="date" name="endDate" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Thêm mới</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
