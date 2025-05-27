<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch user info from database
$user_id = $_SESSION['user_id'];

$stmt = $mysqli->prepare("SELECT user_name, email, birthdate, join_date FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();


if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $user_name = $user['user_name'] ?? 'Not provided';
    $user_email = $user['email'] ?? 'Not provided';
    $user_birth = $user['birthdate'] ?? 'Not provided';
    if ($user_birth && $user_birth !== 'Recently joined') {
        $user_birth = date('F d, Y', strtotime($user_birth));
    }
    $join_date = $user['join_date'] ?? 'Recently joined';
    if ($join_date && $join_date !== 'Recently joined') {
        $join_date = date('F d, Y', strtotime($join_date));
    }
} else {
    // User not found (should not happen), force logout
    session_destroy();
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - StrikeFlix</title>
    <link rel="icon" type="image/svg+xml" href="assets/bolt-solid.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="Assets/css/style.css">
    <link rel="stylesheet" href="Assets/css/profile.css">

    <style>
    .bg {
        background: url('Assets/images/profile_bg.jpg') no-repeat center center;
        background-size: cover;
    }
    </style>

</head>

<body>
    <?php include 'Assets/HTML/home-header.php' ?>
    <section class="bg">
        <div class="profile-container">
            <div class="profile-header">
                <div class="profile-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <h1>My Account</h1>
            </div>

            <div class="profile-details">
                <div class="profile-field">
                    <label>User Name</label>
                    <div class="value"><?php echo htmlspecialchars($user_name); ?></div>
                </div>

                <div class="profile-field">
                    <label>Email</label>
                    <div class="value"><?php echo htmlspecialchars($user_email); ?></div>
                </div>

                <div class="profile-field">
                    <label>Date of Birth</label>
                    <div class="value">
                        <?php echo htmlspecialchars($user_birth); ?>
                    </div>
                </div>

                <div class="profile-field">
                    <label>Member Since</label>
                    <div class="value">
                        <?php echo htmlspecialchars($join_date); ?>
                    </div>
                </div>
            </div>

            <div class="profile-actions">
                <a href="profile-edit.php" class="profile-btn">Edit Profile</a>
                <a href="home.php" class="profile-btn secondary">Back to Home</a>
            </div>
        </div>
    </section>
    <?php include 'Assets/HTML/footer.html' ?>

    <script src="Assets/js/main.js"></script>
</body>

</html>