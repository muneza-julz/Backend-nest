<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'classes/Auth.php';
require_once 'classes/Student.php';

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);

$auth->requireRole(ROLE_ADMIN);

$student = new Student($db);

// Handle date range selection
$reportType = $_GET['type'] ?? 'daily';
$selectedDate = $_GET['date'] ?? date('Y-m-d');

switch ($reportType) {
    case 'daily':
        $startDate = $selectedDate;
        $endDate = $selectedDate;
        break;
    case 'weekly':
        $startDate = date('Y-m-d', strtotime('-7 days', strtotime($selectedDate)));
        $endDate = $selectedDate;
        break;
    case 'monthly':
        $startDate = date('Y-m-01', strtotime($selectedDate));
        $endDate = date('Y-m-t', strtotime($selectedDate));
        break;
    default:
        $startDate = $selectedDate;
        $endDate = $selectedDate;
}

$stats = $student->getRegistrationStats($startDate, $endDate);
$totalCount = array_sum(array_column($stats, 'count'));

$pageTitle = 'Reports';
include 'includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-chart-bar"></i> Registration Reports</h1>
    </div>
    
    <div class="section">
        <div class="section-header">
            <h2>Generate Report</h2>
        </div>
        
        <form method="GET" action="" class="filters-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="type">Report Type</label>
                    <select name="type" id="type" class="form-control">
                        <option value="daily" <?php echo $reportType === 'daily' ? 'selected' : ''; ?>>Daily</option>
                        <option value="weekly" <?php echo $reportType === 'weekly' ? 'selected' : ''; ?>>Weekly</option>
                        <option value="monthly" <?php echo $reportType === 'monthly' ? 'selected' : ''; ?>>Monthly</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="date">Select Date</label>
                    <input type="date" name="date" id="date" class="form-control" 
                           value="<?php echo $selectedDate; ?>">
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sync"></i> Generate
                </button>
            </div>
        </form>
    </div>
    
    <div class="section">
        <div class="section-header">
            <h2><?php echo ucfirst($reportType); ?> Report</h2>
            <p>Period: <?php echo date('F j, Y', strtotime($startDate)); ?> - <?php echo date('F j, Y', strtotime($endDate)); ?></p>
        </div>
        
        <div class="report-summary">
            <div class="report-card">
                <div class="report-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="report-content">
                    <h3><?php echo $totalCount; ?></h3>
                    <p>Total Registrations</p>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Registrations</th>
                        <th>Day of Week</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($stats)): ?>
                        <?php foreach ($stats as $stat): ?>
                        <tr>
                            <td><?php echo date('F j, Y', strtotime($stat['date'])); ?></td>
                            <td><span class="badge badge-primary"><?php echo $stat['count']; ?></span></td>
                            <td><?php echo date('l', strtotime($stat['date'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="total-row">
                            <td><strong>Total</strong></td>
                            <td><strong><span class="badge badge-success"><?php echo $totalCount; ?></span></strong></td>
                            <td></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">No registrations found for this period</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
