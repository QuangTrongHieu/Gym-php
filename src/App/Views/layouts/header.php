<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PowerGym - Phòng Gym Chuyên Nghiệp</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Thêm custom CSS -->
    <style>
        .navbar {
            background-color: #1a1a1a !important;
            padding: 15px 0;
        }

        .navbar-brand {
            font-weight: bold;
            color: #ff4d4d !important;
        }

        .nav-link {
            color: #ffffff !important;
            font-weight: 500;
            margin: 0 15px;
        }

        .nav-link:hover {
            color: #ff4d4d !important;
        }

        .btn-login {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
        }

        .btn-login:hover {
            background-color: #ff3333;
            color: white;
        }

        /* .navbar-brand img {
            border-radius: 25px;
        } */
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="/gym-php">
                <img src="/gym-php/public/images/logo/logo1.png" alt="PowerGym Logo" height="55">
                <span class="ms-2 d-none d-lg-inline">POWERGYM</span>
            </a>

            <!-- Navigation Links -->
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/gym-php">Trang Chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/gym-php/list-trainers">Huấn Luyện Viên</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/gym-php/equipment">Thiết bị tập</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/gym-php/contact">Liên Hệ</a>
                    </li>
                </ul>
            </div>

            <!-- User Account -->
             
            <?php if ($user['isLoggedIn']): ?>
                <!-- Hiển thị dropdown menu khi đã đăng nhập -->
                <div class="dropdown">
                    <button class="btn btn-dark dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                        <img src="<?= $user['avatar'] ?? 'https://openui.fly.dev/openui/24x24.svg?text=👤' ?>"
                            alt="user-avatar" class="rounded-circle" style="width: 30px; height: 30px;">
                        <span class="ms-2 text-white"><?= htmlspecialchars($user['name']) ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/gym-php/user/profile">Hồ Sơ Cá Nhân</a></li>
                        <li><a class="dropdown-item" href="/gym-php/user/training">Lịch Tập</a></li>
                        <li><a class="dropdown-item" href="/gym-php/user/progress">Theo Dõi Tiến Độ</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="/gym-php/logout">Đăng Xuất</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="/gym-php/login" class="btn btn-login">
                    <i class="fa fa-user"></i>
                    <span class="ms-2">Đăng Nhập</span>
                </a>
            <?php endif; ?>

            <!-- Toggle Button -->
            <button class="navbar-toggler ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <?php if (isset($_SESSION['login_message'])): ?>
        <div class="alert alert-info" role="alert">
            <?= htmlspecialchars($_SESSION['login_message']) ?>
        </div>
        <?php unset($_SESSION['login_message']); ?>
    <?php endif; ?>
</body>

</html>