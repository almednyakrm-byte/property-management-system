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

    // Get all payments
    $stmt = $pdo->prepare('SELECT * FROM payments');
    $stmt->execute();
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return payments
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($payments);
    exit;
}

// Handle POST request
if ($method === 'POST') {
    // Get payment data from request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate payment data
    if (!isset($data['amount']) || !isset($data['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize payment data
    $data['amount'] = (float) $data['amount'];
    $data['description'] = trim($data['description']);

    // Insert payment
    $stmt = $pdo->prepare('INSERT INTO payments (amount, description) VALUES (:amount, :description)');
    $stmt->execute($data);

    // Return payment ID
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

    // Get payment ID from URL
    $id = (int) $_GET['id'];

    // Get payment data from request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate payment data
    if (!isset($data['amount']) || !isset($data['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize payment data
    $data['amount'] = (float) $data['amount'];
    $data['description'] = trim($data['description']);

    // Update payment
    $stmt = $pdo->prepare('UPDATE payments SET amount = :amount, description = :description WHERE id = :id');
    $stmt->execute($data);

    // Return payment ID
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['id' => $id]);
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

    // Get payment ID from URL
    $id = (int) $_GET['id'];

    // Delete payment
    $stmt = $pdo->prepare('DELETE FROM payments WHERE id = :id');
    $stmt->execute(['id' => $id]);

    // Return payment ID
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['id' => $id]);
    exit;
}