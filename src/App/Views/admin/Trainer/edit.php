<?php if (isset($trainer) && isset($trainer['name'])): ?>
    <div>
        <h1>Sửa thông tin huấn luyện viên: <?= htmlspecialchars($trainer['name']) ?></h1>
        <!-- Form sửa thông tin -->
    </div>
<?php else: ?>
    <p>Không tìm thấy thông tin huấn luyện viên.</p>
<?php endif; ?>