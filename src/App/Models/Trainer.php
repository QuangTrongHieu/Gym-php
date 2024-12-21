<?php

namespace App\Models;

use Core\ImageUploader;

class Trainer extends BaseModel
{
    protected $table = 'trainers';
    protected $uploadPath = 'public/uploads/trainers';

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllTrainers() {
        $sql = "SELECT * FROM trainers WHERE status = 'active'";
        return $this->db->query($sql)->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT * FROM trainers WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (username, password, fullName, dateOfBirth, 
                sex, phone, email, specialization, experience, certification, salary, 
                eRole, status, avatar) 
                VALUES (:username, :password, :fullName, :dateOfBirth, :sex, :phone, 
                :email, :specialization, :experience, :certification, :salary, 
                :eRole, :status, :avatar)";
                
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($id, $data) {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $data['id'] = $id;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id) {
        $sql = "UPDATE trainers SET status = 'inactive' WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }

    public function getPerformanceStats($trainerId) {
        $sql = "SELECT 
                COUNT(DISTINCT pt.client_id) as total_clients,
                COUNT(ts.id) as total_sessions,
                COUNT(CASE WHEN ts.status = 'completed' THEN 1 END) as completed_sessions,
                COUNT(CASE WHEN ts.status = 'cancelled' THEN 1 END) as cancelled_sessions
                FROM trainers t
                LEFT JOIN pt_registrations pt ON t.id = pt.trainer_id
                LEFT JOIN training_sessions ts ON pt.id = ts.registration_id
                WHERE t.id = :trainerId
                GROUP BY t.id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['trainerId' => $trainerId]);
        return $stmt->fetch();
    }

    public function count($whereClause = '', $params = [])
    {
        $sql = "SELECT COUNT(*) as total FROM trainers t " . $whereClause;
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return (int)$result['total'];
    }

    public function findWithFilters($conditions = [], $params = [], $limit = 10, $offset = 0)
    {
        $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
        $sql = "SELECT * FROM trainers t {$whereClause} LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getSpecialties($trainerId)
    {
        $sql = "SELECT specialization FROM trainer_specialties 
                WHERE trainer_id = :trainer_id AND status = 'active'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['trainer_id' => $trainerId]);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function findAll()
    {
        $stmt = $this->db->query("SELECT * FROM trainers");
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function findByUsername($username)
    {
        $sql = "SELECT * FROM {$this->table} WHERE username = :username AND status = 'active'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    public function findActiveTrainers()
    {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE status = 'ACTIVE'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error finding active trainers: " . $e->getMessage());
            return [];
        }
    }

    public function uploadAvatar($file) {
        try {
            $uploader = new ImageUploader($this->uploadPath);
            return $uploader->upload($file);
        } catch (\RuntimeException $e) {
            throw new \RuntimeException('Avatar upload failed: ' . $e->getMessage());
        }
    }

    public function updateAvatar($id, $file) {
        try {
            // Get current avatar
            $trainer = $this->getById($id);
            $oldAvatar = $trainer['avatar'] ?? null;

            // Upload new avatar
            $uploader = new ImageUploader($this->uploadPath);
            $newAvatar = $uploader->upload($file);

            // Delete old avatar if exists
            if ($oldAvatar) {
                $uploader->delete($oldAvatar);
            }

            // Update avatar in database
            $this->update($id, ['avatar' => $newAvatar]);
            
            return $newAvatar;
        } catch (\RuntimeException $e) {
            throw new \RuntimeException('Avatar update failed: ' . $e->getMessage());
        }
    }

    public function deleteAvatar($fileName) {
        $uploader = new ImageUploader($this->uploadPath);
        return $uploader->delete($fileName);
    }
}