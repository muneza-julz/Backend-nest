<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'classes/Auth.php';
require_once 'classes/User.php';
require_once 'classes/Validator.php';

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);

$auth->requireAuth();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$auth->verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request. Please try again.';
    } else {
        $validator = new Validator();
        
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        $validator->validateRequired($currentPassword, 'Current password');
        $validator->validatePassword($newPassword, 'New password');
        
        if ($newPassword !== $confirmPassword) {
            $validator->addError('New passwords do not match');
        }
        
        if (!$validator->hasErrors()) {
            $user = new User($db);
            
            // Verify current password
            if ($user->authenticate($_SESSION['user_email'], $currentPassword)) {
                if ($user->updatePassword($_SESSION['user_id'], $newPassword)) {
                    $success = 'Password updated successfully!';
                } else {
                    $error = 'Failed to update password. Please try again.';
                }
            } else {
                $error = 'Current password is incorrect';
            }
        } else {
            $error = implode('<br>', $validator->getErrors());
        }
    }
}

$pageTitle = 'Change Password';
include 'includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-key"></i> Change Password</h1>
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
            
            <div class="form-group">
                <label for="current_password"><i class="fas fa-lock"></i> Current Password *</label>
                <input type="password" id="current_password" name="current_password" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="new_password"><i class="fas fa-key"></i> New Password *</label>
                <input type="password" id="new_password" name="new_password" class="form-control" required>
                <small class="form-text">
                    Must be at least 8 characters with uppercase, lowercase, number, and special character
                </small>
            </div>
            
            <div class="form-group">
                <label for="confirm_password"><i class="fas fa-key"></i> Confirm New Password *</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Password
                </button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
