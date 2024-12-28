<?php

namespace App\Models;
use App\Models\BaseModel;

use Core\Database;

class Membership extends BaseModel
{
    protected $table = 'membership_registrations';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    public function findAll()
    {
        $stmt = $this->db->query("
            SELECT mr.*, u.name as user_name, p.name as package_name 
            FROM membership_registrations mr
            JOIN users u ON mr.user_id = u.id
            JOIN packages p ON mr.package_id = p.id
            ORDER BY mr.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function findByUserId($userId)
    {
        $stmt = $this->db->prepare("
            SELECT mr.*, p.name as package_name, p.duration, p.price
            FROM membership_registrations mr
            JOIN packages p ON mr.package_id = p.id
            WHERE mr.user_id = :user_id
            ORDER BY mr.created_at DESC
        ");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO membership_registrations 
            (user_id, package_id, start_date, end_date, payment_method, status, created_at) 
            VALUES 
            (:user_id, :package_id, :start_date, :end_date, :payment_method, :status, :created_at)
        ");
        
        return $stmt->execute([
            ':user_id' => $data['user_id'],
            ':package_id' => $data['package_id'],
            ':start_date' => $data['start_date'],
            ':end_date' => $data['end_date'],
            ':payment_method' => $data['payment_method'],
            ':status' => $data['status'],
            ':created_at' => $data['created_at']
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE membership_registrations 
            SET status = :status,
                updated_at = NOW()
            WHERE id = :id
        ");
        
        return $stmt->execute([
            ':id' => $id,
            ':status' => $data['status']
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM membership_registrations WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function hasActiveOrPendingMembership($userId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM membership_registrations 
            WHERE user_id = :user_id 
            AND status IN ('ACTIVE', 'PENDING')
            AND end_date >= CURRENT_DATE()
        ");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
}