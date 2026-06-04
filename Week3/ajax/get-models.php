<?php
// WEEK 3 - AJAX Endpoint: Returns models for a selected vehicle make
// Called by JavaScript Fetch API on the homepage search bar
header('Content-Type: application/json');

$make_id = isset($_GET['make_id']) ? (int)$_GET['make_id'] : 0;

// WEEK 3: Hardcoded models array (DB query version comes in Week 4)
$models_data = [
    1 => ['Corolla','Camry','Hilux','Land Cruiser','Prado','Vitz','RAV4','Premio'],
    2 => ['Note','Tiida','X-Trail','Patrol','Hardbody','Navara','March'],
    3 => ['Impreza','Forester','Outback','Legacy','XV','WRX','BRZ'],
    4 => ['Demio','Axela','CX-5','BT-50','Atenza','CX-3'],
    5 => ['Fit','Civic','CR-V','Accord','HR-V','Freed'],
    6 => ['Outlander','Pajero','ASX','Eclipse Cross','Galant'],
];

$models = [];
if (isset($models_data[$make_id])) {
    foreach ($models_data[$make_id] as $i => $name) {
        $models[] = ['id' => ($make_id * 100) + $i + 1, 'name' => $name];
    }
}

echo json_encode($models);
?>
