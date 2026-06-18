<?php
/**
 * Student Model
 * Handles student-related database operations
 */

class Student {
    private $conn;
    private $table = 'students';
    
    public $id;
    public $studentId;
    public $name;
    public $course;
    public $year;
    public $contact;
    public $email;
    public $user_id;
    public $status;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Create new student
     */
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (studentId, name, course, year, contact, email, user_id, status) 
                  VALUES (:studentId, :name, :course, :year, :contact, :email, :user_id, :status)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':studentId', $this->studentId);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':course', $this->course);
        $stmt->bindParam(':year', $this->year);
        $stmt->bindParam(':contact', $this->contact);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':status', $this->status);
        
        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        
        return false;
    }
    
    /**
     * Get student by ID
     */
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $this->populateFromRow($row);
            return true;
        }
        
        return false;
    }
    
    /**
     * Get student by student ID
     */
    public function getByStudentId($studentId) {
        $query = "SELECT * FROM " . $this->table . " WHERE studentId = :studentId LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':studentId', $studentId);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch();
        }
        
        return null;
    }
    
    /**
     * Get all students with pagination
     */
    public function getAll($limit = 100, $offset = 0) {
        $query = "SELECT * FROM " . $this->table . " 
                  ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Search students
     */
    public function search($keyword) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE studentId LIKE :keyword 
                  OR name LIKE :keyword 
                  OR course LIKE :keyword 
                  OR year LIKE :keyword 
                  OR contact LIKE :keyword
                  ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $searchTerm = "%{$keyword}%";
        $stmt->bindParam(':keyword', $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Filter students by course and/or year
     */
    public function filter($course = null, $year = null) {
        $conditions = [];
        $params = [];
        
        if ($course) {
            $conditions[] = "course = :course";
            $params[':course'] = $course;
        }
        
        if ($year) {
            $conditions[] = "year = :year";
            $params[':year'] = $year;
        }
        
        $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
        
        $query = "SELECT * FROM " . $this->table . " " . $whereClause . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Update student
     */
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET studentId = :studentId, name = :name, course = :course, 
                      year = :year, contact = :contact, email = :email, status = :status 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':studentId', $this->studentId);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':course', $this->course);
        $stmt->bindParam(':year', $this->year);
        $stmt->bindParam(':contact', $this->contact);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }
    
    /**
     * Delete student
     */
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    /**
     * Check if student ID exists
     */
    public function studentIdExists($studentId, $excludeId = null) {
        $query = "SELECT id FROM " . $this->table . " WHERE studentId = :studentId";
        
        if ($excludeId) {
            $query .= " AND id != :excludeId";
        }
        
        $query .= " LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':studentId', $studentId);
        
        if ($excludeId) {
            $stmt->bindParam(':excludeId', $excludeId);
        }
        
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Get total count
     */
    public function getTotalCount() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['total'];
    }
    
    /**
     * Get registration statistics by date range
     */
    public function getRegistrationStats($startDate, $endDate) {
        $query = "SELECT DATE(created_at) as date, COUNT(*) as count 
                  FROM " . $this->table . " 
                  WHERE DATE(created_at) BETWEEN :startDate AND :endDate 
                  GROUP BY DATE(created_at) 
                  ORDER BY date DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':startDate', $startDate);
        $stmt->bindParam(':endDate', $endDate);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get students registered today
     */
    public function getTodayCount() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " 
                  WHERE DATE(created_at) = CURDATE()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['total'];
    }
    
    /**
     * Populate object from database row
     */
    private function populateFromRow($row) {
        $this->id = $row['id'];
        $this->studentId = $row['studentId'];
        $this->name = $row['name'];
        $this->course = $row['course'];
        $this->year = $row['year'];
        $this->contact = $row['contact'];
        $this->email = $row['email'];
        $this->user_id = $row['user_id'];
        $this->status = $row['status'];
    }
}
?>
