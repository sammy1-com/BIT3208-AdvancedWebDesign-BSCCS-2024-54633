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

// Check if the database already exists and has tables
$result = $conn->query("SELECT COUNT(*) as count FROM information_schema.TABLES WHERE TABLE_SCHEMA = '$dbname'");
$row = $result->fetch_assoc();

if ($row['count'] > 0) {
    // Database already initialized, skip
    echo "Database already initialized.\n";
    $conn->close();
    exit(0);
}

// Read the SQL file and replace the hardcoded database name with the environment variable
$sql = file_get_contents(__DIR__ . '/sql/pitstop.sql');
$sql = str_replace('CREATE DATABASE IF NOT EXISTS pitstop', "CREATE DATABASE IF NOT EXISTS $dbname", $sql);
$sql = str_replace('USE pitstop', "USE $dbname", $sql);

if ($conn->multi_query($sql)) {
    echo "Database initialized successfully!\n";
    while ($conn->more_results()) {
        $conn->next_result();
    }
} else {
    echo "Error initializing database: " . $conn->error . "\n";
}

$conn->close();
?>

