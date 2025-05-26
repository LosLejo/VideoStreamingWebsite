<?php
session_start();
require_once 'db.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect and sanitize inputs
    $user_name = trim($_POST['user_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['passwordConfirm'] ?? '';

    // Basic validation
    if (empty($user_name) || empty($email) || empty($password) || empty($passwordConfirm)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif ($password !== $passwordConfirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        // Check if username or email already exists
        $stmt = $mysqli->prepare("SELECT id FROM users WHERE user_name = ? OR email = ?");
        $stmt->bind_param("ss", $user_name, $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "Username or email already exists.";
        } else {
            // Insert user
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $google_id = 'manual_' . md5(uniqid('', true) . $email); // placeholder for manual users
            $stmt = $mysqli->prepare("INSERT INTO users (google_id, user_name, password, email) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $google_id, $user_name, $hashedPassword, $email);
            if ($stmt->execute()) {
                $success = "Registration successful! <a href='login.php'>Log in here</a>.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <link rel="stylesheet" href="Assets/css/reg.css">
    <link rel="stylesheet" href="Assets/css/style.css">
    <title>Register</title>
</head>

<body>
    <?php include 'Assets/HTML/home-header.php' ?>

    <video autoplay loop muted playsinline class="background-clip">
        <source src="Assets/videos/background.mp4" type="video/mp4">
        <img src="background.jpg" alt="Background">
    </video>

    <form action="register.php" method="POST" class="form">
        <a href="#" class="logo"><i class="fa-solid fa-bolt"></i>StrikeFlix</a>
        <p class="title">Register</p>
        <?php if ($error): ?>
            <div style="color:#f33;background:#fff2;padding:10px 14px;border-radius:7px;margin-bottom:14px"><?= $error ?>
            </div>
        <?php elseif ($success): ?>
            <div style="color:#080;background:#efe8;padding:10px 14px;border-radius:7px;margin-bottom:14px"><?= $success ?>
            </div>
        <?php endif; ?>
        <div class="form-group">
            <label>
                <input type="text" name="user_name" required
                    value="<?= isset($_POST['user_name']) ? htmlspecialchars($_POST['user_name']) : '' ?>">
                <span>Username</span>
            </label>

            <label>
                <input type="text" name="email" required
                    value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                <span>Email</span>
            </label>

            <label>
                <input type="password" name="password" id="password" required>
                <span>Password</span>
                <span class="icon" id="togglePassword">
                    <i class="far fa-eye-slash"></i>
                </span>
            </label>

            <label>
                <input type="password" name="passwordConfirm" id="passwordConfirm" required>
                <span>Confirm Password</span>
                <span class="icon" id="togglePasswordConfirm">
                    <i class="far fa-eye-slash"></i>
                </span>
            </label>
            <button class="submit">Register</button>
            <p class="login">
                Already have an account?
                <a href="login.php">Log in</a>
            </p>
        </div>
    </form>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="Assets/js/main.js"></script>
    <script src="Assets/js/reg.js"></script>
</body>

</html>