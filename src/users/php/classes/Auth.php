<?php
/**
 * Authentication Class
 * Handles user authentication and session management
 */

class Auth {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Login user
     */
    public function login($email, $password) {
        $user = new User($this->conn);
        
        if ($user->authenticate($email, $password)) {
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);
            
            // Set session variables
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_email'] = $user->email;
            $_SESSION['user_name'] = $user->firstName . ' ' . $user->lastName;
            $_SESSION['user_role'] = $user->role;
            $_SESSION['logged_in'] = true;
            $_SESSION['last_activity'] = time();
            
            // Generate CSRF token
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            
            // Log activity
            $this->logActivity($user->id, 'login', 'User logged in');
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Logout user
     */
    public function logout() {
        if (isset($_SESSION['user_id'])) {
            $this->logActivity($_SESSION['user_id'], 'logout', 'User logged out');
        }
        
        // Destroy session
        session_unset();
        session_destroy();
        
        // Start new session
        session_start();
        session_regenerate_id(true);
    }
    
    /**
     * Check if user is authenticated
     */
    public function isAuthenticated() {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            return false;
        }
        
        // Check session timeout
        if (isset($_SESSION['last_activity'])) {
            $inactive = time() - $_SESSION['last_activity'];
            
            if ($inactive > SESSION_TIMEOUT) {
                $this->logout();
                return false;
            }
        }
        
        // Update last activity time
        $_SESSION['last_activity'] = time();
        
        return true;
    }
    
    /**
     * Check if user has specific role
     */
    public function hasRole($role) {
        if (!$this->isAuthenticated()) {
            return false;
        }
        
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
    }
    
    /**
     * Check if user has any of the specified roles
     */
    public function hasAnyRole($roles) {
        if (!$this->isAuthenticated()) {
            return false;
        }
        
        return isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], $roles);
    }
    
    /**
     * Require authentication
     */
    public function requireAuth() {
        if (!$this->isAuthenticated()) {
            header('Location: login.php');
            exit();
        }
    }
    
    /**
     * Require specific role
     */
    public function requireRole($role) {
        $this->requireAuth();
        
        if (!$this->hasRole($role)) {
            header('Location: unauthorized.php');
            exit();
        }
    }
    
    /**
     * Require any of the specified roles
     */
    public function requireAnyRole($roles) {
        $this->requireAuth();
        
        if (!$this->hasAnyRole($roles)) {
            header('Location: unauthorized.php');
            exit();
        }
    }
    
    /**
     * Generate CSRF token
     */
    public function generateCsrfToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Verify CSRF token
     */
    public function verifyCsrfToken($token) {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Log user activity
     */
    private function logActivity($userId, $action, $description) {
        $query = "INSERT INTO activity_logs (user_id, action, description, ip_address) 
                  VALUES (:user_id, :action, :description, :ip_address)";
        
        $stmt = $this->conn->prepare($query);
        
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':action', $action);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':ip_address', $ipAddress);
        
        $stmt->execute();
    }
    
    /**
     * Get current user ID
     */
    public function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Get current user role
     */
    public function getUserRole() {
        return $_SESSION['user_role'] ?? null;
    }
}
?>
