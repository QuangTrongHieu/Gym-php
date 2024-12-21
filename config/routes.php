<?php

$router = new Core\Router;

// Home routes
$router->add('/', ['controller' => 'Home', 'action' => 'index']);
$router->add('/home', ['controller' => 'Home', 'action' => 'index']);
$router->add('/list-trainers', ['controller' => 'Trainer', 'action' => 'listTrainers']);

// Admin routes
$router->add("/admin", ["controller" => "admin", "action" => "index"]);
$router->add("/admin/dashboard", ["controller" => "admin", "action" => "dashboard"]);
$router->add("/admin/admin-management", ["controller" => "admin", "action" => "adminManagement"]);
$router->add("/admin/admin-management/create", ["controller" => "admin", "action" => "create"]);
$router->add("/admin/admin-management/edit/{id:\d+}", ["controller" => "admin", "action" => "edit"]);
$router->add("/admin/admin-management/delete/{id:\d+}", ["controller" => "admin", "action" => "delete"]);

$router->add('/admin/trainer', ['controller' => 'Trainer', 'action' => 'index']);
$router->add('/admin/trainer/create', ['controller' => 'Trainer', 'action' => 'create']);
$router->add('/admin/trainer/edit/{id:\d+}', ['controller' => 'Trainer', 'action' => 'edit']);
$router->add('/admin/trainer/delete/{id:\d+}', ['controller' => 'Trainer', 'action' => 'delete']);

$router->add('/admin/equipment', ['controller' => 'Equipment', 'action' => 'index']);
$router->add('/admin/equipment/create', ['controller' => 'Equipment', 'action' => 'create']);
$router->add('/admin/equipment/edit/{id:\d+}', ['controller' => 'Equipment', 'action' => 'edit']);
$router->add('/admin/equipment/delete/{id:\d+}', ['controller' => 'Equipment', 'action' => 'delete']);

$router->add('/admin/packages', ['controller' => 'packages', 'action' => 'index']);
$router->add('/admin/packages/create', ['controller' => 'packages', 'action' => 'create']);
$router->add('/admin/packages/edit/{id:\d+}', ['controller' => 'packages', 'action' => 'edit']);
$router->add('/admin/packages/delete/{id:\d+}', ['controller' => 'packages', 'action' => 'delete']);

$router->add('/admin/member', ['controller' => 'member', 'action' => 'index']);
$router->add('/admin/member/create', ['controller' => 'member', 'action' => 'create']);
$router->add('/admin/member/edit/{id:\d+}', ['controller' => 'member', 'action' => 'edit']);
$router->add('/admin/member/delete/{id:\d+}', ['controller' => 'member', 'action' => 'delete']);

$router->add('/admin/revenue', ['controller' => 'revenue', 'action' => 'index']);
$router->add('/admin/revenue/create', ['controller' => 'revenue', 'action' => 'create']);
$router->add('/admin/revenue/edit/{id:\d+}', ['controller' => 'revenue', 'action' => 'edit']);
$router->add('/admin/revenue/delete/{id:\d+}', ['controller' => 'revenue', 'action' => 'delete']);

$router->add('/admin/schedule', ['controller' => 'schedule', 'action' => 'index']);
$router->add('/admin/schedule/create', ['controller' => 'schedule', 'action' => 'create']);
$router->add('/admin/schedule/edit/{id:\d+}', ['controller' => 'schedule', 'action' => 'edit']);
$router->add('/admin/schedule/delete/{id:\d+}', ['controller' => 'schedule', 'action' => 'delete']);

// Auth routes
$router->add("/login", ["controller" => "Auth", "action" => "login"]);
$router->add("/register", ["controller" => "Auth", "action" => "register"]);
$router->add("/logout", ["controller" => "Auth", "action" => "logout"]);


// // Membership routes
// $router->add('/member', ['controller' => 'Membership', 'action' => 'index']);
// $router->add('/member/create', ['controller' => 'Membership', 'action' => 'create']);
// $router->add('/member/edit/{id:\d+}', ['controller' => 'Membership', 'action' => 'edit']);
// $router->add('/member/delete/{id:\d+}', ['controller' => 'Membership', 'action' => 'delete']);

