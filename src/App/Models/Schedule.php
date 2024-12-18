<?php

namespace App\Models;

use App\Models\BaseModel;
use Core\Database;

class Schedule extends BaseModel
{
    protected $table = 'schedules';
    protected $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findAll()
    {
        $stmt = $this->db->query("SELECT * FROM schedules");
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $sql = "INSERT INTO schedules (trainerId, dayOfWeek, startTime, endTime, status) 
                VALUES (:trainerId, :dayOfWeek, :startTime, :endTime, :status)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE schedules 
                SET trainerId = :trainerId, dayOfWeek = :dayOfWeek, startTime = :startTime, endTime = :endTime, status = :status 
                WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM schedules WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM schedules WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getByTrainerId($trainerId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE trainer_id = :trainer_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['trainer_id' => $trainerId]);
        return $stmt->fetchAll();
    }

    public function updateTrainerSchedule($trainerId, $scheduleData)
    {
        $sql = "UPDATE {$this->table} SET schedule_data = :schedule_data WHERE trainer_id = :trainer_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'trainer_id' => $trainerId,
            'schedule_data' => json_encode($scheduleData)
        ]);
    }
} 