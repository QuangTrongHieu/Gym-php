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
                        <i class="fas fa-chart-pie me-2"></i>
                        Thống kê người dùng
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <canvas id="userPieChart"></canvas>
                        </div>
                        <div class="col-md-6">
                            <div class="stats-legend mt-3">
                                <div class="legend-item mb-2">
                                    <span class="legend-color" style="background-color: rgb(75, 192, 192)"></span>
                                    <span>Hội viên</span>
                                    <span class="member-count"></span>
                                </div>
                                <div class="legend-item mb-2">
                                    <span class="legend-color" style="background-color: rgb(255, 99, 132)"></span>
                                    <span>Huấn luyện viên</span>
                                    <span class="trainer-count"></span>
                                </div>
                                <div class="legend-item mb-2">
                                    <span class="legend-color" style="background-color: rgb(54, 162, 235)"></span>
                                    <span>Người dùng</span>
                                    <span class="user-count"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.legend-item {
    display: flex;
    align-items: center;
    gap: 10px;
}
.legend-color {
    width: 20px;
    height: 20px;
    border-radius: 3px;
}
</style>

<!-- Add Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let userPieChart;

    function initChart(data) {
        const ctx = document.getElementById('userPieChart').getContext('2d');
        
        if (userPieChart) {
            userPieChart.destroy();
        }

        // Calculate totals
        const memberTotal = data.members[data.members.length - 1];
        const trainerTotal = data.trainers[data.trainers.length - 1];
        const userTotal = data.users[data.users.length - 1];

        // Update legend counts
        document.querySelector('.member-count').textContent = `(${memberTotal})`;
        document.querySelector('.trainer-count').textContent = `(${trainerTotal})`;
        document.querySelector('.user-count').textContent = `(${userTotal})`;

        userPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Hội viên', 'Huấn luyện viên', 'Người dùng'],
                datasets: [{
                    data: [memberTotal, trainerTotal, userTotal],
                    backgroundColor: [
                        'rgb(75, 192, 192)',
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const value = context.raw;
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${context.label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    function fetchData() {
        fetch('/gym-php/admin/getUserStats?days=1')
            .then(response => response.json())
            .then(data => {
                initChart(data);
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    }

    // Initial load
    fetchData();

    // Update every minute
    setInterval(fetchData, 60000);
});
</script>