<?php
require_once 'db.php';

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get the user role
$userRole = $_SESSION['user_role'];

// Handle GET requests
if ($method === 'GET') {
    // Get the ID parameter
    $id = $_GET['id'] ?? null;

    // Check if the user is an admin to allow editing/deleting
    if ($id && $userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Select all properties
    if (!$id) {
        $stmt = $pdo->prepare('SELECT * FROM العقار');
        $stmt->execute();
        $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($properties);
    } else {
        // Select a property by ID
        $stmt = $pdo->prepare('SELECT * FROM العقار WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $property = $stmt->fetch(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($property);
    }
}

// Handle POST requests
if ($method === 'POST') {
    // Get the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate the data
    if (!isset($data['name']) || !isset($data['description']) || !isset($data['price'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize the data
    $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'], FILTER_SANITIZE_STRING);
    $price = filter_var($data['price'], FILTER_SANITIZE_NUMBER_INT);

    // Insert the property
    $stmt = $pdo->prepare('INSERT INTO العقار (name, description, price) VALUES (:name, :description, :price)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Property created successfully']);
}

// Handle PUT requests
if ($method === 'PUT') {
    // Get the ID parameter
    $id = $_GET['id'] ?? null;

    // Check if the user is an admin to allow editing
    if (!$id || $userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate the data
    if (!isset($data['name']) || !isset($data['description']) || !isset($data['price'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize the data
    $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'], FILTER_SANITIZE_STRING);
    $price = filter_var($data['price'], FILTER_SANITIZE_NUMBER_INT);

    // Update the property
    $stmt = $pdo->prepare('UPDATE العقار SET name = :name, description = :description, price = :price WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Property updated successfully']);
}

// Handle DELETE requests
if ($method === 'DELETE') {
    // Get the ID parameter
    $id = $_GET['id'] ?? null;

    // Check if the user is an admin to allow deleting
    if (!$id || $userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Delete the property
    $stmt = $pdo->prepare('DELETE FROM العقار WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Property deleted successfully']);
}