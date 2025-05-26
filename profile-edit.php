<?php
session_start();
require_once 'db.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Redirect if not logged in
if (!isset($_SESSION['google_id'])) {
    header("Location: login.php");
    exit();
}

$google_id = $_SESSION['google_id'];

// Get user info
$stmt = $mysqli->prepare("SELECT name, email, profile_pic, bio FROM users WHERE google_id = ?");
$stmt->bind_param("s", $google_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $bio = trim($_POST["bio"]);

    $stmt = $mysqli->prepare("UPDATE users SET bio = ? WHERE google_id = ?");
    $stmt->bind_param("ss", $bio, $google_id);
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
                    <div class="value"><?= htmlspecialchars($user['name']) ?></div>
                </div>

                <div class="profile-field">
                    <label>Email Address</label>
                    <div class="value"><?= htmlspecialchars($user['email']) ?></div>
                </div>

                <div class="profile-field">
                    <textarea name="bio" id="bio"
                        class="profile-bio"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                </div>

                <div class="profile-field">
                    <input type="date" name="birth" class="profile-bio" value="<?= htmlspecialchars($user['birthdate'] ?? '') ?>">
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
    .bg {
        background: url('Assets/images/profile_bg.jpg') no-repeat center center;
        background-size: cover;

    }

    .profile-container {
        max-width: 650px;
        margin: 5rem auto 0;
        padding: 3rem;
        background: rgba(0, 0, 0, 0.75);
        border-radius: 1rem;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.3);
    }

    .profile-header {
        display: flex;
        align-items: center;
        gap: 3.5rem;
        margin-bottom: 2rem;
        text-align: left;
    }

    .profile-header h1 {
        color: var(--yellow);
        font-size: 3rem;
        margin-bottom: 0;
    }

    .profile-avatar {
        width: 12rem;
        height: 12rem;
        border-radius: 50%;
        background: var(--yellow);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 0 2rem 0;
        /* Remove auto-centering */
        font-size: 4rem;
        color: var(--black);
    }

    .profile-details {
        display: grid;
        gap: 2rem;
    }

    .profile-field {
        background: rgba(255, 255, 255, 0.05);
        padding: 2rem;
        border-radius: 0.5rem;
        border-left: 4px solid var(--yellow);
    }

    .profile-field label {
        display: block;
        color: var(--yellow);
        font-size: 1.4rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .profile-field .value {
        color: #fff;
        font-size: 1.6rem;
        word-break: break-word;
    }

    .profile-actions {
        margin-top: 3rem;
        text-align: center;
        display: flex;
        gap: 2rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .profile-btn {
        display: inline-block;
        padding: 1.2rem 2.5rem;
        background: var(--yellow);
        color: var(--black);
        text-decoration: none;
        border-radius: 0.5rem;
        font-weight: bold;
        font-size: 1.4rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .profile-btn:hover {
        background: #fff;
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1.5rem rgba(255, 193, 7, 0.4);
    }

    .profile-btn.secondary {
        background: transparent;
        color: var(--yellow);
        border: 2px solid var(--yellow);
    }

    .profile-btn.secondary:hover {
        background: var(--yellow);
        color: var(--black);
    }

    @media (max-width: 768px) {
        .profile-container {
            margin: 10rem 2rem 3rem;
            padding: 2rem;
        }

        .profile-header h1 {
            font-size: 2.5rem;
        }

        .profile-avatar {
            width: 10rem;
            height: 10rem;
            font-size: 3rem;
        }

        .profile-actions {
            flex-direction: column;
            align-items: center;
        }

        .profile-btn {
            width: 100%;
            max-width: 300px;
        }
    }
</style>

</html>