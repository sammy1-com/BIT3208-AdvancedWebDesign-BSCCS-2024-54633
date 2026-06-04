<?php
// Database initialization script
$host     = getenv('MYSQLHOST') ?: 'localhost';
$port     = getenv('MYSQLPORT') ?: 3306;
$username = getenv('MYSQLUSER') ?: 'root';
$password = getenv('MYSQLPASSWORD') ?: '';
$dbname   = getenv('MYSQLDATABASE') ?: 'pitstop_db';

// Connect without specifying a database (to create it)
$conn = new mysqli($host, $username, $password, '', $port);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Always drop and recreate to ensure schema is correct
$conn->query("DROP DATABASE IF EXISTS `$dbname`");
$conn->query("CREATE DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->select_db($dbname);

// Read the SQL file and strip CREATE DATABASE / USE statements (handled above)
$sql = file_get_contents(__DIR__ . '/sql/pitstop.sql');
$sql = preg_replace('/^\s*CREATE\s+DATABASE\b[^;]*;\s*/im', '', $sql);
$sql = preg_replace('/^\s*USE\s+\S+\s*;\s*/im', '', $sql);

// Split by semicolon and execute each statement
$statements = array_filter(array_map('trim', explode(';', $sql)));
foreach ($statements as $statement) {
    if (!empty($statement)) {
        if (!$conn->query($statement)) {
            echo "Error executing statement: " . $conn->error . "\nStatement: " . $statement . "\n";
        }
    }
}

echo "Database initialized successfully!\n";
$conn->close();
?>

