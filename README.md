# CRUD_vuejs_PHP
PHP-CRUD-VUE js-MYSQL
Single file PHP,VUE js script that adds to a MySQL database.
Requirements
•	PHP 7.0 or higher with PDO drivers enabled for the following database system:
o	MySQL 5.6  or higher for spatial features in MySQL
Installation
This is a single file application! Upload "index.php and action.php" in xampp/htdocs/create_folder and enjoy!
Test the script by opening the following URL:
http://localhost/folder_name/index.php
Don't forget to modify the configuration at the bottom of the file.
Configuration
Edit the following lines in the bottom of the file "action.php":
$config = new Config([
    'username' => 'root',
    'password' => '',
    'database' => 'test',
]);
These are all the configuration options and their default value between brackets:
•	"address": Hostname (or filename) of the database server (localhost)
•	"username": Username of the user connecting to the database (no default)
•	"password": Password of the user connecting to the database (no default)
•	"database": Database the connecting is made to (no default)
•	"tables": Comma separated list of tables to publish (defaults to 'all')

Features
The following features are supported:
•	Composer install or single PHP file, easy to deploy.
•	Very little code, easy to adapt and maintain
•	Supports POST variables as input 
•	Supports a JSON object as input
•	Support for reading joined results from multiple tables
•	Database connection parameters may depend on authentication

Development
You can access the non-compiled code at the URL:
http://localhost/folder_name/index.php


The CRUD + List operations below act on this table.
Create
If you want to create a record , then click on ‘add’ button in the right top corner.:
