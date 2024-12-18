<?php

namespace App\Models;
use App\Models\BaseModel;
use PDOException;

class Package extends BaseModel
{
    protected $table = 'packages';

    public function __construct()
    {
        parent::__construct();
    }

    public function findAll()
    {
        $stmt = $this->db->query("SELECT * FROM membership_packages");
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        try {
            $sql = "INSERT INTO membership_packages (name, description, price, duration, maxFreeze, benefits, status) 
                    VALUES (:name, :description, :price, :duration, :maxFreeze, :benefits, :status)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':name' => $data['name'],
                ':description' => $data['description'],
                ':price' => $data['price'],
                ':duration' => $data['duration'],
                ':maxFreeze' => $data['maxFreeze'] ?? 0,
                ':benefits' => $data['benefits'] ?? '',
                ':status' => $data['status'] ?? 'ACTIVE'
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function update($id, $data)
    {
        try {
            $sql = "UPDATE membership_packages 
                    SET name = :name, description = :description, price = :price, 
                        duration = :duration, maxFreeze = :maxFreeze, 
                        benefits = :benefits, status = :status 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':name' => $data['name'],
                ':description' => $data['description'],
                ':price' => $data['price'],
                ':duration' => $data['duration'],
                ':maxFreeze' => $data['maxFreeze'] ?? 0,
                ':benefits' => $data['benefits'] ?? '',
                ':status' => $data['status'] ?? 'ACTIVE'
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $sql = "DELETE FROM membership_packages WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getAllPackages()
    {
        $sql = "SELECT * FROM membership_packages WHERE status = 'ACTIVE'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findByTrainerId($trainerId)
    {
        $sql = "SELECT p.* FROM membership_packages p 
                JOIN trainer_packages tp ON p.id = tp.package_id 
                WHERE tp.trainer_id = :trainer_id AND p.status = 'ACTIVE'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['trainer_id' => $trainerId]);
        return $stmt->fetchAll();
    }

    public function getAllActivePackages()
    {
        $sql = "SELECT * FROM membership_packages WHERE status = 'ACTIVE'";
        return $this->db->query($sql)->fetchAll();
    }
} 