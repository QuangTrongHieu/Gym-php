<div class="container-fluid px-4">
    <h1 class="mt-4">Quản lý hội viên</h1>
    
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Danh sách hội viên
            <a href="/gym-php/admin/member/create" class="btn btn-primary float-end">Thêm mới</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên đăng nhập</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Ngày sinh</th>
                        <th>Giới tính</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($members as $member): ?>
                    <tr>
                        <td><?= $member['id'] ?></td>
                        <td><?= $member['username'] ?></td>
                        <td><?= $member['fullName'] ?></td>
                        <td><?= $member['email'] ?></td>
                        <td><?= $member['phone'] ?></td>
                        <td><?= $member['dateOfBirth'] ?></td>
                        <td><?= $member['sex'] ?></td>
                        <td><?= $member['status'] ?></td>
                        <td>
                            <a href="/gym-php/admin/member/edit/<?= $member['id'] ?>" class="btn btn-sm btn-primary">Sửa</a>
                            <form action="/gym-php/admin/member/delete/<?= $member['id'] ?>" method="POST" class="d-inline">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div> 