PHP CRUD APPLICATION - XAMPP SETUP

REQUIREMENTS
- XAMPP with Apache, PHP and MySQL/MariaDB
- A web browser

INSTALLATION
1. Extract the phpcrud folder into:
   C:\xampp\htdocs\
2. Start Apache and MySQL in XAMPP Control Panel.
3. Open http://localhost/phpmyadmin
4. Select the SQL tab and import/run database.sql.
   The script creates the phpcrud database and tblusers table.
5. Open:
   http://localhost/phpcrud/

FILES
- database.sql: creates the database and table.
- dbconnection.php: connects PHP to MySQL.
- functions.php: common validation, escaping and CSRF functions.
- insert.php: creates a user record.
- index.php: lists user records.
- read.php: displays one record.
- edit.php: updates a record.
- delete.php: deletes a record.
- style.css: page styling.

TEST THE FOUR CRUD OPERATIONS
1. CREATE: click Add New User and save a test record.
2. READ: click View to display that record.
3. UPDATE: click Edit, change a value and save.
4. DELETE: click Delete and confirm.

DEFAULT XAMPP DATABASE SETTINGS
Host: localhost
Username: root
Password: (blank)
Database: phpcrud

If you have set a MySQL root password, update dbconnection.php.

NOTE
MobileNumber is VARCHAR rather than BIGINT so that leading zeroes in
phone numbers are preserved. Prepared statements and output escaping
are used to reduce SQL injection and cross-site scripting risks.
