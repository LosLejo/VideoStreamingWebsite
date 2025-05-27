<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userDisplay = $_SESSION['username'] ?? 'Guest';
?>

<header class="header">
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
            <input type="text" placeholder="Search..." id="searchInput">
            <a href="#" class="fas fa-search" id="searchIcon"></a>
        </div>

        <div class="dropdown" id="userDropdown">
            <div id="userIcon" class="user-info">
                <i class="fa fa-user"></i>
                <?php if ($isLoggedIn && isset($_SESSION['user_name'])): ?>
                <span class="username"><?= htmlspecialchars($_SESSION['user_name']) ?></span>
                <?php endif; ?>
            </div>

            <div class="dropdown-content" id="dropdownContent">
                <?php if ($isLoggedIn): ?>
                <button class="dropdown-btn" onclick="location.href='profile.php'">Profile</button>
                <button class="dropdown-btn" onclick="location.href='favorites.php'">Favorites</button>
                <button class="dropdown-btn" onclick="location.href='logout.php'">Logout</button>
                <?php else: ?>
                <button class="dropdown-btn" onclick="location.href='login.php'">Log in</button>
                <button class="dropdown-btn" onclick="location.href='register.php'">Sign up</button>
                <?php endif; ?>
            </div>
        </div>

        <i class="fas fa-bars" id="menu-bars"></i>
    </div>
</header>

<style>
.search-wrapper {
    position: relative;
    display: inline-flex;
    align-items: center;
    margin-right: 0.5rem;
}

.search-wrapper input {
    position: absolute;
    right: 0;
    top: 50%;
    transform: translateY(-50%) scaleX(0);
    transform-origin: right center;
    width: 18rem;
    height: 4rem;
    opacity: 0;
    transition: transform 0.4s ease, opacity 0.4s ease;
    color: rgb(255, 255, 255);
    background: rgba(27, 27, 27, 0.95);
    backdrop-filter: blur(10px);
    font-weight: bold;
    font-size: 1.4rem;
    border: 2px solid transparent;
    outline: none;
    padding: 0 1.5rem;
    border-radius: 3rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    pointer-events: none;
    z-index: 100;
}

.search-wrapper.active input {
    transform: translateY(-50%) scaleX(1);
    opacity: 1;
    border-color: var(--yellow);
    pointer-events: auto;
}

.search-wrapper input:focus {
    box-shadow: 0 0 0 3px rgba(255, 247, 0, 0.3), 0 8px 30px rgba(0, 0, 0, 0.4);
    background: rgba(27, 27, 27, 1);
}

.search-wrapper input::placeholder {
    color: rgba(255, 255, 255, 0.6);
    transition: color 0.3s ease;
}

.search-wrapper input:focus::placeholder {
    color: rgba(255, 255, 255, 0.4);
}

.search-wrapper .fas.fa-search {
    position: relative;
    z-index: 101;
    cursor: pointer;
    transition: color 0.3s ease;
}

.search-wrapper.active .fas.fa-search {
    color: var(--yellow);
}

.user-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    color: rgb(255, 255, 255);
    font-size: 1.4rem;
    text-decoration: none;
}

.username {
    color: var(--yellow);
    text-transform: capitalize;
    font-weight: bold;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchIcon = document.getElementById('searchIcon');
    const searchWrapper = searchInput.parentElement;

    const userDropdown = document.getElementById('userDropdown');
    const dropdownContent = document.getElementById('dropdownContent');
    let dropdownTimeout;
    let searchTimeout;

    // Show input on hover
    searchWrapper.addEventListener('mouseenter', function() {
        clearTimeout(searchTimeout);
        searchWrapper.classList.add('active');
        setTimeout(() => searchInput.focus(), 100);
    });

    // Hide input on mouse leave
    searchWrapper.addEventListener('mouseleave', function() {
        if (document.activeElement !== searchInput) {
            searchTimeout = setTimeout(() => {
                searchWrapper.classList.remove('active');
            }, 200);
        }
    });

    // Handle blur (when input loses focus)
    searchInput.addEventListener('blur', function() {
        searchTimeout = setTimeout(() => {
            searchWrapper.classList.remove('active');
        }, 200);
    });

    // Trigger search on click or Enter
    searchIcon.addEventListener('click', function(e) {
        e.preventDefault();
        const query = searchInput.value.trim();
        if (query) {
            window.location.href = `search.php?q=${encodeURIComponent(query)}`;
        } else {
            searchWrapper.classList.add('active');
            setTimeout(() => searchInput.focus(), 100);
        }
    });

    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && this.value.trim()) {
            window.location.href = `search.php?q=${encodeURIComponent(this.value.trim())}`;
        }
    });

    // Dropdown hover with delay
    userDropdown.addEventListener('mouseenter', function() {
        clearTimeout(dropdownTimeout);
        dropdownContent.style.display = 'block';
    });

    userDropdown.addEventListener('mouseleave', function() {
        dropdownTimeout = setTimeout(() => {
            dropdownContent.style.display = 'none';
        }, 100); // 0.5 second delay
    });
});
</script>