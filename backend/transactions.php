<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Check if user is logged in
if (!$userID) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET requests
if ($method === 'GET') {
    // Get transaction ID from URL query string
    $transactionID = $_GET['id'] ?? null;

    // Check if transaction ID is provided
    if (!$transactionID) {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing transaction ID'));
        exit;
    }

    // Prepare SELECT statement
    $stmt = $pdo->prepare('SELECT * FROM transactions WHERE id = :id');
    $stmt->bindParam(':id', $transactionID);
    $stmt->execute();

    // Fetch transaction data
    $transaction = $stmt->fetch();

    // Check if transaction exists
    if (!$transaction) {
        http_response_code(404);
        echo json_encode(array('error' => 'Transaction not found'));
        exit;
    }

    // Return transaction data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($transaction);
}

// Handle POST requests
elseif ($method === 'POST') {
    // Get transaction data from request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate transaction data
    if (!isset($data['amount']) || !isset($data['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing required fields'));
        exit;
    }

    // Sanitize transaction data
    $amount = filter_var($data['amount'], FILTER_SANITIZE_NUMBER_INT);
    $description = filter_var($data['description'], FILTER_SANITIZE_STRING);

    // Prepare INSERT statement
    $stmt = $pdo->prepare('INSERT INTO transactions (amount, description, user_id) VALUES (:amount, :description, :user_id)');
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':user_id', $userID);

    // Execute INSERT statement
    if ($stmt->execute()) {
        // Return transaction ID
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('id' => $pdo->lastInsertId()));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to create transaction'));
    }
}

// Handle PUT requests
elseif ($method === 'PUT') {
    // Get transaction ID from URL query string
    $transactionID = $_GET['id'] ?? null;

    // Check if transaction ID is provided
    if (!$transactionID) {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing transaction ID'));
        exit;
    }

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get transaction data from request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate transaction data
    if (!isset($data['amount']) || !isset($data['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing required fields'));
        exit;
    }

    // Sanitize transaction data
    $amount = filter_var($data['amount'], FILTER_SANITIZE_NUMBER_INT);
    $description = filter_var($data['description'], FILTER_SANITIZE_STRING);

    // Prepare UPDATE statement
    $stmt = $pdo->prepare('UPDATE transactions SET amount = :amount, description = :description WHERE id = :id AND user_id = :user_id');
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':id', $transactionID);
    $stmt->bindParam(':user_id', $userID);

    // Execute UPDATE statement
    if ($stmt->execute()) {
        // Return success message
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Transaction updated successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to update transaction'));
    }
}

// Handle DELETE requests
elseif ($method === 'DELETE') {
    // Get transaction ID from URL query string
    $transactionID = $_GET['id'] ?? null;

    // Check if transaction ID is provided
    if (!$transactionID) {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing transaction ID'));
        exit;
    }

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare DELETE statement
    $stmt = $pdo->prepare('DELETE FROM transactions WHERE id = :id AND user_id = :user_id');
    $stmt->bindParam(':id', $transactionID);
    $stmt->bindParam(':user_id', $userID);

    // Execute DELETE statement
    if ($stmt->execute()) {
        // Return success message
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Transaction deleted successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to delete transaction'));
    }
}