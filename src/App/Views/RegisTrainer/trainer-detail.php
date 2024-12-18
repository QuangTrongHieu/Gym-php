<!DOCTYPE html>
<html lang="vi">
<head>
    <?php require_once ROOT_PATH . '/src/App/Views/layouts/header.php'; ?>
    <style>
        .trainer-profile {
            padding: 50px 0;
        }
        .trainer-image {
            max-width: 100%;
            border-radius: 10px;
        }
        .trainer-info {
            padding: 20px;
        }
        .trainer-specialties {
            color: #ff4d4d;
            font-size: 1.2rem;
            margin: 15px 0;
        }
        .trainer-stats {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        .schedule-table {
            margin-top: 30px;
        }
        .certification-list {
            list-style: none;
            padding-left: 0;
        }
        .certification-list li {
            margin-bottom: 10px;
            padding-left: 20px;
            position: relative;
        }
        .certification-list li:before {
            content: "•";
            color: #ff4d4d;
            position: absolute;
            left: 0;
        }
    </style>
</head>
<body>
    <div class="container trainer-profile">
        <div class="row">
            <div class="col-md-4">
                <img src="<?= $trainer['avatar'] ?? '/gym-php/public/images/trainers/default.jpg' ?>" 
                     class="trainer-image" 
                     alt="<?= htmlspecialchars($trainer['fullName']) ?>">
                
                <div class="social-links mt-4 text-center">
                    <?php if (!empty($trainer['facebook'])): ?>
                        <a href="<?= $trainer['facebook'] ?>" target="_blank"><i class="fab fa-facebook"></i></a>
                    <?php endif; ?>
                    
                    <?php if (!empty($trainer['instagram'])): ?>
                        <a href="<?= $trainer['instagram'] ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                    <?php endif; ?>
                    
                    <?php if (!empty($trainer['twitter'])): ?>
                        <a href="<?= $trainer['twitter'] ?>" target="_blank"><i class="fab fa-twitter"></i></a>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="col-md-8 trainer-info">
                <h1><?= htmlspecialchars($trainer['fullName']) ?></h1>
                <p class="trainer-specialties"><?= htmlspecialchars($trainer['specialties']) ?></p>
                
                <div class="trainer-stats">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <h4><?= $trainer['experience'] ?></h4>
                            <p>Năm kinh nghiệm</p>
                        </div>
                        <div class="col-md-4">
                            <h4><?= $trainer['clientsCount'] ?>+</h4>
                            <p>Khách hàng</p>
                        </div>
                        <div class="col-md-4">
                            <h4><?= $trainer['classesCount'] ?>+</h4>
                            <p>Lớp đã dạy</p>
                        </div>
                    </div>
                </div>

                <h3>Giới thiệu</h3>
                <p><?= nl2br(htmlspecialchars($trainer['description'])) ?></p>

                <h3>Chứng chỉ</h3>
                <ul class="certification-list">
                    <?php foreach ($trainer['certifications'] as $cert): ?>
                        <li><?= htmlspecialchars($cert) ?></li>
                    <?php endforeach; ?>
                </ul>

                <h3>Lịch dạy</h3>
                <table class="table schedule-table">
                    <thead>
                        <tr>
                            <th>Thời gian</th>
                            <th>Lớp</th>
                            <th>Phòng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($trainer['schedule'] as $class): ?>
                        <tr>
                            <td><?= $class['time'] ?></td>
                            <td><?= htmlspecialchars($class['name']) ?></td>
                            <td><?= htmlspecialchars($class['room']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 