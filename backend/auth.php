<?php
// Start the session to store user data
session_start();

// Import DB connection
require_once 'db.php';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    // If user is logged in, return a JSON response with user data
    $response = array('status' => 'logged_in', 'user_id' => $_SESSION['user_id']);
    echo json_encode($response);
    exit;
}

// Handle login request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'login') {
    // Check if required fields are present
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        $response = array('status' => 'error', 'message' => 'Username and password are required');
        echo json_encode($response);
        exit;
    }

    // Prepare SQL query to select user
    $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $_POST['username']);
    $stmt->execute();

    // Fetch user data
    $user = $stmt->fetch();

    // Check if user exists and password is correct
    if ($user && password_verify($_POST['password'], $user['password'])) {
        // If user exists and password is correct, store user data in session
        $_SESSION['user_id'] = $user['id'];
        $response = array('status' => 'logged_in', 'user_id' => $_SESSION['user_id']);
        echo json_encode($response);
        exit;
    } else {
        // If user does not exist or password is incorrect, return error response
        $response = array('status' => 'error', 'message' => 'Invalid username or password');
        echo json_encode($response);
        exit;
    }
}

// Handle register request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'register') {
    // Check if required fields are present
    if (!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['confirm_password'])) {
        $response = array('status' => 'error', 'message' => 'Username, email, password, and confirm password are required');
        echo json_encode($response);
        exit;
    }

    // Check if password and confirm password match
    if ($_POST['password'] !== $_POST['confirm_password']) {
        $response = array('status' => 'error', 'message' => 'Passwords do not match');
        echo json_encode($response);
        exit;
    }

    // Prepare SQL query to insert new user
    $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
    $stmt->bindParam(':username', $_POST['username']);
    $stmt->bindParam(':email', $_POST['email']);
    $stmt->bindParam(':password', password_hash($_POST['password'], PASSWORD_DEFAULT));
    $stmt->execute();

    // If user is registered successfully, return success response
    $response = array('status' => 'registered', 'message' => 'User registered successfully');
    echo json_encode($response);
    exit;
}

// Handle logout request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'logout') {
    // Destroy session to log out user
    session_destroy();
    $response = array('status' => 'logged_out');
    echo json_encode($response);
    exit;
}


This code handles user registration, login, logout, and checks the current session user status. It includes input field validation, prepared statements for SQL queries, and JSON responses for AJAX calls. The code also includes comments to explain the security checks and session handling.