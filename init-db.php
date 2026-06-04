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
$conn->query("DROP DATABASE IF EXISTS $dbname");

// Read the SQL file and replace the hardcoded database name with the environment variable
$sql = file_get_contents(__DIR__ . '/sql/pitstop.sql');
$sql = str_replace('CREATE DATABASE IF NOT EXISTS pitstop', "CREATE DATABASE IF NOT EXISTS $dbname", $sql);
$sql = str_replace('USE pitstop', "USE $dbname", $sql);

// Split by semicolon and execute each statement
$statements = array_filter(array_map('trim', explode(';', $sql)));
foreach ($statements as $statement) {
    if (!empty($statement)) {
        if (!$conn->query($statement)) {
            echo "Error: " . $conn->error . "\n";
        }
    }
}

echo "Database initialized successfully!\n";
$conn->close();
?>

