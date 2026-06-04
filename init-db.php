<?php
// Database initialization script
$host     = getenv('MYSQLHOST') ?: 'localhost';
$port     = getenv('MYSQLPORT') ?: 3306;
$username = getenv('MYSQLUSER') ?: 'root';
$password = getenv('MYSQLPASSWORD') ?: '';
$dbname   = getenv('MYSQLDATABASE') ?: 'pitstop_db';

echo "Initializing database...\n";
echo "Host: $host, Port: $port, User: $username, DB: $dbname\n";

// Wait for MySQL to be ready
$attempts = 0;
$max_attempts = 30;
while ($attempts < $max_attempts) {
    $conn = @new mysqli($host, $username, $password, '', $port);
    if (!$conn->connect_error) {
        echo "Connected to MySQL\n";
        break;
    }
    $attempts++;
    echo "Waiting for MySQL... (attempt $attempts/$max_attempts)\n";
    sleep(1);
}

if ($conn->connect_error) {
    die("Failed to connect to MySQL after $max_attempts attempts: " . $conn->connect_error);
}

// Always drop and recreate to ensure schema is correct
echo "Dropping database if exists...\n";
if (!$conn->query("DROP DATABASE IF EXISTS `$dbname`")) {
    echo "Warning: Could not drop database: " . $conn->error . "\n";
}

echo "Creating database...\n";
if (!$conn->query("CREATE DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
    die("Failed to create database: " . $conn->error);
}

echo "Selecting database...\n";
if (!$conn->select_db($dbname)) {
    die("Failed to select database: " . $conn->error);
}

// Read the SQL file
echo "Reading SQL file...\n";
$sql = file_get_contents(__DIR__ . '/sql/pitstop.sql');
if ($sql === false) {
    die("Failed to read SQL file");
}

// Remove the CREATE DATABASE and USE statements since we already did that
$sql = preg_replace('/^\\s*CREATE\\s+DATABASE\\b[^;]*;\\s*/im', '', $sql);
$sql = preg_replace('/^\\s*USE\\s+\\S+\\s*;\\s*/im', '', $sql);

// Split by semicolon and execute each statement
$statements = array_filter(array_map('trim', explode(';', $sql)));
echo "Executing " . count($statements) . " SQL statements...\n";

$error_count = 0;
foreach ($statements as $i => $statement) {
    if (!empty($statement)) {
        if (!$conn->query($statement)) {
            echo "Error executing statement " . ($i + 1) . ": " . $conn->error . "\n";
            $error_count++;
        }
    }
}

if ($error_count > 0) {
    echo "Completed with $error_count errors\n";
} else {
    echo "Database initialized successfully!\n";
}

$conn->close();
?>

