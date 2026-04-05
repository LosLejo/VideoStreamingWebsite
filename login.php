<?php
session_start();
require_once 'config.php'; // Load environment variables
require_once 'db.php';

// Google OAuth setup
$client = new Google_Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(GOOGLE_REDIRECT_URI);
$client->addScope('email');
$client->addScope('profile');
$login_url = $client->createAuthUrl();

// Initialize error and username
$error = '';
$username = '';

// Handle traditional login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['user_name'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        $stmt = $mysqli->prepare("SELECT * FROM users WHERE user_name = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $user_result = $stmt->get_result();

        if ($user_result->num_rows > 0) {
            $user = $user_result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['user_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['profile_pic'] = $user['profile_pic'];
                header("Location: home.php");
                exit;
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "User not found.";
        }
        $stmt->close();
    }
}
?>
