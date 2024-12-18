<?php
namespace Core\Middleware;

class AuthMiddleware
{
    public function handle($request, $allowedRoles = [])
    {
        if (!isset($_SESSION['user_id'])) {
            $role = $allowedRoles[0] ?? '';
            switch($role) {
                case 'ADMIN':
                    header('Location: /gym-php/admin-login');
                    break;
                case 'Trainer':
                    header('Location: /gym-php/Trainer-login'); 
                    break;
                default:
                    header('Location: /gym-php/login');
            }
            exit;   
        }

        if (!empty($allowedRoles)) {
            $userRole = $_SESSION['user_role'] ?? null;
            if (!in_array($userRole, $allowedRoles)) {
                header('Location: /gym-php/403');
                exit;
            }
        }

        return true;
    }
} 