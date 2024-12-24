
<div class="container mt-4">
    <h2 class="text-center mb-4">Gym Equipment</h2>
    
    <div class="row">
        <?php if (!empty($equipments)) : ?>
            <?php foreach ($equipments as $equipment) : ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php if (!empty($equipment['image_path'])) : ?>
                            <img src="<?php echo $equipment['image_path']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($equipment['name']); ?>">
                        <?php else : ?>
                            <img src="/assets/images/default-equipment.jpg" class="card-img-top" alt="Default Equipment Image">
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($equipment['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($equipment['description']); ?></p>
                            <p class="card-text">
                                <small class="text-muted">
                                    Status: <span class="badge <?php echo $equipment['status'] === 'Available' ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo htmlspecialchars($equipment['status']); ?>
                                    </span>
                                </small>
                            </p>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">Last maintained: <?php echo date('M d, Y', strtotime($equipment['lastMaintenanceDate'])); ?></small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="col-12">
                <div class="alert alert-info" role="alert">
                    No equipment available at the moment.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>