<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get user role
$user_role = $_SESSION['user_role'];

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET request
if ($method === 'GET') {
    // Validate and sanitize query parameters
    $params = array();
    parse_str($_SERVER['QUERY_STRING'], $params);
    $limit = isset($params['limit']) ? (int) $params['limit'] : 10;
    $offset = isset($params['offset']) ? (int) $params['offset'] : 0;

    // SQL query to select all contracts
    $sql = 'SELECT * FROM contracts LIMIT :limit OFFSET :offset';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch and return contracts
    $contracts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($contracts);
    http_response_code(200);
    exit;
}

// Handle POST request
if ($method === 'POST') {
    // Read JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    if (!isset($input['contract_name']) || !isset($input['contract_start_date']) || !isset($input['contract_end_date'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Invalid request data'));
        exit;
    }

    // SQL query to insert new contract
    $sql = 'INSERT INTO contracts (contract_name, contract_start_date, contract_end_date) VALUES (:contract_name, :contract_start_date, :contract_end_date)';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':contract_name', $input['contract_name'], PDO::PARAM_STR);
    $stmt->bindParam(':contract_start_date', $input['contract_start_date'], PDO::PARAM_STR);
    $stmt->bindParam(':contract_end_date', $input['contract_end_date'], PDO::PARAM_STR);
    $stmt->execute();

    // Return inserted contract ID
    $contract_id = $pdo->lastInsertId();
    header('Content-Type: application/json');
    echo json_encode(array('contract_id' => $contract_id));
    http_response_code(201);
    exit;
}

// Handle PUT request
if ($method === 'PUT') {
    // Read JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    if (!isset($input['contract_id']) || !isset($input['contract_name']) || !isset($input['contract_start_date']) || !isset($input['contract_end_date'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Invalid request data'));
        exit;
    }

    // Check if user is admin
    if ($user_role !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // SQL query to update contract
    $sql = 'UPDATE contracts SET contract_name = :contract_name, contract_start_date = :contract_start_date, contract_end_date = :contract_end_date WHERE contract_id = :contract_id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':contract_id', $input['contract_id'], PDO::PARAM_INT);
    $stmt->bindParam(':contract_name', $input['contract_name'], PDO::PARAM_STR);
    $stmt->bindParam(':contract_start_date', $input['contract_start_date'], PDO::PARAM_STR);
    $stmt->bindParam(':contract_end_date', $input['contract_end_date'], PDO::PARAM_STR);
    $stmt->execute();

    // Return updated contract ID
    header('Content-Type: application/json');
    echo json_encode(array('contract_id' => $input['contract_id']));
    http_response_code(200);
    exit;
}

// Handle DELETE request
if ($method === 'DELETE') {
    // Read JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    if (!isset($input['contract_id'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Invalid request data'));
        exit;
    }

    // Check if user is admin
    if ($user_role !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // SQL query to delete contract
    $sql = 'DELETE FROM contracts WHERE contract_id = :contract_id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':contract_id', $input['contract_id'], PDO::PARAM_INT);
    $stmt->execute();

    // Return deleted contract ID
    header('Content-Type: application/json');
    echo json_encode(array('contract_id' => $input['contract_id']));
    http_response_code(200);
    exit;
}

// Return 405 Method Not Allowed for unsupported methods
http_response_code(405);
header('Content-Type: application/json');
echo json_encode(array('error' => 'Method Not Allowed'));
exit;