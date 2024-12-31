<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?= $_SESSION['error']; ?>
        <?php unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="container">
    <h1>Sửa thông tin hội viên</h1>
    <form action="/gym-php/admin/member-management/update/<?= $member['id'] ?>" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Ảnh đại diện</label>
            <input type="file" name="avatar" class="form-control" accept="image/*">
            <?php if (!empty($member['avatar'])): ?>
                <div class="mt-2">
                    <img src="/gym-php/public/uploads/members/<?= htmlspecialchars($member['avatar']) ?>" 
                         alt="Current avatar" 
                         class="member-avatar"
                         style="width: 100px; height: 100px;">
                </div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($member['username']) ?>" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Họ tên</label>
            <input type="text" name="fullName" class="form-control" value="<?= htmlspecialchars($member['fullName']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($member['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password (để trống nếu không thay đổi)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Ngày sinh</label>
            <input type="date" name="dateOfBirth" class="form-control" value="<?= htmlspecialchars($member['dateOfBirth']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Giới tính</label>
            <select name="sex" class="form-control" required>
                <option value="Male" <?= $member['sex'] == 'Male' ? 'selected' : '' ?>>Nam</option>
                <option value="Female" <?= $member['sex'] == 'Female' ? 'selected' : '' ?>>Nữ</option>
                <option value="Other" <?= $member['sex'] == 'Other' ? 'selected' : '' ?>>Khác</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Số điện thoại</label>
            <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($member['phone']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Trạng thái</label>
            <select name="status" class="form-control" required>
                <option value="ACTIVE" <?= ($member['status'] ?? '') == 'ACTIVE' ? 'selected' : '' ?>>Hoạt động</option>
                <option value="INACTIVE" <?= ($member['status'] ?? '') == 'INACTIVE' ? 'selected' : '' ?>>Không hoạt động</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Gói tập</label>
            <select name="package_id" class="form-select" required>
                <option value="">Chọn gói tập</option>
                <?php foreach($packages as $package): ?>
                    <option value="<?= $package['id'] ?>" <?= ($member['package_id'] ?? '') == $package['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($package['name']) ?> 
                        (<?= $package['duration'] ?> tháng - <?= number_format($package['price'], 0, ',', '.') ?> VNĐ)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Ngày bắt đầu</label>
            <input type="date" name="startDate" class="form-control" value="<?= htmlspecialchars($member['startDate'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ngày kết thúc</label>
            <input type="date" name="endDate" class="form-control" value="<?= htmlspecialchars($member['endDate'] ?? '') ?>" required>
        </div>
        <div>
            <a href="/gym-php/admin/member" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </div>
    </form>
</div>

<style>
.member-avatar {
    border-radius: 50%;
    object-fit: cover;
}
</style>
