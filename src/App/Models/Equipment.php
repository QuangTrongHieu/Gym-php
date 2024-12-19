<?php

namespace App\Models;

use App\Models\BaseModel;
use PDOException;

class Equipment extends BaseModel
{
    protected $table = 'equipment';

    public function __construct()
    {
        parent::__construct();
    }
    public function findAll()
    {
        $stmt = $this->db->query("SELECT * FROM equipment");
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        try {
            $sql = "INSERT INTO equipment (name, description, purchaseDate, price, status, lastMaintenanceDate, nextMaintenanceDate) 
                    VALUES (:name, :description, :purchaseDate, :price, :status, :lastMaintenanceDate, :nextMaintenanceDate)";
            
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([
                ':name' => $data['name'],
                ':description' => $data['description'],
                ':purchaseDate' => $data['purchaseDate'],
                ':price' => $data['price'],
                ':status' => $data['status'],
                ':lastMaintenanceDate' => $data['lastMaintenanceDate'],
                ':nextMaintenanceDate' => $data['nextMaintenanceDate']
            ]);
        } catch (PDOException $e) {
            // Xử lý lỗi
            return false;
        }
    }

    public function update($id, $data)
    {
        try {
            $sql = "UPDATE equipment 
                    SET name = :name,
                        description = :description,
                        purchaseDate = :purchaseDate,
                        price = :price,
                        status = :status,
                        lastMaintenanceDate = :lastMaintenanceDate,
                        nextMaintenanceDate = :nextMaintenanceDate
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([
                ':id' => $id,
                ':name' => $data['name'],
                ':description' => $data['description'],
                ':purchaseDate' => $data['purchaseDate'],
                ':price' => $data['price'],
                ':status' => $data['status'],
                ':lastMaintenanceDate' => $data['lastMaintenanceDate'],
                ':nextMaintenanceDate' => $data['nextMaintenanceDate']
            ]);
        } catch (PDOException $e) {
            // Xử lý lỗi
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $sql = "DELETE FROM equipment WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            // Xử lý lỗi
            return false;
        }
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM equipment WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
}