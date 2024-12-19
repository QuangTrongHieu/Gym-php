<?php foreach ($Trainers as $Trainer): ?>
    <div class="modal fade" id="editModal<?= $Trainer['id'] ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sửa Thông tin Nhân viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="/gym/admin/Trainer-management/edit/<?= $Trainer['id'] ?>" method="POST">
                        <input type="hidden" name="id" value="<?= $Trainer['id'] ?>">

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" 
                                   value="<?= $Trainer['email'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" name="fullName" class="form-control" 
                                   value="<?= $Trainer['fullName'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ngày sinh</label>
                            <input type="date" name="dateOfBirth" class="form-control" 
                                   value="<?= $Trainer['dateOfBirth'] ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Giới tính</label>
                            <select name="sex" class="form-select">
                                <option value="Male" <?= $Trainer['sex'] == 'Male' ? 'selected' : '' ?>>Nam</option>
                                <option value="Female" <?= $Trainer['sex'] == 'Female' ? 'selected' : '' ?>>Nữ</option>
                                <option value="Other" <?= $Trainer['sex'] == 'Other' ? 'selected' : '' ?>>Khác</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" 
                                   value="<?= $Trainer['phone'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Địa chỉ</label>
                            <input type="text" name="address" class="form-control" 
                                   value="<?= $Trainer['address'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Lương</label>
                            <input type="number" name="salary" class="form-control" 
                                   value="<?= $Trainer['salary'] ?>" required>
                        </div>

                        <div class="modal-footer px-0 pb-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
