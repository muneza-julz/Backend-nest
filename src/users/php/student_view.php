<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'classes/Auth.php';
require_once 'classes/Student.php';

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);

$auth->requireAnyRole([ROLE_ADMIN, ROLE_REGISTRAR]);

$studentId = $_GET['id'] ?? null;

if (!$studentId) {
    header('Location: students.php');
    exit();
}

$student = new Student($db);
if (!$student->getById($studentId)) {
    header('Location: students.php');
    exit();
}

$pageTitle = 'View Student';
include 'includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-eye"></i> Student Details</h1>
        <div>
            <a href="student_edit.php?id=<?php echo $student->id; ?>" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="students.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Students
            </a>
        </div>
    </div>
    
    <div class="section">
        <div class="student-details">
            <div class="detail-row">
                <div class="detail-label"><i class="fas fa-id-card"></i> Student ID:</div>
                <div class="detail-value"><?php echo htmlspecialchars($student->studentId); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label"><i class="fas fa-user"></i> Full Name:</div>
                <div class="detail-value"><?php echo htmlspecialchars($student->name); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label"><i class="fas fa-book"></i> Course:</div>
                <div class="detail-value"><?php echo htmlspecialchars($student->course); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label"><i class="fas fa-calendar-alt"></i> Year Level:</div>
                <div class="detail-value"><?php echo htmlspecialchars($student->year); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label"><i class="fas fa-phone"></i> Contact Number:</div>
                <div class="detail-value"><?php echo htmlspecialchars($student->contact); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label"><i class="fas fa-envelope"></i> Email Address:</div>
                <div class="detail-value"><?php echo htmlspecialchars($student->email ?? 'N/A'); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label"><i class="fas fa-toggle-on"></i> Status:</div>
                <div class="detail-value">
                    <span class="badge badge-<?php echo $student->status === 'active' ? 'success' : 'danger'; ?>">
                        <?php echo ucfirst($student->status); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
