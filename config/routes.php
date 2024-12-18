<?php

$router = new Core\Router;

// Home routes
$router->add('/', ['controller' => 'Home', 'action' => 'index']);
$router->add('/home', ['controller' => 'Home', 'action' => 'index']);

// Admin routes
$router->add("/admin", ["controller" => "admin", "action" => "index"]);
$router->add("/admin/dashboard", ["controller" => "admin", "action" => "dashboard"]);
$router->add("/admin/admin-management", ["controller" => "admin", "action" => "adminManagement"]);
$router->add("/admin/admin-management/create", ["controller" => "admin", "action" => "create"]);
$router->add("/admin/admin-management/edit/{id:\d+}", ["controller" => "admin", "action" => "edit"]);
$router->add("/admin/admin-management/delete/{id:\d+}", ["controller" => "admin", "action" => "delete"]);

$router->add('/admin/trainer', ['controller' => 'trainer', 'action' => 'trainerManagement']);
$router->add('/admin/trainer/create', ['controller' => 'trainer', 'action' => 'create']);
$router->add('/admin/trainer/edit/{id:\d+}', ['controller' => 'trainer', 'action' => 'edit']);
$router->add('/admin/trainer/delete/{id:\d+}', ['controller' => 'trainer', 'action' => 'delete']);

// Auth routes
$router->add("/login", ["controller" => "Auth", "action" => "login"]);
$router->add("/register", ["controller" => "Auth", "action" => "register"]);
$router->add("/logout", ["controller" => "Auth", "action" => "logout"]);

// Equipment routes
$router->add('/admin/equipment', ['controller' => 'Equipment', 'action' => 'index']);
$router->add('/admin/equipment/create', ['controller' => 'Equipment', 'action' => 'create']);
$router->add('/admin/equipment/edit/{id:\d+}', ['controller' => 'Equipment', 'action' => 'edit']);
$router->add('/admin/equipment/delete/{id:\d+}', ['controller' => 'Equipment', 'action' => 'delete']);

// Membership routes
$router->add('/membership', ['controller' => 'Membership', 'action' => 'index']);
$router->add('/membership/create', ['controller' => 'Membership', 'action' => 'create']);
$router->add('/membership/update/{id:\d+}', ['controller' => 'Membership', 'action' => 'update']);
$router->add('/membership/delete/{id:\d+}', ['controller' => 'Membership', 'action' => 'delete']);

// Package routes
$router->add('/packages', ['controller' => 'Package', 'action' => 'index']);
$router->add('/packages/create', ['controller' => 'Package', 'action' => 'create']);
$router->add('/packages/edit/{id:\d+}', ['controller' => 'Package', 'action' => 'edit']);
$router->add('/packages/delete/{id:\d+}', ['controller' => 'Package', 'action' => 'delete']);

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
$router->add("/Trainer-login", ["controller" => "Auth", "action" => "trainerLogin"]);
$router->add("/Trainer/logout", ["controller" => "Auth", "action" => "logout"]);

return $router;