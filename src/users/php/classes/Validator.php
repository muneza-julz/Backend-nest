<?php
/**
 * Validator Class
 * Handles input validation and sanitization
 */

class Validator {
    private $errors = [];
    
    /**
     * Validate email
     */
    public function validateEmail($email, $fieldName = 'Email') {
        if (empty($email)) {
            $this->errors[] = "$fieldName is required";
            return false;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "$fieldName is not valid";
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate required field
     */
    public function validateRequired($value, $fieldName) {
        if (empty($value)) {
            $this->errors[] = "$fieldName is required";
            return false;
        }
        return true;
    }
    
    /**
     * Validate password strength
     */
    public function validatePassword($password, $fieldName = 'Password') {
        if (empty($password)) {
            $this->errors[] = "$fieldName is required";
            return false;
        }
        
        if (strlen($password) < PASSWORD_MIN_LENGTH) {
            $this->errors[] = "$fieldName must be at least " . PASSWORD_MIN_LENGTH . " characters long";
            return false;
        }
        
        // Check for at least one uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            $this->errors[] = "$fieldName must contain at least one uppercase letter";
            return false;
        }
        
        // Check for at least one lowercase letter
        if (!preg_match('/[a-z]/', $password)) {
            $this->errors[] = "$fieldName must contain at least one lowercase letter";
            return false;
        }
        
        // Check for at least one number
        if (!preg_match('/[0-9]/', $password)) {
            $this->errors[] = "$fieldName must contain at least one number";
            return false;
        }
        
        // Check for at least one special character
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $this->errors[] = "$fieldName must contain at least one special character";
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate length
     */
    public function validateLength($value, $min, $max, $fieldName) {
        $length = strlen($value);
        
        if ($length < $min) {
            $this->errors[] = "$fieldName must be at least $min characters long";
            return false;
        }
        
        if ($length > $max) {
            $this->errors[] = "$fieldName must not exceed $max characters";
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate phone number
     */
    public function validatePhone($phone, $fieldName = 'Phone number') {
        if (empty($phone)) {
            $this->errors[] = "$fieldName is required";
            return false;
        }
        
        // Basic phone validation (adjust pattern as needed)
        if (!preg_match('/^[0-9+\-\s()]+$/', $phone)) {
            $this->errors[] = "$fieldName is not valid";
            return false;
        }
        
        return true;
    }
    
    /**
     * Sanitize string
     */
    public function sanitizeString($value) {
        return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Sanitize email
     */
    public function sanitizeEmail($email) {
        return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    }
    
    /**
     * Get validation errors
     */
    public function getErrors() {
        return $this->errors;
    }
    
    /**
     * Check if there are errors
     */
    public function hasErrors() {
        return !empty($this->errors);
    }
    
    /**
     * Clear errors
     */
    public function clearErrors() {
        $this->errors = [];
    }
    
    /**
     * Add custom error
     */
    public function addError($error) {
        $this->errors[] = $error;
    }
}
?>
