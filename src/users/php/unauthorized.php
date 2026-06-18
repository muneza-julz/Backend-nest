<?php
require_once 'config/config.php';

$pageTitle = 'Unauthorized';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle . ' - ' . APP_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <i class="fas fa-exclamation-triangle" style="color: #e74c3c;"></i>
                <h1>Access Denied</h1>
            </div>
            
            <div class="auth-form" style="text-align: center;">
                <h2>Unauthorized Access</h2>
                <p>You don't have permission to access this page.</p>
                <p>Please contact the administrator if you believe this is an error.</p>
                
                <div style="margin-top: 2rem;">
                    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                        <a href="dashboard.php" class="btn btn-primary">
                            <i class="fas fa-home"></i> Go to Dashboard
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
