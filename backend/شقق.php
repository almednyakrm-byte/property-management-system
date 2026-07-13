<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = [
    '/shqqs' => [
        'GET' => function () {
            // Select all shqqs
            $stmt = $pdo->prepare('SELECT * FROM shqqs');
            $stmt->execute();
            $shqqs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($shqqs);
        },
        'POST' => function () {
            // Validate input data
            if (!isset($input['name']) || !isset($input['address']) || !isset($input['price'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid input']);
                exit;
            }

            // Sanitize input data
            $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
            $address = filter_var($input['address'], FILTER_SANITIZE_STRING);
            $price = filter_var($input['price'], FILTER_SANITIZE_NUMBER_INT);

            // Insert new shqq
            $stmt = $pdo->prepare('INSERT INTO shqqs (name, address, price) VALUES (:name, :address, :price)');
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':price', $price);
            $stmt->execute();

            // Return created shqq
            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Shqq created successfully']);
        }
    ],
    '/shqqs/:id' => [
        'GET' => function ($id) {
            // Select shqq by id
            $stmt = $pdo->prepare('SELECT * FROM shqqs WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $shqq = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if shqq exists
            if (!$shqq) {
                http_response_code(404);
                echo json_encode(['error' => 'Shqq not found']);
                exit;
            }

            // Return shqq
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($shqq);
        },
        'PUT' => function ($id) {
            // Validate input data
            if (!isset($input['name']) || !isset($input['address']) || !isset($input['price'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid input']);
                exit;
            }

            // Sanitize input data
            $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
            $address = filter_var($input['address'], FILTER_SANITIZE_STRING);
            $price = filter_var($input['price'], FILTER_SANITIZE_NUMBER_INT);

            // Check if user is admin
            if ($_SESSION['user_role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Forbidden']);
                exit;
            }

            // Update shqq
            $stmt = $pdo->prepare('UPDATE shqqs SET name = :name, address = :address, price = :price WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':price', $price);
            $stmt->execute();

            // Return updated shqq
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Shqq updated successfully']);
        },
        'DELETE' => function ($id) {
            // Check if user is admin
            if ($_SESSION['user_role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Forbidden']);
                exit;
            }

            // Delete shqq
            $stmt = $pdo->prepare('DELETE FROM shqqs WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Return deleted shqq
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Shqq deleted successfully']);
        }
    ]
];

// Get route and method from URL
$matches = [];
if (preg_match('/^\/shqqs\/?(\d*)$/', $_SERVER['REQUEST_URI'], $matches)) {
    $id = $matches[1];
    $route = '/shqqs';
    if ($id) {
        $route .= '/' . $id;
    }
} else {
    $route = '/shqqs';
}

// Get method from request
$method = $_SERVER['REQUEST_METHOD'];

// Check if route and method are valid
if (!isset($routes[$route][$method])) {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Call route handler
$routes[$route][$method]($id);