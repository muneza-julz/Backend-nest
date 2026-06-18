<?php
/**
 * User Model
 * Handles user-related database operations
 */

class User {
    private $conn;
    private $table = 'users';
    
    public $id;
    public $firstName;
    public $lastName;
    public $email;
    public $password;
    public $role;
    public $status;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Create new user with password hashing
     */
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (firstName, lastName, email, password, role, status) 
                  VALUES (:firstName, :lastName, :email, :password, :role, :status)";
        
        $stmt = $this->conn->prepare($query);
        
        // Hash password
        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);
        
        // Bind parameters
        $stmt->bindParam(':firstName', $this->firstName);
        $stmt->bindParam(':lastName', $this->lastName);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':status', $this->status);
        
        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        
        return false;
    }
    
    /**
     * Authenticate user
     */
    public function authenticate($email, $password) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE email = :email AND status = 'active' LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            
            if (password_verify($password, $row['password'])) {
                $this->id = $row['id'];
                $this->firstName = $row['firstName'];
                $this->lastName = $row['lastName'];
                $this->email = $row['email'];
                $this->role = $row['role'];
                $this->status = $row['status'];
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get user by ID
     */
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $this->id = $row['id'];
            $this->firstName = $row['firstName'];
            $this->lastName = $row['lastName'];
            $this->email = $row['email'];
            $this->role = $row['role'];
            $this->status = $row['status'];
            return true;
        }
        
        return false;
    }
    
    /**
     * Get all users
     */
    public function getAll() {
        $query = "SELECT id, firstName, lastName, email, role, status, created_at 
                  FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Update user
     */
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET firstName = :firstName, lastName = :lastName, 
                      email = :email, role = :role, status = :status 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':firstName', $this->firstName);
        $stmt->bindParam(':lastName', $this->lastName);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }
    
    /**
     * Delete user
     */
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    /**
     * Check if email exists
     */
    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Update password
     */
    public function updatePassword($id, $newPassword) {
        $query = "UPDATE " . $this->table . " SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
}
?>
