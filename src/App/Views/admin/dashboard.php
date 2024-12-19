<div class="container mt-4">
    <h1>Chào mừng đến với Trang quản trị</h1>
    <div class="row mt-4">
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Quản lý Admin</h5>
                    <p class="card-text">Quản lý tài khoản admin của hệ thống</p>
                    <a href="/gym-php/admin/admin-management" class="btn btn-primary">Truy cập</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Quản lý huấn luyện viên</h5>
                    <p class="card-text">Quản lý thông tin huấn luyện viên</p>
                    <a href="/gym-php/admin/trainer" class="btn btn-primary">Truy cập</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Quản lý Thiết bị</h5>
                    <p class="card-text">Quản lý thông tin thiết bị</p>
                    <a href="/gym-php/admin/equipment" class="btn btn-primary">Truy cập</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Doanh thu</h5>
                    <ul>
                        <?php 
                        $revenueData = $revenueData ?? [];
                        foreach ($revenueData as $revenue): ?>
                            <li><?php echo htmlspecialchars($revenue['package_name']); ?>: <?php echo htmlspecialchars($revenue['total_revenue']); ?> VNĐ</li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>