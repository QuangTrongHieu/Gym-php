<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xóa Hội viên</title>
    <!-- ... existing code ... -->
</head>
<body>
    <div class="container mt-4">
        <h1>Xóa Hội viên</h1>
        <p>Bạn có chắc chắn muốn xóa hội viên <strong><?= htmlspecialchars($member['fullName']) ?></strong> không?</p>
        <form action="/gym-php/admin/member/destroy/<?= $member['id'] ?>" method="POST">
            <input type="hidden" name="id" value="<?= $member['id'] ?>">
            <button type="submit" class="btn btn-danger">Xóa</button>
            <a href="/gym-php/admin/member" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</body>
</html>