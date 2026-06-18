<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?><?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
    <nav class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <i class="fas fa-graduation-cap"></i>
                <span><?php echo APP_NAME; ?></span>
            </div>
            <ul class="navbar-menu">
                <?php if ($_SESSION['user_role'] === ROLE_ADMIN || $_SESSION['user_role'] === ROLE_REGISTRAR): ?>
                <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="students.php"><i class="fas fa-users"></i> Students</a></li>
                <?php if ($_SESSION['user_role'] === ROLE_ADMIN): ?>
                <li><a href="users.php"><i class="fas fa-user-shield"></i> Users</a></li>
                <li><a href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
                <?php endif; ?>
                <?php endif; ?>
                
                <?php if ($_SESSION['user_role'] === ROLE_STUDENT): ?>
                <li><a href="student_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="student_profile.php"><i class="fas fa-user"></i> Profile</a></li>
                <?php endif; ?>
                
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">
                        <i class="fas fa-user-circle"></i> 
                        <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        <i class="fas fa-chevron-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="profile.php"><i class="fas fa-user-edit"></i> My Profile</a></li>
                        <li><a href="change_password.php"><i class="fas fa-key"></i> Change Password</a></li>
                        <li><hr></li>
                        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <?php endif; ?>
    
    <main class="main-content">
