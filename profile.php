<?php
session_start();
require_once 'db.php';


// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}

// You'll need to replace this with your actual database connection
// For now, I'm using sample data from session
$user_name = $_SESSION['user_name'] ?? 'Not provided';
$user_email = $_SESSION['user_email'] ?? 'Not provided';
$user_birth = $_SESSION['user_birth'] ?? 'Not provided'; // You may need to add this to your session
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
                    <label>Full Name</label>
                    <div class="value"><?php echo htmlspecialchars($user_name); ?></div>
                </div>

                <div class="profile-field">
                    <label>Email Address</label>
                    <div class="value"><?php echo htmlspecialchars($user_email); ?></div>
                </div>

                <div class="profile-field">
                    <label>Date of Birth</label>
                    <div class="value"><?php echo htmlspecialchars($user_birth); ?></div>
                </div>

                <div class="profile-field">
                    <label>Member Since</label>
                    <div class="value">
                        <?php
                        // You can store join date in session or fetch from database
                        echo isset($_SESSION['join_date']) ? htmlspecialchars($_SESSION['join_date']) : 'Recently joined';
                        ?>
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

</body>

</html>