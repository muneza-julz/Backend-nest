<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'classes/Auth.php';
require_once 'classes/Student.php';

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);

// Require authentication and admin/registrar role
$auth->requireAnyRole([ROLE_ADMIN, ROLE_REGISTRAR]);

$student = new Student($db);

// Handle search and filters
$students = [];
$searchKeyword = $_GET['search'] ?? '';
$filterCourse = $_GET['course'] ?? '';
$filterYear = $_GET['year'] ?? '';

if ($searchKeyword) {
    $students = $student->search($searchKeyword);
} elseif ($filterCourse || $filterYear) {
    $students = $student->filter($filterCourse, $filterYear);
} else {
    $students = $student->getAll();
}

$pageTitle = 'Students';
include 'includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-users"></i> Student Management</h1>
        <a href="student_add.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Student
        </a>
    </div>
    
    <div class="filters-section">
        <form method="GET" action="" class="filters-form">
            <div class="form-row">
                <div class="form-group">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search by ID, name, course..." 
                           value="<?php echo htmlspecialchars($searchKeyword); ?>">
                </div>
                
                <div class="form-group">
                    <select name="course" class="form-control">
                        <option value="">All Courses</option>
                        <option value="Computer Science" <?php echo $filterCourse === 'Computer Science' ? 'selected' : ''; ?>>Computer Science</option>
                        <option value="Engineering" <?php echo $filterCourse === 'Engineering' ? 'selected' : ''; ?>>Engineering</option>
                        <option value="Business Administration" <?php echo $filterCourse === 'Business Administration' ? 'selected' : ''; ?>>Business Administration</option>
                        <option value="Medicine" <?php echo $filterCourse === 'Medicine' ? 'selected' : ''; ?>>Medicine</option>
                        <option value="Arts" <?php echo $filterCourse === 'Arts' ? 'selected' : ''; ?>>Arts</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <select name="year" class="form-control">
                        <option value="">All Years</option>
                        <option value="1st Year" <?php echo $filterYear === '1st Year' ? 'selected' : ''; ?>>1st Year</option>
                        <option value="2nd Year" <?php echo $filterYear === '2nd Year' ? 'selected' : ''; ?>>2nd Year</option>
                        <option value="3rd Year" <?php echo $filterYear === '3rd Year' ? 'selected' : ''; ?>>3rd Year</option>
                        <option value="4th Year" <?php echo $filterYear === '4th Year' ? 'selected' : ''; ?>>4th Year</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>
                
                <a href="students.php" class="btn btn-secondary">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
        </form>
    </div>
    
    <div class="section">
        <div class="section-header">
            <h2>Student Records (<?php echo count($students); ?> found)</h2>
        </div>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Course</th>
                        <th>Year</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($students)): ?>
                        <?php foreach ($students as $s): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($s['studentId']); ?></td>
                            <td><?php echo htmlspecialchars($s['name']); ?></td>
                            <td><?php echo htmlspecialchars($s['course']); ?></td>
                            <td><?php echo htmlspecialchars($s['year']); ?></td>
                            <td><?php echo htmlspecialchars($s['contact']); ?></td>
                            <td><?php echo htmlspecialchars($s['email'] ?? 'N/A'); ?></td>
                            <td>
                                <span class="badge badge-<?php echo $s['status'] === 'active' ? 'success' : 'danger'; ?>">
                                    <?php echo ucfirst($s['status']); ?>
                                </span>
                            </td>
                            <td class="actions">
                                <a href="student_view.php?id=<?php echo $s['id']; ?>" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="student_edit.php?id=<?php echo $s['id']; ?>" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="student_delete.php?id=<?php echo $s['id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this student?')" 
                                   title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No students found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
