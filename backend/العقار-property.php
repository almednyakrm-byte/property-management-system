<?php
require_once 'db.php';

// Get user data from session
$user = $_SESSION['user'];

// Check if user is logged in
if (!$user) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET request
if ($method === 'GET') {
    // Check if user is admin
    if ($user['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get all properties
    $stmt = $pdo->prepare('SELECT * FROM properties');
    $stmt->execute();
    $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return properties
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($properties);
    exit;
}

// Handle POST request
if ($method === 'POST') {
    // Get request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate data
    if (!isset($data['title']) || !isset($data['description']) || !isset($data['price'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize data
    $title = filter_var($data['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'], FILTER_SANITIZE_STRING);
    $price = filter_var($data['price'], FILTER_SANITIZE_NUMBER_INT);

    // Insert property
    $stmt = $pdo->prepare('INSERT INTO properties (title, description, price, user_id) VALUES (:title, :description, :price, :user_id)');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':user_id', $user['id']);
    $stmt->execute();

    // Return property ID
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $pdo->lastInsertId()]);
    exit;
}

// Handle PUT request
if ($method === 'PUT') {
    // Check if user is admin
    if ($user['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate data
    if (!isset($data['id']) || !isset($data['title']) || !isset($data['description']) || !isset($data['price'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize data
    $id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);
    $title = filter_var($data['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'], FILTER_SANITIZE_STRING);
    $price = filter_var($data['price'], FILTER_SANITIZE_NUMBER_INT);

    // Update property
    $stmt = $pdo->prepare('UPDATE properties SET title = :title, description = :description, price = :price WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Property updated successfully']);
    exit;
}

// Handle DELETE request
if ($method === 'DELETE') {
    // Check if user is admin
    if ($user['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate data
    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize data
    $id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete property
    $stmt = $pdo->prepare('DELETE FROM properties WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Property deleted successfully']);
    exit;
}