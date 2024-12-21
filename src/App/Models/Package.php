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

    public function findById($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM membership_packages WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error finding package by ID: " . $e->getMessage());
            return false;
        }
    }

    public function create($data)
    {
        try {
            $sql = "INSERT INTO membership_packages (name, description, duration, price, status) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $data['name'],
                $data['description'],
                $data['duration'],
                $data['price'],
                $data['status'] ?? 'active'
            ]);
        } catch (PDOException $e) {
            error_log("Error creating package: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data)
    {
        try {
            $sql = "UPDATE membership_packages SET name = ?, description = ?, duration = ?, price = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $data['name'],
                $data['description'],
                $data['duration'],
                $data['price'],
                $id
            ]);
        } catch (PDOException $e) {
            error_log("Error updating package: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM membership_packages WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error deleting package: " . $e->getMessage());
            return false;
        }
    }

    public function findActivePackages()
    {
        try {
            $stmt = $this->db->query("SELECT * FROM membership_packages WHERE status = 'active' ORDER BY price ASC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error in findActivePackages: " . $e->getMessage());
            return [];
        }
    }
}