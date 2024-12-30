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
            SELECT 
                u.id,
                u.username,
                u.fullName,
                u.email,
                u.phone,
                u.dateOfBirth,
                u.sex,
                u.status,
                mp.name as package_name,
                mp.price,
                mr.startDate,
                mr.endDate,
                mr.status as membership_status,
                p.paymentMethod,
                p.paymentStatus,
                CASE 
                    WHEN mr.endDate >= CURRENT_DATE AND mr.status = 'ACTIVE' THEN 'active'
                    ELSE 'inactive'
                END as membership_active_status
            FROM users u
            LEFT JOIN membership_registrations mr ON u.id = mr.userId
            LEFT JOIN membership_packages mp ON mr.packageId = mp.id
            LEFT JOIN payments p ON mr.paymentId = p.id
            WHERE u.eRole = 'USER'
            ORDER BY u.createdAt DESC
        ");
        return $stmt->fetchAll();
    }

    public function findByUserId($userId)
    {
        $stmt = $this->db->prepare("
            SELECT mr.*, mp.name as package_name, mp.duration, mp.price,
                   p.paymentMethod, p.paymentStatus, p.qrImage
            FROM membership_registrations mr
            JOIN membership_packages mp ON mr.packageId = mp.id
            JOIN payments p ON mr.paymentId = p.id
            WHERE mr.userId = :userId
            ORDER BY mr.createdAt DESC
        ");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        try {
            $this->db->beginTransaction();

            // Create payment record first
            $paymentStmt = $this->db->prepare("
                INSERT INTO payments 
                (amount, paymentMethod, qrImage, paymentStatus, createdAt) 
                VALUES 
                (:amount, :paymentMethod, :qrImage, :paymentStatus, NOW())
            ");
            
            $paymentStmt->execute([
                ':amount' => $data['amount'],
                ':paymentMethod' => $data['paymentMethod'],
                ':qrImage' => $data['qrImage'] ?? null,
                ':paymentStatus' => 'PENDING'
            ]);
            
            $paymentId = $this->db->lastInsertId();

            // Create membership registration
            $stmt = $this->db->prepare("
                INSERT INTO membership_registrations 
                (userId, packageId, startDate, endDate, status, paymentId, createdAt) 
                VALUES 
                (:userId, :packageId, :startDate, :endDate, :status, :paymentId, NOW())
            ");
            
            $success = $stmt->execute([
                ':userId' => $data['userId'],
                ':packageId' => $data['packageId'],
                ':startDate' => $data['startDate'],
                ':endDate' => $data['endDate'],
                ':status' => 'ACTIVE',
                ':paymentId' => $paymentId
            ]);

            if ($success) {
                $this->db->commit();
                return $this->db->lastInsertId();
            } else {
                $this->db->rollBack();
                return false;
            }
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function update($id, $data)
    {
        try {
            $this->db->beginTransaction();

            // Get the current membership to get the payment ID
            $stmt = $this->db->prepare("SELECT paymentId FROM membership_registrations WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $membership = $stmt->fetch();

            if (!$membership) {
                throw new \Exception("Membership not found");
            }

            // Update payment information
            $paymentStmt = $this->db->prepare("
                UPDATE payments 
                SET amount = :amount,
                    paymentMethod = :paymentMethod,
                    qrImage = :qrImage,
                    paymentStatus = :paymentStatus,
                    updatedAt = NOW()
                WHERE id = :id
            ");
            
            $paymentSuccess = $paymentStmt->execute([
                ':id' => $membership['paymentId'],
                ':amount' => $data['amount'],
                ':paymentMethod' => $data['paymentMethod'],
                ':qrImage' => $data['qrImage'] ?? null,
                ':paymentStatus' => $data['paymentStatus']
            ]);

            if (!$paymentSuccess) {
                throw new \Exception("Failed to update payment");
            }

            // Update membership registration
            $membershipStmt = $this->db->prepare("
                UPDATE membership_registrations 
                SET packageId = :packageId,
                    startDate = :startDate,
                    endDate = :endDate,
                    status = :status,
                    updatedAt = NOW()
                WHERE id = :id
            ");
            
            $membershipSuccess = $membershipStmt->execute([
                ':id' => $id,
                ':packageId' => $data['packageId'],
                ':startDate' => $data['startDate'],
                ':endDate' => $data['endDate'],
                ':status' => $data['status']
            ]);

            if (!$membershipSuccess) {
                throw new \Exception("Failed to update membership");
            }

            $this->db->commit();
            return true;

        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function updatePaymentStatus($paymentId, $status)
    {
        $stmt = $this->db->prepare("
            UPDATE payments 
            SET paymentStatus = :status,
                paymentDate = NOW(),
                updatedAt = NOW()
            WHERE id = :id
        ");
        
        return $stmt->execute([
            ':id' => $paymentId,
            ':status' => $status
        ]);
    }

    public function delete($id)
    {
        try {
            $this->db->beginTransaction();

            // Get payment ID first
            $stmt = $this->db->prepare("SELECT paymentId FROM membership_registrations WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $membership = $stmt->fetch();

            if ($membership) {
                // Delete membership registration
                $deleteStmt = $this->db->prepare("DELETE FROM membership_registrations WHERE id = :id");
                $deleteStmt->bindParam(':id', $id);
                $deleteStmt->execute();

                // Delete associated payment
                $paymentStmt = $this->db->prepare("DELETE FROM payments WHERE id = :paymentId");
                $paymentStmt->bindParam(':paymentId', $membership['paymentId']);
                $paymentStmt->execute();
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function hasActiveOrPendingMembership($userId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM membership_registrations mr
            JOIN payments p ON mr.paymentId = p.id
            WHERE mr.userId = :userId 
            AND mr.status IN ('ACTIVE', 'FROZEN')
            AND mr.endDate >= CURRENT_DATE()
            AND p.paymentStatus IN ('COMPLETED', 'PENDING')
        ");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }

    public function getActiveMembership($userId)
    {
        $stmt = $this->db->prepare("
            SELECT mr.*, mp.name as package_name, mp.duration, mp.price,
                   p.paymentMethod, p.paymentStatus
            FROM membership_registrations mr
            JOIN membership_packages mp ON mr.packageId = mp.id
            JOIN payments p ON mr.paymentId = p.id
            WHERE mr.userId = :userId 
            AND mr.status = 'ACTIVE'
            AND mr.endDate >= CURRENT_DATE()
            AND p.paymentStatus = 'COMPLETED'
            ORDER BY mr.createdAt DESC
            LIMIT 1
        ");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function extendMembership($id, $endDate)
    {
        $stmt = $this->db->prepare("
            UPDATE membership_registrations 
            SET endDate = :endDate,
                updatedAt = NOW()
            WHERE id = :id
        ");
        
        return $stmt->execute([
            ':id' => $id,
            ':endDate' => $endDate
        ]);
    }

    public function freezeMembership($id)
    {
        $stmt = $this->db->prepare("
            UPDATE membership_registrations 
            SET status = 'FROZEN',
                freezeCount = freezeCount + 1,
                freezeDays = IFNULL(freezeDays, 0),
                updatedAt = NOW()
            WHERE id = :id
            AND status = 'ACTIVE'
            AND freezeCount < (
                SELECT maxFreeze 
                FROM membership_packages mp
                JOIN membership_registrations mr ON mp.id = mr.packageId
                WHERE mr.id = :id
            )
        ");
        
        return $stmt->execute([':id' => $id]);
    }

    public function unfreezeMembership($id, $frozenDays)
    {
        $stmt = $this->db->prepare("
            UPDATE membership_registrations 
            SET status = 'ACTIVE',
                freezeDays = freezeDays + :frozenDays,
                endDate = DATE_ADD(endDate, INTERVAL :frozenDays DAY),
                updatedAt = NOW()
            WHERE id = :id
            AND status = 'FROZEN'
        ");
        
        return $stmt->execute([
            ':id' => $id,
            ':frozenDays' => $frozenDays
        ]);
    }
}