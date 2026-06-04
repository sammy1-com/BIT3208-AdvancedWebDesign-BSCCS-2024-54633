<?php
// WEEK 3 - Database Connection (now properly included and tested)
$host     = 'localhost';
$dbname   = 'pitstop';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>
