<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'classes/User.php';
require_once 'classes/Auth.php';
require_once 'classes/Validator.php';

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);

// If user is already logged in, redirect
if ($auth->isAuthenticated()) {
    $role = $_SESSION['user_role'];
    if ($role === ROLE_ADMIN || $role === ROLE_REGISTRAR) {
        header('Location: dashboard.php');
    } else {
        header('Location: student_dashboard.php');
    }
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validator = new Validator();
    
    // Sanitize inputs
    $email = $validator->sanitizeEmail($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validate inputs
    $validator->validateRequired($email, 'Email');
    $validator->validateEmail($email);
    $validator->validateRequired($password, 'Password');
    
    if (!$validator->hasErrors()) {
        // Attempt login
        if ($auth->login($email, $password)) {
            // Redirect based on role
            $role = $_SESSION['user_role'];
            if ($role === ROLE_ADMIN || $role === ROLE_REGISTRAR) {
                header('Location: dashboard.php');
            } else {
                header('Location: student_dashboard.php');
            }
            exit();
        } else {
            $error = 'Invalid email or password';
        }
    } else {
        $error = implode('<br>', $validator->getErrors());
    }
}

$pageTitle = 'Login';
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
                <i class="fas fa-graduation-cap"></i>
                <h1><?php echo APP_NAME; ?></h1>
                <p>Student Registration System</p>
            </div>
            
            <form method="POST" action="" class="auth-form">
                <h2>Login to Your Account</h2>
                
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
                
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" 
                           value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" 
                           placeholder="Enter your email" required>
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" id="password" name="password" class="form-control" 
                           placeholder="Enter your password" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
                
                <div class="auth-footer">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                </div>
                
                <div class="demo-credentials">
                    <p><strong>Demo Credentials:</strong></p>
                    <p>Admin: admin@xwzschool.com / Admin@123</p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
