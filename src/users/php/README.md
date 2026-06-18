# XWZ School Student Registration System (SRS)

A comprehensive web-based Student Registration System built with PHP, MySQL, and modern web technologies.

## Features

### Task 1: User Management ✅
- User roles: Admin, Registrar, Student
- Secure user registration with password hashing (bcrypt)
- Login/Logout authentication with session management
- Role-based access control
- Separate dashboards for different user roles

### Task 2: Student Registration ✅
- Student registration form with validation
- CRUD operations (Create, Read, Update, Delete)
- Search and filter functionality
- Registration statistics tracking

### Task 3: Admin Dashboard ✅
- Overview of registered students
- Daily/Weekly/Monthly reports
- User management (add/remove, reset passwords)
- Activity tracking

### Task 4: Security & Validation ✅
- Input validation and sanitization
- Duplicate student ID prevention
- Strong password enforcement
- CSRF protection
- Secure session handling
- Error handling with user feedback

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- PDO extension enabled

### Setup Instructions

1. **Database Configuration**
   - Create MySQL database: `xwz_school_db`
   - Import schema: `config/database_schema.sql`
   - Update database credentials in `config/database.php`

2. **Configure Application**
   - Update `config/config.php` with your base URL
   - Ensure proper file permissions

3. **Access the Application**
   - Navigate to: `http://localhost/your-folder/`
   - Default admin credentials:
     - Email: admin@xwzschool.com
     - Password: Admin@123

## Project Structure

```
php/
├── config/
│   ├── config.php              # Application configuration
│   ├── database.php            # Database connection
│   └── database_schema.sql     # Database schema
├── classes/
│   ├── Auth.php                # Authentication class
│   ├── User.php                # User model
│   ├── Student.php             # Student model
│   └── Validator.php           # Input validation
├── assets/
│   ├── css/
│   │   └── style.css           # Main stylesheet
│   └── js/
│       └── main.js             # JavaScript functionality
├── includes/
│   ├── header.php              # Header template
│   └── footer.php              # Footer template
├── index.php                   # Entry point
├── login.php                   # Login page
├── register.php                # User registration
├── dashboard.php               # Admin/Registrar dashboard
├── students.php                # Student list
├── student_add.php             # Add student
├── student_edit.php             # Edit student
├── student_view.php             # View student details
├── student_delete.php           # Delete student
├── users.php                    # User management
├── reports.php                  # Reports page
└── README.md                    # This file
```

## User Roles & Permissions

### Admin
- Full system access
- Manage users (add, edit, delete, reset passwords)
- Manage students (CRUD operations)
- Generate reports
- View all statistics

### Registrar
- Manage students (CRUD operations)
- View statistics
- Limited access to user management

### Student
- View own profile
- Update personal information
- Limited dashboard access

## Security Features

1. **Password Security**
   - Bcrypt hashing (PASSWORD_BCRYPT)
   - Minimum 8 characters
   - Must contain: uppercase, lowercase, number, special character

2. **Session Management**
   - Secure session handling
   - Session timeout (30 minutes)
   - Session regeneration on login

3. **CSRF Protection**
   - Token-based CSRF protection
   - Token verification on form submissions

4. **Input Validation**
   - Server-side validation
   - SQL injection prevention (PDO prepared statements)
   - XSS prevention (htmlspecialchars)
   - Data sanitization

5. **Error Handling**
   - Proper error messages
   - User-friendly feedback
   - Activity logging

## Technologies Used

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript
- **Icons**: Font Awesome 6.4.0
- **Security**: PDO, bcrypt, CSRF tokens

## Database Schema

### Users Table
- id, firstName, lastName, email, password
- role (admin/registrar/student)
- status, created_at, updated_at

### Students Table
- id, studentId, name, course, year
- contact, email, user_id
- status, created_at, updated_at

### Activity Logs Table
- id, user_id, action, description
- ip_address, created_at

## Usage Guide

### Adding Students
1. Login as Admin or Registrar
2. Navigate to "Students" menu
3. Click "Add New Student"
4. Fill in student details
5. Submit the form

### Generating Reports
1. Login as Admin
2. Navigate to "Reports" menu
3. Select report type (Daily/Weekly/Monthly)
4. Choose date range
5. Click "Generate"

### Managing Users
1. Login as Admin
2. Navigate to "Users" menu
3. Add, edit, or delete users
4. Reset user passwords as needed

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check database credentials in `config/database.php`
   - Ensure MySQL service is running

2. **Session Issues**
   - Check PHP session configuration
   - Ensure session directory has write permissions

3. **Login Problems**
   - Verify email and password
   - Check user status (must be 'active')

## Future Enhancements

- Email notifications
- Document upload functionality
- Advanced reporting with charts
- Export to PDF/Excel
- Two-factor authentication
- Student portal enhancements
- Bulk import/export

## License

This project is developed for XWZ School as part of their digital transformation initiative.

## Support

For technical support or questions:
- Contact: admin@xwzschool.com
- Documentation: See inline code comments

## Credits

Developed by: Full Stack Development Team
Version: 1.0.0
Date: 2024

---

**Note**: Change default admin password immediately after first login!
