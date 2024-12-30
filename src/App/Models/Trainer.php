<?php

namespace App\Models;

class Trainer extends BaseModel
{
    protected $table = 'trainers';

    public function __construct()
    {
        parent::__construct();
        // Set default fetch mode to FETCH_ASSOC
        $this->db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    }

    // Transaction methods
    public function beginTransaction()
    {
        return $this->db->beginTransaction();
    }

    public function commit()
    {
        return $this->db->commit();
    }

    public function rollBack()
    {
        return $this->db->rollBack();
    }

    public function inTransaction()
    {
        return $this->db->inTransaction();
    }

    public function getAllTrainers() {
        $sql = "SELECT * FROM trainers WHERE status = 'active'";
        return $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM trainers WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
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
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function count($whereClause = '', $params = [])
    {
        $sql = "SELECT COUNT(*) as total FROM trainers t " . $whereClause;
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
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
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
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
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        return $this->getById($id);
    }

    public function findByUsername($username)
    {
        $sql = "SELECT * FROM {$this->table} WHERE username = :username AND status = 'active'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
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

    public function getTrainerByUsername($username)
    {
        $sql = "SELECT * FROM {$this->table} WHERE username = :username AND status = 'ACTIVE'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getTrainerById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id AND status = 'ACTIVE'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function updateTrainer($id, $data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $data['id'] = $id;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function getTrainingSchedule($trainerId)
    {
        $sql = "SELECT s.*, m.fullName as memberName 
                FROM schedules s 
                JOIN members m ON s.memberId = m.id 
                WHERE s.trainerId = :trainerId 
                AND s.date >= CURRENT_DATE 
                ORDER BY s.date, s.startTime";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['trainerId' => $trainerId]);
        $schedules = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (empty($schedules)) {
            return "Không có lịch tập nào sắp tới";
        }

        $formattedSchedule = "";
        foreach ($schedules as $schedule) {
            $date = date('d/m/Y', strtotime($schedule['date']));
            $startTime = date('H:i', strtotime($schedule['startTime']));
            $endTime = date('H:i', strtotime($schedule['endTime']));
            
            $formattedSchedule .= "Ngày: {$date}\n";
            $formattedSchedule .= "Thời gian: {$startTime} - {$endTime}\n";
            $formattedSchedule .= "Học viên: {$schedule['memberName']}\n\n";
        }

        return rtrim($formattedSchedule);
    }
}