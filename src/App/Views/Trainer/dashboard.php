// ... existing code ...

    <!-- Lịch tập Card -->
    <div class="col-xl-6 col-md-12 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Lịch tập</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $trainingSchedule ?? 'Chưa có lịch tập' ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thông tin cá nhân Card -->
    <div class="col-xl-6 col-md-12 mb-4">
        <div class="card border-left-secondary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                            Thông tin cá nhân</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $personalInfo ?? 'Chưa có thông tin' ?></div>
                    </div>
                    <div class="col-auto">
                        <a href="/ecommerce-php/employee/profile/edit" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Chỉnh sửa
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

// ... existing code ...