<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success']; ?>
        <?php unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?= $_SESSION['error']; ?>
        <?php unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Thống kê Doanh thu</h1>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Tên Gói</th>
            <th>Tổng Doanh thu</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($revenueData as $revenue): ?>
        <tr>
            <td><?= htmlspecialchars($revenue['package_name']) ?></td>
            <td><?= number_format($revenue['total_revenue']) ?> VNĐ</td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>