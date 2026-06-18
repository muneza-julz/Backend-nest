<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'classes/Auth.php';
require_once 'classes/Student.php';

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);

// Require authentication and admin/registrar role
$auth->requireAnyRole([ROLE_ADMIN, ROLE_REGISTRAR]);

$student = new Student($db);

// Get statistics
$totalStudents = $student->getTotalCount();
$todayRegistrations = $student->getTodayCount();

// Get recent registrations (last 7 days)
$startDate = date('Y-m-d', strtotime('-7 days'));
$endDate = date('Y-m-d');
$weeklyStats = $student->getRegistrationStats($startDate, $endDate);

// Get recent students
$recentStudents = $student->getAll(10, 0);

$pageTitle = 'Dashboard';
include 'includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-home"></i> Dashboard</h1>
        <p>Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
    </div>
    
    <div class="dashboard-cards">
        <div class="card card-primary">
            <div class="card-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="card-content">
                <h3><?php echo $totalStudents; ?></h3>
                <p>Total Students</p>
            </div>
        </div>
        
        <div class="card card-success">
            <div class="card-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="card-content">
                <h3><?php echo $todayRegistrations; ?></h3>
                <p>Registered Today</p>
            </div>
        </div>
        
        <div class="card card-info">
            <div class="card-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="card-content">
                <h3><?php echo count($weeklyStats); ?></h3>
                <p>Active Days (7d)</p>
            </div>
        </div>
        
        <div class="card card-warning">
            <div class="card-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="card-content">
                <h3><?php echo $_SESSION['user_role']; ?></h3>
                <p>Your Role</p>
            </div>
        </div>
    </div>
    
    <div class="dashboard-content">
        <div class="section">
            <div class="section-header">
                <h2><i class="fas fa-chart-bar"></i> Registration Statistics (Last 7 Days)</h2>
            </div>
            <div class="stats-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Registrations</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($weeklyStats)): ?>
                            <?php foreach ($weeklyStats as $stat): ?>
                            <tr>
                                <td><?php echo date('F j, Y', strtotime($stat['date'])); ?></td>
                                <td><span class="badge badge-primary"><?php echo $stat['count']; ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" class="text-center">No registrations in the last 7 days</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="section">
            <div class="section-header">
                <h2><i class="fas fa-user-graduate"></i> Recent Student Registrations</h2>
                <a href="students.php" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Year</th>
                            <th>Registered On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recentStudents)): ?>
                            <?php foreach ($recentStudents as $s): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($s['studentId']); ?></td>
                                <td><?php echo htmlspecialchars($s['name']); ?></td>
                                <td><?php echo htmlspecialchars($s['course']); ?></td>
                                <td><?php echo htmlspecialchars($s['year']); ?></td>
                                <td><?php echo date('M j, Y', strtotime($s['created_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No students registered yet</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
