<div class="container">
    <h1>Xóa hội viên</h1>
    
    <div class="alert alert-warning">
        <h4 class="alert-heading">Xác nhận xóa</h4>
        <p>Bạn có chắc chắn muốn xóa hội viên <strong><?= htmlspecialchars($member['fullName']) ?></strong> không?</p>
        <hr>
        <p class="mb-0">Hành động này không thể hoàn tác.</p>
    </div>

    <form action="/gym-php/admin/member-management/delete/<?= $member['id'] ?>" method="POST">
        <input type="hidden" name="id" value="<?= $member['id'] ?>">
        
        <div class="mb-3">
            <label class="form-label">Username:</label>
            <p class="form-control-static"><?= htmlspecialchars($member['username']) ?></p>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Email:</label>
            <p class="form-control-static"><?= htmlspecialchars($member['email']) ?></p>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Số điện thoại:</label>
            <p class="form-control-static"><?= htmlspecialchars($member['phone']) ?></p>
        </div>

        <div class="mt-4">
            <a href="/gym-php/admin/member" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-danger">Xác nhận xóa</button>
        </div>
    </form>
</div>
