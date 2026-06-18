<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'classes/Auth.php';
require_once 'classes/Student.php';

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);

$auth->requireRole(ROLE_STUDENT);

$student = new Student($db);
$userStudents = [];

// Get students associated with this user (if any)
$query = "SELECT * FROM students WHERE user_id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$userStudents = $stmt->fetchAll();

$pageTitle = 'Student Dashboard';
include 'includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-user-graduate"></i> Student Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
    </div>
    
    <div class="dashboard-cards">
        <div class="card card-primary">
            <div class="card-icon">
                <i class="fas fa-id-badge"></i>
            </div>
            <div class="card-content">
                <h3><?php echo count($userStudents); ?></h3>
                <p>My Registrations</p>
            </div>
        </div>
        
        <div class="card card-info">
            <div class="card-icon">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="card-content">
                <h3><?php echo ucfirst($_SESSION['user_role']); ?></h3>
                <p>Account Type</p>
            </div>
        </div>
    </div>
    
    <?php if (!empty($userStudents)): ?>
    <div class="section">
        <div class="section-header">
            <h2><i class="fas fa-list"></i> My Student Records</h2>
        </div>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Course</th>
                        <th>Year</th>
                        <th>Status</th>
                        <th>Registered On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($userStudents as $s): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($s['studentId']); ?></td>
                        <td><?php echo htmlspecialchars($s['name']); ?></td>
                        <td><?php echo htmlspecialchars($s['course']); ?></td>
                        <td><?php echo htmlspecialchars($s['year']); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $s['status'] === 'active' ? 'success' : 'danger'; ?>">
                                <?php echo ucfirst($s['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M j, Y', strtotime($s['created_at'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php else: ?>
    <div class="section">
        <div style="text-align: center; padding: 3rem;">
            <i class="fas fa-inbox" style="font-size: 4rem; color: #95a5a6; margin-bottom: 1rem;"></i>
            <h3>No Student Records Found</h3>
            <p>You don't have any student registrations yet.</p>
            <p>Contact the registrar's office to complete your student registration.</p>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
