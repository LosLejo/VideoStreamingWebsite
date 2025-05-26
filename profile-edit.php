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

    // Compose birthdate from dropdowns
    $birth_year = $_POST['birth_year'] ?? '';
    $birth_month = $_POST['birth_month'] ?? '';
    $birth_day = $_POST['birth_day'] ?? '';
    $birth = null;
    if (checkdate((int)$birth_month, (int)$birth_day, (int)$birth_year)) {
        $birth = sprintf('%04d-%02d-%02d', $birth_year, $birth_month, $birth_day);
    }

    $stmt = $mysqli->prepare("UPDATE users SET bio = ?, birthdate = ? WHERE id = ?");
    $stmt->bind_param("ssi", $bio, $birth, $user_id);
    $stmt->execute();

    header("Location: profile.php");
    exit();
}

// Prepare default selected values for the dropdowns
$birth_year = $birth_month = $birth_day = '';
if (!empty($user['birthdate'])) {
    $date = DateTime::createFromFormat('Y-m-d', $user['birthdate']);
    if ($date) {
        $birth_year = $date->format('Y');
        $birth_month = $date->format('m');
        $birth_day = $date->format('d');
    }
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

    <style>
        .bg {
            background: url('Assets/images/profile_bg.jpg') no-repeat center center;
            background-size: cover;
        }

        .profile-bio-area {
            width: 100%;
            min-height: 80px;
            max-height: 180px;
            resize: vertical;
            padding: 1rem;
            font-size: 1.5rem;
            border-radius: 0.4rem;
            border: 1px solid #ccc;
            background: rgba(30, 30, 30, 0.84);
            color: #fff;
            transition: box-shadow 0.2s;
        }

        .profile-bio-area:focus {
            outline: none;
            box-shadow: 0 0 0 2px var(--yellow);
        }

        .birthdate-selects {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .birthdate-selects select {
            padding: 0.5rem 1rem;
            border-radius: 0.4rem;
            border: 1px solid #ccc;
            font-size: 1.1rem;
            background: rgba(255, 255, 255, 0.1);
            color: yellow;
            background-color: rgba(22, 22, 22, 0.88);
        }

        .birthdate-selects select:focus {
            outline: none;
            box-shadow: 0 0 0 2px var(--yellow);
        }

        .profile-field label {
            margin-bottom: 0.5rem;
            display: block;
        }
    </style>

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

            <form method="POST" class="profile-details" autocomplete="off">
                <div class="profile-field">
                    <label>User Name</label>
                    <div class="value"><?= htmlspecialchars($user['user_name']) ?></div>
                </div>

                <div class="profile-field">
                    <label>Email</label>
                    <div class="value"><?= htmlspecialchars($user['email']) ?></div>
                </div>

                <div class="profile-field">
                    <label for="bio">Bio</label>
                    <textarea name="bio" id="bio" class="profile-bio-area" maxlength="255"
                        placeholder="Write something about yourself..."><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                    <div style="font-size: 1.5rem; color: #ccc; margin-top: 5px;">Max 255 characters.</div>
                </div>

                <div class="profile-field">
                    <label>Date of Birth</label>
                    <div class="birthdate-selects">
                        <select name="birth_month" required>
                            <option value="">Month</option>
                            <?php
                            for ($m = 1; $m <= 12; $m++) {
                                $selected = ($birth_month == sprintf('%02d', $m)) ? 'selected' : '';
                                $monthName = date('F', mktime(0, 0, 0, $m, 1));
                                echo "<option value='" . sprintf('%02d', $m) . "' $selected>$monthName</option>";
                            }
                            ?>
                        </select>
                        <select name="birth_day" required>
                            <option value="">Day</option>
                            <?php
                            for ($d = 1; $d <= 31; $d++) {
                                $selected = ($birth_day == sprintf('%02d', $d)) ? 'selected' : '';
                                echo "<option value='" . sprintf('%02d', $d) . "' $selected>$d</option>";
                            }
                            ?>
                        </select>
                        <select name="birth_year" required>
                            <option value="">Year</option>
                            <?php
                            $currentYear = date('Y');
                            for ($y = $currentYear - 100; $y <= $currentYear; $y++) {
                                $selected = ($birth_year == $y) ? 'selected' : '';
                                echo "<option value='$y' $selected>$y</option>";
                            }
                            ?>
                        </select>
                    </div>
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

</html>