<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'classes/Auth.php';
require_once 'classes/User.php';

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);

// Only admin can manage users
$auth->requireRole(ROLE_ADMIN);

$user = new User($db);
$users = $user->getAll();

$pageTitle = 'User Management';
include 'includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-user-shield"></i> User Management</h1>
        <a href="user_add.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New User
        </a>
    </div>
    
    <div class="section">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?php echo $u['id']; ?></td>
                            <td><?php echo htmlspecialchars($u['firstName'] . ' ' . $u['lastName']); ?></td>
                            <td><?php echo htmlspecialchars($u['email']); ?></td>
                            <td>
                                <span class="badge badge-<?php 
                                    echo $u['role'] === 'admin' ? 'danger' : 
                                         ($u['role'] === 'registrar' ? 'warning' : 'info'); 
                                ?>">
                                    <?php echo ucfirst($u['role']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo $u['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                    <?php echo ucfirst($u['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M j, Y', strtotime($u['created_at'])); ?></td>
                            <td class="actions">
                                <a href="user_edit.php?id=<?php echo $u['id']; ?>" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="user_reset_password.php?id=<?php echo $u['id']; ?>" class="btn btn-sm btn-info" title="Reset Password">
                                    <i class="fas fa-key"></i>
                                </a>
                                <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                <a href="user_delete.php?id=<?php echo $u['id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this user?')" 
                                   title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No users found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