// // Package routes
// $router->add('/packages', ['controller' => 'Package', 'action' => 'index']);
// $router->add('/packages/create', ['controller' => 'Package', 'action' => 'create']);
// $router->add('/packages/edit/{id:\d+}', ['controller' => 'Package', 'action' => 'edit']);
// $router->add('/packages/delete/{id:\d+}', ['controller' => 'Package', 'action' => 'delete']);

// Payment routes
$router->add('/payment/create', ['controller' => 'Payment', 'action' => 'create']);
$router->add('/payment/update-status/{id:\d+}', ['controller' => 'Payment', 'action' => 'updateStatus']);

// PT Registration routes
$router->add('/pt-registration', ['controller' => 'PTRegistration', 'action' => 'getAll']);
$router->add('/pt-registration/create', ['controller' => 'PTRegistration', 'action' => 'create']);
$router->add('/pt-registration/update/{id:\d+}', ['controller' => 'PTRegistration', 'action' => 'update']);
$router->add('/pt-registration/delete/{id:\d+}', ['controller' => 'PTRegistration', 'action' => 'delete']);

// Revenue routes
$router->add('/admin/revenue', ['controller' => 'Revenue', 'action' => 'index']);

// Schedule routes
$router->add('/schedule', ['controller' => 'Schedule', 'action' => 'index']);
$router->add('/schedule/create', ['controller' => 'Schedule', 'action' => 'create']);

// Trainer routes
$router->add('/trainers', ['controller' => 'Trainer', 'action' => 'index']);
$router->add('/trainers/create', ['controller' => 'Trainer', 'action' => 'create']);
$router->add('/trainers/update/{id:\d+}', ['controller' => 'Trainer', 'action' => 'update']);
$router->add('/trainers/delete/{id:\d+}', ['controller' => 'Trainer', 'action' => 'delete']);
$router->add('/trainers/{id:\d+}/schedule', ['controller' => 'Trainer', 'action' => 'getSchedule']);
$router->add('/trainers/{id:\d+}/sessions', ['controller' => 'Trainer', 'action' => 'getTrainingSessions']);
$router->add('/trainers/sessions/{id:\d+}/status', ['controller' => 'Trainer', 'action' => 'updateSessionStatus']);
$router->add('/trainers/{id:\d+}/performance', ['controller' => 'Trainer', 'action' => 'getPerformanceStats']);
$router->add('/trainers/{id:\d+}/clients', ['controller' => 'Trainer', 'action' => 'getClients']);

// User routes
$router->add('/user', ['controller' => 'User', 'action' => 'index']);
$router->add('/user/profile', ['controller' => 'User', 'action' => 'profile']);
$router->add('/user/upload-avatar', ['controller' => 'User', 'action' => 'uploadAvatar']);
$router->add('/user/addresses', ['controller' => 'User', 'action' => 'addresses']);
$router->add('/user/address/{id:\d+}', ['controller' => 'User', 'action' => 'getAddress']);
$router->add('/user/address/create', ['controller' => 'User', 'action' => 'createAddress']);
$router->add('/user/address/delete/{id:\d+}', ['controller' => 'User', 'action' => 'deleteAddress']);
$router->add('/user/address/{id:\d+}/default', ['controller' => 'User', 'action' => 'setDefaultAddress']);

// Trainer panel routes
$router->add('/trainer/dashboard', ['controller' => 'TrainerPanel', 'action' => 'dashboard']);
$router->add('/trainer/schedule', ['controller' => 'TrainerPanel', 'action' => 'schedule']); 
$router->add('/trainer/clients', ['controller' => 'TrainerPanel', 'action' => 'clients']);
$router->add('/trainer/programs', ['controller' => 'TrainerPanel', 'action' => 'programs']);
$router->add('/trainer/profile', ['controller' => 'TrainerPanel', 'action' => 'profile']);

// RegisTrainer routes
$router->add('/trainers-list', ['controller' => 'RegisTrainer', 'action' => 'index']);
$router->add('/trainer/{id:\d+}', ['controller' => 'RegisTrainer', 'action' => 'trainerDetail']);

// Admin auth routes
$router->add("/admin-login", ["controller" => "Auth", "action" => "adminLogin"]);
$router->add("/admin/logout", ["controller" => "Auth", "action" => "logout"]);

// Trainer auth routes
$router->add("/trainer-login", ["controller" => "Auth", "action" => "trainerLogin"]);
$router->add("/trainer/logout", ["controller" => "Auth", "action" => "logout"]);

return $router;