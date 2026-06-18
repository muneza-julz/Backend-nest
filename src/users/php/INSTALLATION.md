# Installation Guide - XWZ School SRS

## Quick Start Guide

### Step 1: Prerequisites Check
Before installation, ensure you have:
- ✅ PHP 7.4 or higher installed
- ✅ MySQL 5.7 or higher installed
- ✅ Apache/Nginx web server running
- ✅ PDO MySQL extension enabled

### Step 2: Download and Extract
1. Download the SRS package
2. Extract to your web server directory
   - XAMPP: `C:\xampp\htdocs\xwz-srs\`
   - WAMP: `C:\wamp64\www\xwz-srs\`
   - Linux: `/var/www/html/xwz-srs/`

### Step 3: Database Setup

#### Option A: Using phpMyAdmin
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Click "New" to create a database
3. Name it: `xwz_school_db`
4. Select Collation: `utf8mb4_unicode_ci`
5. Click "Import" tab
6. Choose file: `config/database_schema.sql`
7. Click "Go" to import

#### Option B: Using MySQL Command Line
```bash
mysql -u root -p
CREATE DATABASE xwz_school_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE xwz_school_db;
SOURCE /path/to/config/database_schema.sql;
EXIT;
```

### Step 4: Configure Database Connection
1. Open `config/database.php`
2. Update these values:
```php
define('DB_HOST', 'localhost');      // Usually localhost
define('DB_NAME', 'xwz_school_db');  // Your database name
define('DB_USER', 'root');           // Your MySQL username
define('DB_PASS', '');               // Your MySQL password
```

### Step 5: Configure Application
1. Open `config/config.php`
2. Update the BASE_URL:
```php
define('BASE_URL', 'http://localhost/xwz-srs/');
```

### Step 6: Set File Permissions (Linux/Mac only)
```bash
chmod 755 -R /var/www/html/xwz-srs/
chmod 777 /var/www/html/xwz-srs/uploads/ (if exists)
```

### Step 7: Access the Application
1. Open your web browser
2. Navigate to: `http://localhost/xwz-srs/`
3. You will be redirected to the login page

### Step 8: First Login
Use the default admin credentials:
- **Email**: admin@xwzschool.com
- **Password**: Admin@123

⚠️ **IMPORTANT**: Change the admin password immediately after first login!

## Verification Checklist

After installation, verify:
- [ ] Login page loads correctly
- [ ] Can login with admin credentials
- [ ] Dashboard displays properly
- [ ] Can add a test student
- [ ] Can view student list
- [ ] Can generate reports
- [ ] No PHP errors in browser console

## Common Issues and Solutions

### Issue 1: "Connection Error"
**Solution**: Check database credentials in `config/database.php`

### Issue 2: "Table doesn't exist"
**Solution**: Import the SQL schema file again

### Issue 3: "Session error"
**Solution**: Check PHP session configuration and directory permissions

### Issue 4: "404 Not Found"
**Solution**: Verify BASE_URL in `config/config.php` matches your setup

### Issue 5: "Access Denied"
**Solution**: Check MySQL user permissions

## Server Requirements

### Minimum Requirements
- PHP 7.4+
- MySQL 5.7+
- 256MB RAM
- 50MB disk space

### Recommended Requirements
- PHP 8.0+
- MySQL 8.0+
- 512MB RAM
- 100MB disk space

### Required PHP Extensions
- PDO
- pdo_mysql
- mbstring
- session
- json

Check your PHP configuration:
```bash
php -m
```

## Security Recommendations

1. **Change Default Password**
   - Login as admin
   - Go to Change Password
   - Update immediately

2. **Update Database Credentials**
   - Use strong MySQL password
   - Don't use 'root' in production

3. **File Permissions**
   - Don't give 777 permissions in production
   - Restrict access to config files

4. **Enable HTTPS**
   - Use SSL certificate
   - Force HTTPS connections

5. **Regular Backups**
   - Backup database regularly
   - Keep copies of uploaded files

## Production Deployment

### Additional Steps for Production:
1. Disable error display in PHP
2. Enable error logging
3. Use environment variables for sensitive data
4. Configure .htaccess for security
5. Set up automated backups
6. Configure email settings
7. Enable HTTPS/SSL

## Support

If you encounter issues:
1. Check error logs: `php_error.log`
2. Review Apache/Nginx logs
3. Verify all requirements are met
4. Contact: admin@xwzschool.com

## Next Steps

After successful installation:
1. Change admin password
2. Create user accounts for registrars
3. Add student records
4. Customize system settings
5. Train staff on usage

---

**Congratulations!** Your XWZ School SRS is now installed and ready to use.
