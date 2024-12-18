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
            $stmt = $this->db->query("SELECT * FROM membership_registrations");
            return $stmt->fetchAll();
        }

        public function create($data)
        {
            $stmt = $this->db->prepare("INSERT INTO membership_registrations (userId, packageId, startDate, endDate, status, paymentId) VALUES (:userId, :packageId, :startDate, :endDate, :status, :paymentId)");
            $stmt->bindParam(':userId', $data['userId']);
            $stmt->bindParam(':packageId', $data['packageId']);
            $stmt->bindParam(':startDate', $data['startDate']);
            $stmt->bindParam(':endDate', $data['endDate']);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':paymentId', $data['paymentId']);
            return $stmt->execute();
        }

        public function update($id, $data)
        {
            $stmt = $this->db->prepare("UPDATE membership_registrations SET userId = :userId, packageId = :packageId, startDate = :startDate, endDate = :endDate, status = :status, paymentId = :paymentId WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':userId', $data['userId']);
            $stmt->bindParam(':packageId', $data['packageId']);
            $stmt->bindParam(':startDate', $data['startDate']);
            $stmt->bindParam(':endDate', $data['endDate']);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':paymentId', $data['paymentId']);
            return $stmt->execute();
        }

        public function delete($id)
        {
            $stmt = $this->db->prepare("DELETE FROM membership_registrations WHERE id = :id");
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        }

        public function find($id)
        {
            return $this->findById($id);
        }
    } 