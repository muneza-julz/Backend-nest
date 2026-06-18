<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'classes/User.php';
require_once 'classes/Validator.php';

$database = new Database();
$db = $database->getConnection();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validator = new Validator();
    
    // Sanitize inputs
    $firstName = $validator->sanitizeString($_POST['firstName'] ?? '');
    $lastName = $validator->sanitizeString($_POST['lastName'] ?? '');
    $email = $validator->sanitizeEmail($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Validate inputs
    $validator->validateRequired($firstName, 'First name');
    $validator->validateRequired($lastName, 'Last name');
    $validator->validateEmail($email);
    $validator->validatePassword($password);
    
    if ($password !== $confirmPassword) {
        $validator->addError('Passwords do not match');
    }
    
    // Check if email already exists
    $user = new User($db);
    if ($user->emailExists($email)) {
        $validator->addError('Email already registered');
    }
    
    if (!$validator->hasErrors()) {
        $user->firstName = $firstName;
        $user->lastName = $lastName;
        $user->email = $email;
        $user->password = $password;
        $user->role = ROLE_STUDENT; // Default role
        $user->status = 'active';
        
        if ($user->create()) {
            $success = 'Registration successful! You can now <a href="login.php">login</a>.';
            // Clear form
            $firstName = $lastName = $email = '';
        } else {
            $error = 'Registration failed. Please try again.';
        }
    } else {
        $error = implode('<br>', $validator->getErrors());
    }
}

$pageTitle = 'Register';
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
                <h2>Create Your Account</h2>
                
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
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName"><i class="fas fa-user"></i> First Name</label>
                        <input type="text" id="firstName" name="firstName" class="form-control" 
                               value="<?php echo isset($firstName) ? htmlspecialchars($firstName) : ''; ?>" 
                               placeholder="Enter first name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="lastName"><i class="fas fa-user"></i> Last Name</label>
                        <input type="text" id="lastName" name="lastName" class="form-control" 
                               value="<?php echo isset($lastName) ? htmlspecialchars($lastName) : ''; ?>" 
                               placeholder="Enter last name" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" 
                           value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" 
                           placeholder="Enter your email" required>
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" id="password" name="password" class="form-control" 
                           placeholder="Enter password" required>
                    <small class="form-text">
                        Password must be at least 8 characters and contain uppercase, lowercase, number, and special character
                    </small>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password"><i class="fas fa-lock"></i> Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" 
                           placeholder="Re-enter password" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-user-plus"></i> Register
                </button>
                
                <div class="auth-footer">
                    <p>Already have an account? <a href="login.php">Login here</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
