<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'config.php'; // Load environment variables
require_once 'db.php';

$client = new Google_Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(GOOGLE_REDIRECT_URI);
$client->addScope('email');
$client->addScope('profile');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (isset($token['error'])) {
        die("Google error: " . htmlspecialchars($token['error_description'] ?? $token['error']));
    }

    $client->setAccessToken($token['access_token']);
    $oauth = new Google_Service_Oauth2($client);
    $userinfo = $oauth->userinfo->get();

    // Extract user info
    $google_id = $userinfo->id;
    $user_name = $userinfo->name;  // Use Google user's name as user_name
    $email = $userinfo->email;
    $profile_pic = $userinfo->picture;

    // Generate a random password for Google users (not used for login)
    $dummy_password = password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT);

    // Check if user already exists
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE google_id = ?");
    $stmt->bind_param("s", $google_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User exists, log them in
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['user_name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['profile_pic'] = $user['profile_pic'];
        header("Location: home.php");
    } else {
        // Create new user
        $stmt = $mysqli->prepare("INSERT INTO users (google_id, user_name, password, email, profile_pic) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $google_id, $user_name, $dummy_password, $email, $profile_pic);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $mysqli->insert_id;
            $_SESSION['user_name'] = $user_name;
            $_SESSION['email'] = $email;
            $_SESSION['profile_pic'] = $profile_pic;
            header("Location: home.php");
        } else {
            die("Error creating user: " . $stmt->error);
        }
    }
    $stmt->close();
}
?>
