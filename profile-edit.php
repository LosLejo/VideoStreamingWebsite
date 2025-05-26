<?php
session_start();
require_once 'db.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user info
$stmt = $mysqli->prepare("SELECT user_name, email, profile_pic, bio, birthdate FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $bio = trim($_POST["bio"]);
    $birth = $_POST["birth"];

    $stmt = $mysqli->prepare("UPDATE users SET bio = ?, birthdate = ? WHERE id = ?");
    $stmt->bind_param("ssi", $bio, $birth, $user_id);
    $stmt->execute();

    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - StrikeFlix</title>
    <link rel="icon" type="image/svg+xml" href="assets/bolt-solid.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="Assets/css/style.css">
    <link rel="stylesheet" href="Assets/css/profile.css">
</head>

<body>
    <?php include 'Assets/HTML/home-header.php' ?>
    <section class="bg">
        <div class="profile-container">
            <div class="profile-header">
                <div class="profile-avatar">
                    <i class="fas fa-user-edit"></i>
                </div>
                <h1>Edit Profile</h1>
            </div>

            <form method="POST" class="profile-details">
                <div class="profile-field">
                    <label>Full Name</label>
                    <div class="value"><?= htmlspecialchars($user['user_name']) ?></div>
                </div>

                <div class="profile-field">
                    <label>Email Address</label>
                    <div class="value"><?= htmlspecialchars($user['email']) ?></div>
                </div>

                <div class="profile-field">
                    <label>Bio</label>
                    <textarea name="bio" id="bio"
                        class="profile-bio"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                </div>

                <div class="profile-field">
                    <label>Date of Birth</label>
                    <input type="date" name="birth" class="profile-bio"
                        value="<?= htmlspecialchars($user['birthdate'] ?? '') ?>">
                </div>

                <div class="profile-actions">
                    <button type="submit" class="profile-btn">Save Changes</button>
                    <a href="profile.php" class="profile-btn secondary">Cancel</a>
                </div>
            </form>
        </div>
    </section>
    <?php include 'Assets/HTML/footer.html' ?>
    <script src="Assets/js/main.js"></script>
</body>
<style>
    /* ... (your CSS remains unchanged) ... */
</style>

</html>