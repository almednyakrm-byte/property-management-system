<?php
require_once 'db.php';

// Get user role and id from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Check if user is logged in
if (!$userID) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare('SELECT * FROM إخطار');
    $stmt->execute();
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($notifications);
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request body'));
        exit;
    }

    // Validate and sanitize input
    $title = trim($input['title']);
    $message = trim($input['message']);
    if (empty($title) || empty($message)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Title and message are required'));
        exit;
    }

    // Insert notification
    $stmt = $pdo->prepare('INSERT INTO إخطار (title, message, user_id) VALUES (:title, :message, :user_id)');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':message', $message);
    $stmt->bindParam(':user_id', $userID);
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array('message' => 'Notification created successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to create notification'));
    }
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request body'));
        exit;
    }

    // Validate and sanitize input
    $id = $input['id'];
    $title = trim($input['title']);
    $message = trim($input['message']);
    if (empty($title) || empty($message)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Title and message are required'));
        exit;
    }

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Update notification
    $stmt = $pdo->prepare('UPDATE إخطار SET title = :title, message = :message WHERE id = :id AND user_id = :user_id');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':message', $message);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':user_id', $userID);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Notification updated successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to update notification'));
    }
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request body'));
        exit;
    }

    // Validate and sanitize input
    $id = $input['id'];
    if (empty($id)) {
        http_response_code(400);
        echo json_encode(array('error' => 'ID is required'));
        exit;
    }

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Delete notification
    $stmt = $pdo->prepare('DELETE FROM إخطار WHERE id = :id AND user_id = :user_id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':user_id', $userID);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Notification deleted successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to delete notification'));
    }
}