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

if ($studentId) {
    $student = new Student($db);
    
    if ($student->delete($studentId)) {
        header('Location: students.php?message=deleted');
    } else {
        header('Location: students.php?error=delete_failed');
    }
} else {
    header('Location: students.php');
}

exit();
?>
