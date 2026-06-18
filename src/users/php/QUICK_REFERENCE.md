# XWZ School SRS - Quick Reference Guide

## 🚀 Quick Start

### Default Credentials
```
Admin Account:
Email: admin@xwzschool.com
Password: Admin@123
```

### URLs
```
Main: http://localhost/xwz-srs/
Login: http://localhost/xwz-srs/login.php
Dashboard: http://localhost/xwz-srs/dashboard.php
```

## 📋 Common Tasks

### Adding a Student
1. Login as Admin/Registrar
2. Click "Students" → "Add New Student"
3. Fill form (Student ID, Name, Course, Year, Contact)
4. Click "Add Student"

### Searching Students
1. Go to "Students" page
2. Use search box (by ID, name, course)
3. Or use filters (Course, Year)
4. Click "Search"

### Generating Reports
1. Login as Admin
2. Click "Reports"
3. Select report type (Daily/Weekly/Monthly)
4. Choose date
5. Click "Generate"

### Adding Users (Admin Only)
1. Click "Users" → "Add New User"
2. Fill form (Name, Email, Password, Role)
3. Click "Add User"

### Resetting Password (Admin Only)
1. Go to "Users" page
2. Click key icon next to user
3. Enter new password
4. Click "Reset Password"

### Changing Your Password
1. Click your name → "Change Password"
2. Enter current password
3. Enter new password (twice)
4. Click "Update Password"

## 🎯 Password Requirements

Must have:
- At least 8 characters
- 1 uppercase letter (A-Z)
- 1 lowercase letter (a-z)
- 1 number (0-9)
- 1 special character (!@#$%^&*)

Example: `Admin@123`

## 👥 User Roles Comparison

| Feature | Admin | Registrar | Student |
|---------|-------|-----------|---------|
| View Students | ✅ | ✅ | ❌ |
| Add Students | ✅ | ✅ | ❌ |
| Edit Students | ✅ | ✅ | ❌ |
| Delete Students | ✅ | ✅ | ❌ |
| Manage Users | ✅ | ❌ | ❌ |
| Generate Reports | ✅ | ❌ | ❌ |
| View Own Profile | ✅ | ✅ | ✅ |

## 🔧 Configuration Files

### Database Config
File: `config/database.php`
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'xwz_school_db');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### App Config
File: `config/config.php`
```php
define('APP_NAME', 'XWZ School SRS');
define('BASE_URL', 'http://localhost/xwz-srs/');
define('SESSION_TIMEOUT', 1800); // 30 minutes
```

## 🐛 Troubleshooting

### Can't Login
- Check email/password
- Verify user status is "active"
- Clear browser cookies

### Database Error
- Check credentials in `config/database.php`
- Ensure MySQL is running
- Verify database exists

### Session Expired
- Session timeout: 30 minutes
- Login again

### Permission Denied
- Check user role
- Contact admin for access

## 📊 Available Courses

- Computer Science
- Engineering
- Business Administration
- Medicine
- Arts
- Science

## 📅 Year Levels

- 1st Year
- 2nd Year
- 3rd Year
- 4th Year

## 🔐 Security Features

✅ Password hashing (bcrypt)
✅ Session management
✅ CSRF protection
✅ Input validation
✅ SQL injection prevention
✅ XSS protection
✅ Activity logging

## 📱 Browser Support

✅ Chrome (recommended)
✅ Firefox
✅ Edge
✅ Safari
✅ Mobile browsers

## 🆘 Support Contacts

**Technical Support**
- Email: admin@xwzschool.com

**For Help**
- Check README.md
- Check INSTALLATION.md
- Review error messages

## ⚡ Keyboard Shortcuts

| Action | Shortcut |
|--------|----------|
| Search | Ctrl + F |
| New Tab | Ctrl + T |
| Refresh | F5 or Ctrl + R |

## 📦 File Upload Limits

- None currently (future feature)

## 🔄 Data Backup

**Recommended**: Daily backups
**Method**: Export MySQL database
```bash
mysqldump -u root -p xwz_school_db > backup.sql
```

## 📝 Useful MySQL Commands

### Backup Database
```sql
mysqldump -u root -p xwz_school_db > backup_$(date +%Y%m%d).sql
```

### Restore Database
```sql
mysql -u root -p xwz_school_db < backup.sql
```

### View All Users
```sql
SELECT * FROM users;
```

### View All Students
```sql
SELECT * FROM students;
```

### Reset Admin Password (if locked out)
```sql
UPDATE users 
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
WHERE email = 'admin@xwzschool.com';
```
(Password becomes: Admin@123)

## 🎨 Status Colors

- **Green**: Active/Success
- **Red**: Inactive/Error/Danger
- **Blue**: Info/Primary
- **Orange**: Warning
- **Gray**: Secondary/Disabled

## 📞 Emergency Procedures

### If Admin Locked Out
1. Use MySQL command to reset password
2. Or restore from backup
3. Or create new admin via database

### If Database Corrupted
1. Stop web server
2. Restore from latest backup
3. Re-import schema if needed
4. Test thoroughly

## ✅ Daily Tasks Checklist

For Administrators:
- [ ] Check new registrations
- [ ] Review pending users
- [ ] Generate daily report
- [ ] Backup database

For Registrars:
- [ ] Process new students
- [ ] Update student records
- [ ] Respond to queries

## 🎓 Student ID Format

Recommended: `STU-YYYY-NNN`
- STU = Student prefix
- YYYY = Year (2024)
- NNN = Sequential number (001, 002...)

Example: `STU-2024-001`

## 📖 More Information

- Full Documentation: `README.md`
- Installation Guide: `INSTALLATION.md`
- Project Summary: `PROJECT_SUMMARY.md`

---

**Keep this reference handy for quick access to common tasks!**
