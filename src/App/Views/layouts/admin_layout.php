<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .nav-item .nav-link {
            padding: 0.8rem 1rem;
            transition: all 0.3s;
        }

        .nav-item .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-item .nav-link i {
            margin-right: 10px;
        }

        #mainContent {
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 bg-dark min-vh-100 p-0">
                <div class="p-3 text-white">
                    <h4>Admin Panel</h4>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/gym-php/admin/dashboard">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/gym-php/admin/admin-management">
                            <i class="fas fa-users"></i> Quản lý admin
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/gym-php/admin/trainer">
                            <i class="fas fa-user-tie"></i> Quản lý huấn luyện viên
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/gym-php/admin/member">
                            <i class="fas fa-user-friends"></i> Quản lý hội viên
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/gym-php/admin/equipment">
                            <i class="fas fa-tools"></i> Quản lý thiết bị
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/gym-php/admin/packages">
                            <i class="fas fa-box-open"></i> Quản lý gói tập
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/gym-php/admin/Schedule">
                            <i class="fas fa-calendar-alt"></i> Lịch tập
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/gym-php/admin/Revenue">
                            <i class="fas fa-chart-line"></i> Doanh thu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/gym-php/admin/logout">
                            <i class="bi bi-box-arrow-right"></i> Đăng xuất
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main content -->
            <div class="col-md-10 p-0">
                <div id="mainContent">
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>