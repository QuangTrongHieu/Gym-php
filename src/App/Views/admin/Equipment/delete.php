<?php if (isset($equipment)): ?>
    <form action="/admin/equipment/delete/<?= $equipment['id'] ?>" method="POST">
        <p>Bạn có chắc chắn muốn xóa thiết bị này không?</p>
        <p><strong>Tên:</strong> <?= $equipment['name'] ?></p>
        <p><strong>Mô tả:</strong> <?= $equipment['description'] ?></p>
        
        <div class="modal-footer px-0 pb-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-danger">Xóa</button>
        </div>
    </form>
<?php else: ?>
<?php endif; ?>