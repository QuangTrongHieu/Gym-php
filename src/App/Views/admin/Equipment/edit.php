<?php if (isset($equipment)): ?>
    <form action="/admin/equipment/edit/<?= $equipment['id'] ?>" method="POST">
        <input type="hidden" name="id" value="<?= $equipment['id'] ?>">

        <div class="mb-3">
            <label class="form-label">Tên</label>
            <input type="text" name="name" class="form-control" 
                   value="<?= $equipment['name'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control" required><?= $equipment['description'] ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Ngày mua</label>
            <input type="date" name="purchaseDate" class="form-control" 
                   value="<?= $equipment['purchaseDate'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Giá</label>
            <input type="number" name="price" class="form-control" 
                   value="<?= $equipment['price'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Trạng thái</label>
            <select name="status" class="form-select" required>
                <option value="active" <?= $equipment['status'] == 'active' ? 'selected' : '' ?>>Hoạt động</option>
                <option value="inactive" <?= $equipment['status'] == 'inactive' ? 'selected' : '' ?>>Ngừng hoạt động</option>
            </select>
        </div>

        <div class="modal-footer px-0 pb-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </div>
    </form>
<?php else: ?>
<?php endif; ?>