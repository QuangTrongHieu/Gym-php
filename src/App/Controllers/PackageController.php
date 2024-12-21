<?php

namespace App\Controllers;

use Core\Controller;
use Core\Database;

class PackageController extends Controller
{
    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = new Database();
    }

    public function index()
    {
        $packages = $this->db->query("SELECT * FROM packages")->fetchAll();
        $this->view('admin/packages/index', ['packages' => $packages]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $price = $_POST['price'];
            $duration = $_POST['duration'];
            $description = $_POST['description'] ?? '';
            $status = 'active';

            try {
                $sql = "INSERT INTO packages (name, description, duration, price, status) VALUES (?, ?, ?, ?, ?)";
                $this->db->query($sql, [$name, $description, $duration, $price, $status]);
                $_SESSION['success'] = "Thêm gói tập thành công!";
                header('Location: /gym-php/admin/packages');
                exit;
            } catch (\Exception $e) {
                $_SESSION['error'] = "Có lỗi xảy ra khi thêm gói tập!";
            }
        }

        $this->view('admin/packages/create');
    }
}
