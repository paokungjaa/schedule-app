<?php
session_start();
require 'db.php';

// Set session cookie to expire when the browser is closed
ini_set('session.cookie_lifetime', 0);  // Expire immediately after the session ends

if (isset($_SESSION['user_id'])) {
    header("Location: ../frontend/dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username']; // or use 'email' if you're using email for login
    $password = $_POST['password'];

    // Fetch user data from the database based on the username or email
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $db_username, $db_password);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        // Use password_verify to check if the password is correct
        if (password_verify($password, $db_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $db_username;
            header("Location: ../frontend/dashboard.php");  // Redirect to dashboard after successful login
            exit;
        } else {
            echo "Invalid login. Please check your username and password.";
        }
    } else {
        echo "Invalid login. Please check your username and password.";
    }
}
?>
