<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    '/get-all' => 'getAll',
    '/get-one' => 'getOne',
    '/create' => 'create',
    '/update' => 'update',
    '/delete' => 'delete'
);

// Get route
$match = null;
foreach ($routes as $route => $method) {
    if (strpos($_SERVER['REQUEST_URI'], $route) !== false) {
        $match = $route;
        break;
    }
}

// Call method
if ($match) {
    $method = $routes[$match];
    $method($input);
} else {
    http_response_code(404);
    echo json_encode(array('error' => 'Not found'));
}

// Helper functions
function getAll($input) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM الإخطار');
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($result);
}

function getOne($input) {
    global $db;
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        return;
    }
    $stmt = $db->prepare('SELECT * FROM الإخطار WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
        return;
    }
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($result);
}

function create($input) {
    global $db;
    // Validate input
    if (!isset($input['title']) || !isset($input['content'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        return;
    }
    // Sanitize input
    $title = htmlspecialchars($input['title']);
    $content = htmlspecialchars($input['content']);
    // Insert data
    $stmt = $db->prepare('INSERT INTO الإخطار (title, content) VALUES (:title, :content)');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Created successfully'));
}

function update($input) {
    global $db;
    if (!isset($input['id']) || !isset($input['title']) || !isset($input['content'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        return;
    }
    // Sanitize input
    $title = htmlspecialchars($input['title']);
    $content = htmlspecialchars($input['content']);
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        return;
    }
    // Update data
    $stmt = $db->prepare('UPDATE الإخطار SET title = :title, content = :content WHERE id = :id');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Updated successfully'));
}

function delete($input) {
    global $db;
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        return;
    }
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        return;
    }
    // Delete data
    $stmt = $db->prepare('DELETE FROM الإخطار WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Deleted successfully'));
}

?>