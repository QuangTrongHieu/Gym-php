<?php

namespace App\Controllers;

use App\Models\Equipment;

class EquipmentController extends BaseController
{
    private $equipmentModel;

    public function __construct()
    {
        $this->equipmentModel = new Equipment();
    }

    private function handleImageUpload($file, $oldAvatar = null)
    {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            if ($file['error'] === UPLOAD_ERR_NO_FILE) {
                return false; // Không có file được tải lên, không phải lỗi
            }
            throw new \Exception($this->getFileErrorMessage($file['error']));
        }

        // Kiểm tra kích thước file
        if ($file['size'] > self::UPLOAD_CONFIG['max_size']) {
            throw new \Exception('Kích thước file quá lớn. Giới hạn ' . (self::UPLOAD_CONFIG['max_size'] / 1024 / 1024) . 'MB');
        }

        // Kiểm tra loại MIME
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!array_key_exists($mimeType, self::UPLOAD_CONFIG['allowed_types'])) {
            throw new \Exception('Loại file không được hỗ trợ. Chỉ chấp nhận file ảnh (JPG, PNG, GIF, WEBP)');
        }

        $uploadDir = ROOT_PATH . '/' . self::UPLOAD_CONFIG['dir'] . '/';

        // Xóa avatar cũ nếu tồn tại
        if ($oldAvatar && $oldAvatar !== 'default.jpg') {
            $oldAvatarPath = $uploadDir . $oldAvatar;
            if (file_exists($oldAvatarPath) && is_file($oldAvatarPath)) {
                unlink($oldAvatarPath);
            }
        }

        // Tạo tên file an toàn
        $extension = self::UPLOAD_CONFIG['allowed_types'][$mimeType];
        $fileName = 'member_' . uniqid() . '_' . time() . '.' . $extension;
        $targetPath = $uploadDir . $fileName;

        // Kiểm tra kích thước và nội dung ảnh
        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            throw new \Exception('File không phải là hình ảnh hợp lệ');
        }

        // Di chuyển file đã tải lên
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new \Exception('Không thể lưu file. Vui lòng kiểm tra quyền thư mục');
        }

        // Đặt quyền an toàn cho file
        chmod($targetPath, 0644);

        return $fileName;
    }

    public function index()
    {
        $equipments = $this->equipmentModel->findAll();
        $this->view('admin/equipment/index', [
            'title' => 'Quản lý Thiết bị',
            'equipments' => $equipments
        ]);
    }

    public function listEquipment()
    {
        $equipments = $this->equipmentModel->getAllActiveEquipment();
        // Debug output
        echo "<!-- Debug: " . print_r($equipments, true) . " -->";

        $this->view('equipmentreviews/equipment', [
            'title' => 'Thiết Bị Phòng Gym',
            'equipments' => $equipments
        ]);
    }

    public function listEquipmentForUsers()
    {
        $equipment = $this->equipmentModel->findActiveEquipment();
        $this->view('EquipmentReviews/Equipment', [
            'title' => 'Các thiết bị tập của chúng tôi',
            'equipment' => $equipment
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate dữ liệu
            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'purchaseDate' => $_POST['purchaseDate'],
                'price' => floatval($_POST['price']),
                'status' => $_POST['status'],
                'lastMaintenanceDate' => !empty($_POST['lastMaintenanceDate']) ? $_POST['lastMaintenanceDate'] : null,
                'nextMaintenanceDate' => !empty($_POST['nextMaintenanceDate']) ? $_POST['nextMaintenanceDate'] : null
            ];

            // Xử lý upload ảnh
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imagePath = $this->handleImageUpload($_FILES['image']);
                if ($imagePath) {
                    $data['image_path'] = $imagePath;
                }
            }

            // Kiểm tra validate
            $errors = [];
            if (empty($data['name'])) {
                $errors['name'] = 'Vui lòng nhập tên thiết bị';
            }
            if (empty($data['purchaseDate'])) {
                $errors['purchaseDate'] = 'Vui lòng nhập ngày mua';
            }
            if ($data['price'] <= 0) {
                $errors['price'] = 'Giá phải lớn hơn 0';
            }

            if (empty($errors)) {
                if ($this->equipmentModel->create($data)) {
                    $_SESSION['success'] = 'Thêm thiết bị thành công';
                    header('Location: /gym-php/admin/equipment');
                    exit();
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra';
                    header('Location: /gym-php/admin/equipment');
                    exit();
                }
            }

            $title = 'Thêm thiết bị mới';
            include 'views/admin/equipment/create.php';
        } else {
            $title = 'Thêm thiết bị mới';
            include 'views/admin/equipment/create.php';
        }
    }

    public function edit($id)
    {
        $equipment = $this->equipmentModel->findById($id);
        if (!$equipment) {
            $_SESSION['error'] = 'Không tìm thấy thiết bị';
            header('Location: /gym-php/admin/equipment');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate dữ liệu
            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'purchaseDate' => $_POST['purchaseDate'],
                'price' => floatval($_POST['price']),
                'status' => $_POST['status'],
                'lastMaintenanceDate' => !empty($_POST['lastMaintenanceDate']) ? $_POST['lastMaintenanceDate'] : null,
                'nextMaintenanceDate' => !empty($_POST['nextMaintenanceDate']) ? $_POST['nextMaintenanceDate'] : null
            ];

            // Xử lý upload ảnh mới
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imagePath = $this->handleImageUpload($_FILES['image']);
                if ($imagePath) {
                    // Xóa ảnh cũ nếu có
                    if (!empty($equipment['image_path'])) {
                        $oldImagePath = ROOT_PATH . $equipment['image_path'];
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                    $data['image_path'] = $imagePath;
                }
            }

            // Kiểm tra validate
            $errors = [];
            if (empty($data['name'])) {
                $errors['name'] = 'Vui lòng nhập tên thiết bị';
            }
            if (empty($data['purchaseDate'])) {
                $errors['purchaseDate'] = 'Vui lòng nhập ngày mua';
            }
            if ($data['price'] <= 0) {
                $errors['price'] = 'Giá phải lớn hơn 0';
            }

            if (empty($errors)) {
                if ($this->equipmentModel->update($id, $data)) {
                    $_SESSION['success'] = 'Cập nhật thiết bị thành công';
                    header('Location: /gym-php/admin/equipment');
                    exit();
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra';
                    header('Location: /gym-php/admin/equipment');
                    exit();
                }
            }

            $this->view('admin/equipment/edit', [
                'title' => 'Chỉnh sửa thiết bị',
                'equipment' => $equipment,
                'errors' => $errors
            ]);
        } else {
            $this->view('admin/equipment/edit', [
                'title' => 'Chỉnh sửa thiết bị',
                'equipment' => $equipment
            ]);
        }
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get equipment details before deletion
            $equipment = $this->equipmentModel->findById($id);

            if ($equipment && $this->equipmentModel->delete($id)) {
                // Delete associated image if it exists
                if (!empty($equipment['image_path'])) {
                    $imagePath = ROOT_PATH . $equipment['image_path'];
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
                $_SESSION['success'] = 'Xóa thiết bị thành công';
                header('Location: /gym-php/admin/equipment');
                exit();
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra';
                header('Location: /gym-php/admin/equipment');
                exit();
            }
        }
        header('Location: /gym-php/admin/equipment');
        exit();
    }
}
