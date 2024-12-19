<?php if (!empty($trainers)): ?>
    <?php foreach ($trainers as $trainer): ?>
        <div>
            <h2>Xóa huấn luyện viên: <?= $trainer['name'] ?></h2>
            <!-- Form xóa huấn luyện viên -->
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Không có huấn luyện viên nào để xóa.</p>
<?php endif; ?>