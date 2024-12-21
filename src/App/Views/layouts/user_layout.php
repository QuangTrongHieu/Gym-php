
    <?php require_once 'header.php'; ?>

    <div class="container py-4">
        <div class="bg-white rounded shadow">
            <div class="row g-0">
                <!-- Sidebar -->
                <div class="col-md-3 border-end">
                    <div class="p-4">
                        <div class="d-flex align-items-center mb-4">
                            <img src="<?= isset($user['avatar']) ? '/gym-php/public' . $user['avatar'] : 'https://placehold.co/100x100?text=Profile' ?>"
                                alt="User Profile" class="rounded-circle me-3"
                                style="width: 60px; height: 60px; object-fit: cover;" />
                            <div>
                                <div class="fs-5 fw-semibold"><?= htmlspecialchars($user['username'] ?? 'Người dùng') ?>
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-pencil-square"></i>
                                    <a href="/gym-php/user/profile" class="text-decoration-none text-muted">Sửa hồ
                                        sơ</a>
                                </small>
                            </div>
                        </div>

                        <div class="nav flex-column nav-pills">
                            <div class="nav-item mb-2">
                                <span class="d-block text-secondary fw-bold mb-2">Tài khoản của tôi</span>
                                <a href="/gym-php/user/profile"
                                    class="nav-link <?= $active_page == 'profile' ? 'active' : '' ?>">
                                    <i class="bi bi-person me-2"></i>Hồ Sơ
                                </a>
                                <a href="/gym-php/user/addresses"
                                    class="nav-link <?= $active_page == 'addresses' ? 'active' : '' ?>">
                                    <i class="bi bi-geo-alt me-2"></i>Địa Chỉ
                                </a>
                                <a href="/gym-php/user/change-password"
                                    class="nav-link <?= $active_page == 'change-password' ? 'active' : '' ?>">
                                    <i class="bi bi-key me-2"></i>Đổi Mật Khẩu
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Main Content -->
                <div class="col-md-9">
                    <div class="p-4">
                        <?php echo $content; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer có thể thêm ở đây -->
    <?php require_once 'footer.php'; ?>
