<?php
require_once 'config/config.php';

// If user is already logged in, redirect to appropriate dashboard
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    if ($_SESSION['user_role'] === ROLE_ADMIN || $_SESSION['user_role'] === ROLE_REGISTRAR) {
        header('Location: dashboard.php');
    } else {
        header('Location: student_dashboard.php');
    }
    exit();
}

// Redirect to login page
header('Location: login.php');
exit();
?>
