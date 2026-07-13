<?php

// Import database connection settings
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized access'));
    exit;
}

// Get input data from JSON or POST request
$inputData = json_decode(file_get_contents('php://input'), true) ?: $_POST;

// Validate input data
if (!isset($inputData['id']) && !isset($inputData['property_id']) && !isset($inputData['date']) && !isset($inputData['description'])) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid input data'));
    exit;
}

// Sanitize input data
$property_id = (int) ($inputData['property_id'] ?? $inputData['id']);
$date = (string) ($inputData['date'] ?? '');
$description = (string) ($inputData['description'] ?? '');

// Connect to database
$db = new PDO($dsn, $username, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET request
if (isset($_GET['action']) && $_GET['action'] == 'get_all') {
    // Select all property history records
    $stmt = $db->prepare('SELECT * FROM property_history ORDER BY id DESC');
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
} elseif (isset($_GET['action']) && $_GET['action'] == 'get_one') {
    // Select one property history record by id
    $stmt = $db->prepare('SELECT * FROM property_history WHERE id = :id');
    $stmt->bindParam(':id', $property_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Record not found'));
    }
    exit;
}

// Handle POST request
if (isset($_GET['action']) && $_GET['action'] == 'create') {
    // Insert new property history record
    if ($_SESSION['user_role'] == 'admin') {
        $stmt = $db->prepare('INSERT INTO property_history (property_id, date, description) VALUES (:property_id, :date, :description)');
        $stmt->bindParam(':property_id', $property_id);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        http_response_code(201);
        echo json_encode(array('message' => 'Record created successfully'));
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden access'));
    }
    exit;
}

// Handle PUT request
if (isset($_GET['action']) && $_GET['action'] == 'update') {
    // Update existing property history record
    if ($_SESSION['user_role'] == 'admin') {
        $stmt = $db->prepare('UPDATE property_history SET date = :date, description = :description WHERE id = :id');
        $stmt->bindParam(':id', $property_id);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        http_response_code(200);
        echo json_encode(array('message' => 'Record updated successfully'));
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden access'));
    }
    exit;
}

// Handle DELETE request
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    // Delete property history record by id
    if ($_SESSION['user_role'] == 'admin') {
        $stmt = $db->prepare('DELETE FROM property_history WHERE id = :id');
        $stmt->bindParam(':id', $property_id);
        $stmt->execute();
        http_response_code(200);
        echo json_encode(array('message' => 'Record deleted successfully'));
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden access'));
    }
    exit;
}

http_response_code(404);
echo json_encode(array('error' => 'Invalid request'));
exit;

?>