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
    <video autoplay loop muted plays-inline class="background-clip">
        <source src="background.mp4" type="video/mp4">
    </video>
    <form action="login" class="form">
        <a href="#" class="logo"><i class="fa-solid fa-bolt"></i>StrikeFlix</a>
        <p class="title">Register</p>
        <div class="form-group">
            <label>
                <input type="text" required>
                <span>Username</span>
            </label>

            <label>
                <input type="text" required>
                <span>Email</span>
            </label>

            <label>
                <input type="password" id="password" required>
                <span>Password</span>
                <span class="icon" id="togglePassword">
                    <i class="far fa-eye-slash"></i>
                </span>
            </label>

            <label>
                <input type="password" id="passwordConfirm" required>
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
    <script src="Assets/js/main.js"></script>
</body>

</html>