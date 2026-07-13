<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data from JSON or POST
$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

// Validate input data
if (!isset($input['id']) && !isset($input['title']) && !isset($input['description']) && !isset($input['price'])) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
    exit;
}

// Sanitize input data
$input['title'] = trim($input['title']);
$input['description'] = trim($input['description']);
$input['price'] = (float) $input['price'];

// Get user role
$userRole = $_SESSION['user_role'];

// GET all properties
if (isset($input['action']) && $input['action'] == 'get_all') {
    $stmt = $pdo->prepare('SELECT * FROM properties');
    $stmt->execute();
    $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($properties);
    exit;
}

// GET single property
if (isset($input['action']) && $input['action'] == 'get_one') {
    $stmt = $pdo->prepare('SELECT * FROM properties WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();
    $property = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$property) {
        http_response_code(404);
        echo json_encode(array('error' => 'Property not found'));
        exit;
    }
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($property);
    exit;
}

// CREATE new property
if (isset($input['action']) && $input['action'] == 'create') {
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    $stmt = $pdo->prepare('INSERT INTO properties (title, description, price) VALUES (:title, :description, :price)');
    $stmt->bindParam(':title', $input['title']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->bindParam(':price', $input['price']);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Property created successfully'));
    exit;
}

// UPDATE existing property
if (isset($input['action']) && $input['action'] == 'update') {
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    $stmt = $pdo->prepare('UPDATE properties SET title = :title, description = :description, price = :price WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->bindParam(':title', $input['title']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->bindParam(':price', $input['price']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Property updated successfully'));
    exit;
}

// DELETE property
if (isset($input['action']) && $input['action'] == 'delete') {
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    $stmt = $pdo->prepare('DELETE FROM properties WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Property deleted successfully'));
    exit;
}

http_response_code(400);
echo json_encode(array('error' => 'Invalid request'));
exit;