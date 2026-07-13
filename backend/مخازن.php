<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    '/makhazen' => array('GET' => 'index', 'POST' => 'store'),
    '/makhazen/:id' => array('GET' => 'show', 'PUT' => 'update', 'DELETE' => 'destroy')
);

// Route to the correct method
$match = false;
foreach ($routes as $route => $methods) {
    if (strpos($route, '/') !== false) {
        $parts = explode('/', $route);
        if (count($parts) == 2 && $parts[0] == 'makhazen' && $parts[1] == $input['id']) {
            $match = true;
            break;
        }
    } elseif (strpos($route, ':id') !== false) {
        if (isset($input['id'])) {
            $match = true;
            break;
        }
    } elseif ($route == 'makhazen') {
        $match = true;
        break;
    }
}

if (!$match) {
    http_response_code(404);
    echo json_encode(array('error' => 'Not Found'));
    exit;
}

// Define methods
function index() {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM makhazen');
    $stmt->execute();
    $makhazen = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($makhazen);
}

function show() {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM makhazen WHERE id = :id');
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $makhazen = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$makhazen) {
        http_response_code(404);
        echo json_encode(array('error' => 'Not Found'));
        exit;
    }
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($makhazen);
}

function store() {
    global $pdo;
    // Validate input
    if (!isset($input['name']) || !isset($input['address'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    // Sanitize input
    $name = htmlspecialchars($input['name']);
    $address = htmlspecialchars($input['address']);
    // Insert data
    $stmt = $pdo->prepare('INSERT INTO makhazen (name, address) VALUES (:name, :address)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':address', $address);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Makhazen created successfully'));
}

function update() {
    global $pdo;
    // Validate input
    if (!isset($input['name']) || !isset($input['address'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    // Sanitize input
    $name = htmlspecialchars($input['name']);
    $address = htmlspecialchars($input['address']);
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    // Update data
    $stmt = $pdo->prepare('UPDATE makhazen SET name = :name, address = :address WHERE id = :id');
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':address', $address);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Makhazen updated successfully'));
}

function destroy() {
    global $pdo;
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    // Delete data
    $stmt = $pdo->prepare('DELETE FROM makhazen WHERE id = :id');
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Makhazen deleted successfully'));
}

// Call the correct method
$method = $routes[$match][array_key_first($routes[$match])];
$method();

?>