<?php
// Database connection - Week 1
$host     = 'localhost';
$dbname   = 'pitstop';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die('<p style="color:red;">Connection FAILED: ' . $conn->connect_error . '</p>');
}

echo '<p style="color:green;">Database connection SUCCESSFUL.</p>';
?>
