<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Hội viên</title>
    <!-- ... existing code ... -->
</head>
<body>
    <div class="container mt-4">
        <h1>Sửa Hội viên</h1>
        <form action="/gym-php/admin/member/update/<?= $member['id'] ?>" method="POST">
            <input type="hidden" name="id" value="<?= $member['id'] ?>">
            <div class="mb-3">
                <label for="fullName" class="form-label">Họ tên</label>
                <input type="text" class="form-control" id="fullName" name="fullName" value="<?= htmlspecialchars($member['fullName']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($member['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">SĐT</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($member['phone']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="package" class="form-label">Gói hội viên</label>
                <input type="text" class="form-control" id="package" name="package" value="<?= htmlspecialchars($member['package']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="startDate" class="form-label">Ngày bắt đầu</label>
                <input type="date" class="form-control" id="startDate" name="startDate" value="<?= htmlspecialchars($member['startDate']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="endDate" class="form-label">Ngày kết thúc</label>
                <input type="date" class="form-control" id="endDate" name="endDate" value="<?= htmlspecialchars($member['endDate']) ?>" required>
            </div>
            <button type="submit" class