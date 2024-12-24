<?php

namespace App\Models;

use App\Models\BaseModel;
use PDO;

class Schedule extends BaseModel
{
    protected $table = 'schedules';

    public function getAllSchedulesWithNames()
    {
        $sql = "SELECT s.*, u.fullName as user_name, t.fullName as trainer_name 
                FROM {$this->table} s
                LEFT JOIN users u ON s.user_id = u.id
                LEFT JOIN trainers t ON s.trainer_id = t.id
                ORDER BY s.training_date DESC, s.start_time ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (user_id, trainer_id, training_date, start_time, end_time, notes, status) 
                VALUES (:user_id, :trainer_id, :training_date, :start_time, :end_time, :notes, :status)";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':user_id' => $data['user_id'],
            ':trainer_id' => $data['trainer_id'],
            ':training_date' => $data['training_date'],
            ':start_time' => $data['start_time'],
            ':end_time' => $data['end_time'],
            ':notes' => $data['notes'],
            ':status' => $data['status']
        ]);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} 
                SET user_id = :user_id,
                    trainer_id = :trainer_id,
                    training_date = :training_date,
                    start_time = :start_time,
                    end_time = :end_time,
                    notes = :notes,
                    status = :status
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':id' => $id,
            ':user_id' => $data['user_id'],
            ':trainer_id' => $data['trainer_id'],
            ':training_date' => $data['training_date'],
            ':start_time' => $data['start_time'],
            ':end_time' => $data['end_time'],
            ':notes' => $data['notes'],
            ':status' => $data['status']
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}