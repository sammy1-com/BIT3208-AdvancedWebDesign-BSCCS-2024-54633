<?php
require_once '../includes/db.php';
header('Content-Type: application/json');
$make_id = (int)($_GET['make_id'] ?? 0);
if (!$make_id) { echo '[]'; exit; }
$result = $conn->query("SELECT id, name FROM models WHERE make_id = $make_id ORDER BY name ASC");
echo json_encode($result->fetch_all(MYSQLI_ASSOC));
