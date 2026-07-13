<?php

// Import database connection file
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data from JSON body
$inputData = json_decode(file_get_contents('php://input'), true);

// Check if input data is valid
if (!$inputData) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request data']);
    exit;
}

// Define database table name
$tableName = 'التاريخ';

// Define PDO connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);

// Define user role
$userRole = $_SESSION['role'];

// Define CRUD operations
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Get all records
        if ($userRole === 'admin') {
            $stmt = $pdo->prepare("SELECT * FROM $tableName");
            $stmt->execute();
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($records);
        } else {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
        }
        break;

    case 'POST':
        // Insert new record
        if ($userRole === 'admin') {
            // Validate input data
            if (!isset($inputData['title']) || !isset($inputData['description'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid request data']);
                exit;
            }

            // Sanitize input data
            $title = $pdo->quote($inputData['title']);
            $description = $pdo->quote($inputData['description']);

            // Insert new record
            $stmt = $pdo->prepare("INSERT INTO $tableName (title, description) VALUES ($title, $description)");
            $stmt->execute();

            // Get inserted record ID
            $recordId = $pdo->lastInsertId();

            // Return inserted record
            $stmt = $pdo->prepare("SELECT * FROM $tableName WHERE id = $recordId");
            $stmt->execute();
            $record = $stmt->fetch(PDO::FETCH_ASSOC);
            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode($record);
        } else {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
        }
        break;

    case 'PUT':
        // Update existing record
        if ($userRole === 'admin') {
            // Validate input data
            if (!isset($inputData['id']) || !isset($inputData['title']) || !isset($inputData['description'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid request data']);
                exit;
            }

            // Sanitize input data
            $id = (int) $inputData['id'];
            $title = $pdo->quote($inputData['title']);
            $description = $pdo->quote($inputData['description']);

            // Update existing record
            $stmt = $pdo->prepare("UPDATE $tableName SET title = $title, description = $description WHERE id = $id");
            $stmt->execute();

            // Return updated record
            $stmt = $pdo->prepare("SELECT * FROM $tableName WHERE id = $id");
            $stmt->execute();
            $record = $stmt->fetch(PDO::FETCH_ASSOC);
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($record);
        } else {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
        }
        break;

    case 'DELETE':
        // Delete existing record
        if ($userRole === 'admin') {
            // Validate input data
            if (!isset($inputData['id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid request data']);
                exit;
            }

            // Sanitize input data
            $id = (int) $inputData['id'];

            // Delete existing record
            $stmt = $pdo->prepare("DELETE FROM $tableName WHERE id = $id");
            $stmt->execute();

            // Return success message
            http_response_code(200);
            echo json_encode(['message' => 'Record deleted successfully']);
        } else {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

// Close PDO connection
$pdo = null;

?>