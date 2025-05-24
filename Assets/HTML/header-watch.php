<?php
session_start();
$isLoggedIn = isset($_SESSION['user_email']);
?>


<header>
    <a href="home.php" class="logo"><i class="fa-solid fa-bolt"></i>StrikeFlix</a>

    <nav class="navbar">
        <a href="home.php">Home</a>
        <a href="home.php#browse">Browse</a>
        <a href="home.php#new-releases">New Releases</a>
        <a href="genres.php">Genres</a>
        <a href="home.php#popular">Popular Now</a>
    </nav>

    <div class="icons">
        <div class="search-wrapper">
            <input type="text" placeholder="Search...">
            <a href="#" class="fas fa-search"></a>
        </div>

        <div class="dropdown">
            <a class="fa fa-user" id="sign-in"></a>
            <div class="dropdown-content">
                <button class="dropdown-btn">Favorites</button>

                <?php if (!$isLoggedIn): ?>
                <button class="dropdown-btn" onclick="location.href='login.php'">Log in</button>
                <button class="dropdown-btn" onclick="location.href='register.php'">Create Account</button>
                <?php else: ?>
                <button class="dropdown-btn" onclick="location.href='logout.php'">Log out</button>
                <?php endif; ?>
            </div>
        </div>


        <i class="fas fa-bars" id="menu-bars"></i>
    </div>

    <style>
    .search-wrapper {
        position: relative;
        display: inline-flex;
        align-items: center;
    }

    .search-wrapper input {
        width: 0;
        opacity: 0;
        transition: 0.5s;
        color: #fff;
        background: transparent;
        font-weight: bolder;
        font-size: 14px;
        border: none;
        outline: none;
        margin-right: 0.5rem;
    }

    .search-wrapper:hover input,
    .search-wrapper input:hover {
        width: 200px;
        opacity: 1;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #333;
        min-width: 120px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
        right: 0;
    }

    .dropdown-content button {
        background: none;
        border: none;
        color: white;
        padding: 10px 16px;
        text-align: left;
        width: 100%;
        cursor: pointer;
    }

    .dropdown-content button:hover {
        background-color: #555;
    }

    #userDropdown:hover .dropdown-content {
        display: block;
    }
    </style>
</header>
<script>
let isLoggedIn = false;

const dropdownContent = document.getElementById('dropdownContent');

function renderDropdown() {
    if (isLoggedIn) {
        dropdownContent.innerHTML = `
      <button class="dropdown-btn" id="favoritesBtn">Favorites</button>
      <button class="dropdown-btn" id="logoutBtn" onclick="location.href='logout.php'">Logout</button>
    `;


        document.getElementById('logoutBtn').addEventListener('click', () => {
            logoutUser();
        });
    } else {
        dropdownContent.innerHTML = `
      <button class="dropdown-btn" id="favoritesBtn">Favorites</button>
      <button class="dropdown-btn" id="signInBtn" onclick="location.href='login.php'">Sign In</button>
      <button class="dropdown-btn" id="signUpBtn" onclick="location.href='register.php'">Sign Up</button>
    `;
    }
}

function loginUser() {
    isLoggedIn = true;
    renderDropdown();
}

function logoutUser() {
    isLoggedIn = false;
    renderDropdown();

}


renderDropdown();


setTimeout(() => {
    loginUser();
}, 3000);
</script>