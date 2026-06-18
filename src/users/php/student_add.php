<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'classes/Auth.php';
require_once 'classes/Student.php';
require_once 'classes/Validator.php';

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);

$auth->requireAnyRole([ROLE_ADMIN, ROLE_REGISTRAR]);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!$auth->verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request. Please try again.';
    } else {
        $validator = new Validator();
        
        // Sanitize inputs
        $studentId = $validator->sanitizeString($_POST['studentId'] ?? '');
        $name = $validator->sanitizeString($_POST['name'] ?? '');
        $course = $validator->sanitizeString($_POST['course'] ?? '');
        $year = $validator->sanitizeString($_POST['year'] ?? '');
        $contact = $validator->sanitizeString($_POST['contact'] ?? '');
        $email = $validator->sanitizeEmail($_POST['email'] ?? '');
        
        // Validate inputs
        $validator->validateRequired($studentId, 'Student ID');
        $validator->validateRequired($name, 'Name');
        $validator->validateRequired($course, 'Course');
        $validator->validateRequired($year, 'Year');
        $validator->validateRequired($contact, 'Contact');
        
        if (!empty($email)) {
            $validator->validateEmail($email);
        }
        
        // Check if student ID already exists
        $student = new Student($db);
        if ($student->studentIdExists($studentId)) {
            $validator->addError('Student ID already exists');
        }
        
        if (!$validator->hasErrors()) {
            $student->studentId = $studentId;
            $student->name = $name;
            $student->course = $course;
            $student->year = $year;
            $student->contact = $contact;
            $student->email = $email;
            $student->user_id = $_SESSION['user_id'];
            $student->status = 'active';
            
            if ($student->create()) {
                $success = 'Student added successfully!';
                // Clear form
                $studentId = $name = $course = $year = $contact = $email = '';
            } else {
                $error = 'Failed to add student. Please try again.';
            }
        } else {
            $error = implode('<br>', $validator->getErrors());
        }
    }
}

$pageTitle = 'Add Student';
include 'includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-user-plus"></i> Add New Student</h1>
        <a href="students.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Students
        </a>
    </div>
    
    <div class="form-section">
        <?php if ($error): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo $error; ?>
        </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo $success; ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="" class="form">
            <input type="hidden" name="csrf_token" value="<?php echo $auth->generateCsrfToken(); ?>">
            
            <div class="form-row">
                <div class="form-group">
                    <label for="studentId"><i class="fas fa-id-card"></i> Student ID *</label>
                    <input type="text" id="studentId" name="studentId" class="form-control" 
                           value="<?php echo isset($studentId) ? htmlspecialchars($studentId) : ''; ?>" 
                           placeholder="e.g., STU-2024-001" required>
                </div>
                
                <div class="form-group">
                    <label for="name"><i class="fas fa-user"></i> Full Name *</label>
                    <input type="text" id="name" name="name" class="form-control" 
                           value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" 
                           placeholder="Enter full name" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="course"><i class="fas fa-book"></i> Course *</label>
                    <select id="course" name="course" class="form-control" required>
                        <option value="">Select Course</option>
                        <option value="Computer Science" <?php echo (isset($course) && $course === 'Computer Science') ? 'selected' : ''; ?>>Computer Science</option>
                        <option value="Engineering" <?php echo (isset($course) && $course === 'Engineering') ? 'selected' : ''; ?>>Engineering</option>
                        <option value="Business Administration" <?php echo (isset($course) && $course === 'Business Administration') ? 'selected' : ''; ?>>Business Administration</option>
                        <option value="Medicine" <?php echo (isset($course) && $course === 'Medicine') ? 'selected' : ''; ?>>Medicine</option>
                        <option value="Arts" <?php echo (isset($course) && $course === 'Arts') ? 'selected' : ''; ?>>Arts</option>
                        <option value="Science" <?php echo (isset($course) && $course === 'Science') ? 'selected' : ''; ?>>Science</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="year"><i class="fas fa-calendar-alt"></i> Year Level *</label>
                    <select id="year" name="year" class="form-control" required>
                        <option value="">Select Year</option>
                        <option value="1st Year" <?php echo (isset($year) && $year === '1st Year') ? 'selected' : ''; ?>>1st Year</option>
                        <option value="2nd Year" <?php echo (isset($year) && $year === '2nd Year') ? 'selected' : ''; ?>>2nd Year</option>
                        <option value="3rd Year" <?php echo (isset($year) && $year === '3rd Year') ? 'selected' : ''; ?>>3rd Year</option>
                        <option value="4th Year" <?php echo (isset($year) && $year === '4th Year') ? 'selected' : ''; ?>>4th Year</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="contact"><i class="fas fa-phone"></i> Contact Number *</label>
                    <input type="text" id="contact" name="contact" class="form-control" 
                           value="<?php echo isset($contact) ? htmlspecialchars($contact) : ''; ?>" 
                           placeholder="e.g., +1234567890" required>
                </div>
                
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" 
                           value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" 
                           placeholder="student@email.com">
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Add Student
                </button>
                <a href="students.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
