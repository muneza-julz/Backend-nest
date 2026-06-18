<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'classes/Auth.php';
require_once 'classes/User.php';
require_once 'classes/Validator.php';

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);

$auth->requireRole(ROLE_ADMIN);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$auth->verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request. Please try again.';
    } else {
        $validator = new Validator();
        
        $firstName = $validator->sanitizeString($_POST['firstName'] ?? '');
        $lastName = $validator->sanitizeString($_POST['lastName'] ?? '');
        $email = $validator->sanitizeEmail($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? ROLE_STUDENT;
        
        $validator->validateRequired($firstName, 'First name');
        $validator->validateRequired($lastName, 'Last name');
        $validator->validateEmail($email);
        $validator->validatePassword($password);
        
        $user = new User($db);
        if ($user->emailExists($email)) {
            $validator->addError('Email already exists');
        }
        
        if (!$validator->hasErrors()) {
            $user->firstName = $firstName;
            $user->lastName = $lastName;
            $user->email = $email;
            $user->password = $password;
            $user->role = $role;
            $user->status = 'active';
            
            if ($user->create()) {
                $success = 'User added successfully!';
                $firstName = $lastName = $email = '';
            } else {
                $error = 'Failed to add user. Please try again.';
            }
        } else {
            $error = implode('<br>', $validator->getErrors());
        }
    }
}

$pageTitle = 'Add User';
include 'includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-user-plus"></i> Add New User</h1>
        <a href="users.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>
    
    <div class="form-section">
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
        
        <form method="POST" action="" class="form">
            <input type="hidden" name="csrf_token" value="<?php echo $auth->generateCsrfToken(); ?>">
            
            <div class="form-row">
                <div class="form-group">
                    <label for="firstName"><i class="fas fa-user"></i> First Name *</label>
                    <input type="text" id="firstName" name="firstName" class="form-control" 
                           value="<?php echo isset($firstName) ? htmlspecialchars($firstName) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="lastName"><i class="fas fa-user"></i> Last Name *</label>
                    <input type="text" id="lastName" name="lastName" class="form-control" 
                           value="<?php echo isset($lastName) ? htmlspecialchars($lastName) : ''; ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email Address *</label>
                <input type="email" id="email" name="email" class="form-control" 
                       value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Password *</label>
                <input type="password" id="password" name="password" class="form-control" required>
                <small class="form-text">
                    Must be at least 8 characters with uppercase, lowercase, number, and special character
                </small>
            </div>
            
            <div class="form-group">
                <label for="role"><i class="fas fa-user-tag"></i> Role *</label>
                <select id="role" name="role" class="form-control" required>
                    <option value="student">Student</option>
                    <option value="registrar">Registrar</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Add User
                </button>
                <a href="users.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
