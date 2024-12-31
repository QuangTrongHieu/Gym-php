<div class="container mt-4">
    <h1>Chào mừng đến với Trang quản trị</h1>
    <div class="row mt-4">

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-user-shield me-2"></i>
                        Quản lý Admin
                    </h5>
                    <p class="card-text">Quản lý tài khoản admin của hệ thống</p>
                    <a href="/gym-php/admin/admin-management" class="btn btn-primary">Truy cập</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-dumbbell me-2"></i>
                        Quản lý huấn luyện viên
                    </h5>
                    <p class="card-text">Quản lý thông tin huấn luyện viên</p>
                    <a href="/gym-php/admin/trainer" class="btn btn-primary">Truy cập</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-cogs me-2"></i>
                        Quản lý Thiết bị
                    </h5>
                    <p class="card-text">Quản lý thông tin thiết bị</p>
                    <a href="/gym-php/admin/equipment" class="btn btn-primary">Truy cập</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-chart-pie me-2"></i>
                        Thống kê gói tập
                    </h5>
                    <ul class="list-group">
                        <?php
                        $revenueData = $revenueData ?? [];
                        foreach ($revenueData as $revenue): ?>
                            <li class="list-group-item">
                                <i class="fas fa-cube me-2"></i>
                                <strong><?php echo htmlspecialchars($revenue['package_name']); ?></strong><br>
                                <i class="fas fa-users me-2"></i>
                                Số người đăng ký: <?php echo htmlspecialchars($revenue['total_users']); ?><br>
                                <i class="fas fa-money-bill-wave me-2"></i>
                                Doanh thu: <?php echo number_format($revenue['total_revenue'], 0, ',', '.'); ?> VNĐ
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-chart-bar me-2"></i>
                        Biểu đồ doanh thu và số người đăng ký theo tháng
                    </h5>
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($monthlyRevenue['labels'] ?? []); ?>,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: <?php echo json_encode($monthlyRevenue['revenue'] ?? []); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgb(75, 192, 192)',
                    borderWidth: 1,
                    yAxisID: 'y'
                }, {
                    label: 'Số người đăng ký',
                    data: <?php echo json_encode($monthlyRevenue['users'] ?? []); ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgb(255, 99, 132)',
                    borderWidth: 1,
                    type: 'line',
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Doanh thu (VNĐ)'
                        },
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value) + ' VNĐ';
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Số người đăng ký'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (context.dataset.yAxisID === 'y') {
                                    return 'Doanh thu: ' + new Intl.NumberFormat('vi-VN').format(context.raw) + ' VNĐ';
                                } else {
                                    return 'Số người đăng ký: ' + context.raw;
                                }
                            }
                        }
                    }
                }
            }
        });
    });
</script>