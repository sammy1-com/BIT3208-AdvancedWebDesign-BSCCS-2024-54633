<?php
// Database initialization script
$host     = getenv('MYSQLHOST') ?: 'localhost';
$port     = getenv('MYSQLPORT') ?: 3306;
$username = getenv('MYSQLUSER') ?: 'root';
$password = getenv('MYSQLPASSWORD') ?: '';

// Connect without specifying a database (to create it)
$conn = new mysqli($host, $username, $password, '', $port);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Read and execute the SQL file
$sql = file_get_contents(__DIR__ . '/Week5/sql/pitstop.sql');

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

