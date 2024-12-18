<?php

namespace App\Models;

class Trainer extends BaseModel
{
    protected $table = 'trainers';

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
        $sql = "INSERT INTO trainers (username, fullName, dateOfBirth, sex, phone, 
                email, specialization, experience, certification, salary, password, status) 
                VALUES (:username, :fullName, :dateOfBirth, :sex, :phone, 
                :email, :specialization, :experience, :certification, :salary, :password, 'active')";
        
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $this->db->query($sql, $data);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $fields = [];
        $params = ['id' => $id];
        foreach ($data as $key => $value) {
            if ($key !== 'id') {
                $fields[] = "$key = :$key";
                $params[$key] = $value;
            }
        }
        if (isset($data['password'])) {
            $params['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        $sql = "UPDATE trainers SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
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
}