<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userDisplay = $_SESSION['username'] ?? 'Guest';
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
        backdrop-filter: blur(1rem);
        font-weight: bold;
        font-size: 1.4rem;
        border: 0.2rem solid transparent;
        outline: none;
        padding: 0 1.5rem;
        border-radius: 0.3rem;
        box-shadow: 0 0.4rem 2rem rgba(0, 0, 0, 0.3);
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
        box-shadow: 0 0 0 0.3rem rgba(255, 247, 0, 0.3), 0 0.8rem 3rem rgba(0, 0, 0, 0.4);
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

    /* ADD MISSING DROPDOWN CSS */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        top: 100%;
        margin-top: 0.5rem;
        padding: 1rem;
        background-color: #1b1b1b;
        min-width: 15rem;
        border-radius: 1rem;
        box-shadow: 0 0.8rem 1.6rem rgba(0, 0, 0, 0.3);
        z-index: 1000;
        border: 0.1rem solid #333;
    }

    .dropdown-content button {
        display: block;
        width: 100%;
        text-align: left;
        color: white;
        font-size: 1.4rem;
        cursor: pointer;
        padding: 0.8rem 1rem;
        border-radius: 0.5rem;
        transition: background-color 0.3s ease;
        background: transparent;
        margin-bottom: 0.5rem;
        border: none;
    }

    .dropdown-content button:hover {
        background-color: var(--yellow);
        color: var(--black);
    }

    .dropdown-content button:last-child {
        margin-bottom: 0;
    }

    /* Mobile hamburger menu */
    #menu-bars {
        display: none;
    }

    /* MOBILE RESPONSIVE STYLES - FIXED VERSION */
    @media (max-width: 76.8rem) {

        /* Show hamburger menu on mobile */
        header .icons #menu-bars {
            display: inline-block !important;
            cursor: pointer;
            font-size: 2.5rem;
            margin-left: 1rem;
            background: var(--yellow) !important;
            color: var(--black) !important;
            border-radius: 25% !important;
            height: 4.5rem !important;
            width: 4.5rem !important;
            line-height: 4.5rem !important;
            text-align: center !important;
            transition: background 0.3s, color 0.3s !important;
        }

        header .icons #menu-bars:hover {
            background: var(--black) !important;
            color: var(--yellow) !important;
        }

        /* Mobile navbar styles - CORRECTED */
        header .navbar {
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            right: 0 !important;
            background: var(--black) !important;
            border-top: .1rem solid rgba(255, 255, 255, .1) !important;
            border-bottom: .1rem solid rgba(255, 255, 255, .1) !important;
            padding: 1rem !important;
            clip-path: polygon(0 0, 100% 0, 100% 0, 0 0) !important;
            transition: clip-path 0.3s ease !important;
            z-index: 1000 !important;
            display: block !important;
            /* Override any display: none */
        }

        header .navbar.active {
            clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%) !important;
        }

        header .navbar a {
            display: block !important;
            padding: 1.5rem !important;
            margin: 1rem 0 !important;
            font-size: 2rem !important;
            background: var(--yellow) !important;
            color: var(--black) !important;
            border-radius: 0.5rem !important;
            text-align: center !important;
        }

        header .navbar a:hover {
            background: #fff !important;
            color: var(--black) !important;
        }

        /* Rest of mobile styles... */
        .dropdown-content {
            position: fixed !important;
            right: 1rem !important;
            top: 7rem !important;
            z-index: 2000 !important;
            min-width: 18rem;
            max-width: 85vw;
        }

        .user-info {
            padding: 0.8rem;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease;
        }

        .user-info:hover,
        .user-info:active {
            background-color: rgba(255, 247, 0, 0.1);
        }

        .search-wrapper input {
            width: 15rem;
        }

        .username {
            display: none;
        }
    }

    @media (max-width: 46.8rem) {
        .search-wrapper input {
            width: 12rem;
        }

        header {
            padding: 1rem 5% !important;
        }

        .icons {
            gap: 0.5rem;
        }

        .dropdown-content {
            min-width: 20rem !important;
            right: 0.5rem !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchIcon = document.getElementById('searchIcon');
        const searchWrapper = searchInput ? searchInput.parentElement : null;
        const userDropdown = document.getElementById('userDropdown');
        const dropdownContent = document.getElementById('dropdownContent');
        const menuBars = document.getElementById('menu-bars');
        const navbar = document.querySelector('.navbar');

        let dropdownTimeout;
        let searchTimeout;

        console.log('Elements found:', {
            menuBars: !!menuBars,
            navbar: !!navbar,
            userDropdown: !!userDropdown,
            dropdownContent: !!dropdownContent
        });

        // Function to check if we're on mobile
        function isMobile() {
            return window.innerWidth <= 768;
        }

        // Mobile menu toggle functionality - ENHANCED
        if (menuBars && navbar) {
            console.log('Setting up hamburger menu...');

            menuBars.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                console.log('Hamburger clicked!', 'Currently active:', navbar.classList.contains('active'));

                navbar.classList.toggle('active');

                // Close dropdown when mobile menu opens
                if (navbar.classList.contains('active')) {
                    if (dropdownContent) {
                        dropdownContent.style.display = 'none';
                    }
                }

                console.log('After toggle:', navbar.classList.contains('active'));
            });

            // Close mobile menu when clicking on a link
            const navLinks = navbar.querySelectorAll('a');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    console.log('Nav link clicked, closing menu');
                    navbar.classList.remove('active');
                });
            });
        } else {
            console.error('Menu bars or navbar not found!', {
                menuBars,
                navbar
            });
        }

        // Search functionality
        if (searchWrapper && searchInput) {
            searchWrapper.addEventListener('mouseenter', function() {
                if (!isMobile()) {
                    clearTimeout(searchTimeout);
                    searchWrapper.classList.add('active');
                    setTimeout(() => searchInput.focus(), 100);
                }
            });

            searchWrapper.addEventListener('mouseleave', function() {
                if (!isMobile() && document.activeElement !== searchInput) {
                    searchTimeout = setTimeout(() => {
                        searchWrapper.classList.remove('active');
                    }, 200);
                }
            });

            searchInput.addEventListener('blur', function() {
                searchTimeout = setTimeout(() => {
                    searchWrapper.classList.remove('active');
                }, 200);
            });

            if (searchIcon) {
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
            }

            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && this.value.trim()) {
                    window.location.href = `search.php?q=${encodeURIComponent(this.value.trim())}`;
                }
            });
        }

        // Dropdown functionality
        if (userDropdown && dropdownContent) {
            userDropdown.addEventListener('mouseenter', function() {
                if (!isMobile()) {
                    clearTimeout(dropdownTimeout);
                    dropdownContent.style.display = 'block';
                }
            });

            userDropdown.addEventListener('mouseleave', function() {
                if (!isMobile()) {
                    dropdownTimeout = setTimeout(() => {
                        dropdownContent.style.display = 'none';
                    }, 100);
                }
            });

            userDropdown.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                if (navbar && navbar.classList.contains('active')) {
                    navbar.classList.remove('active');
                }

                const isVisible = dropdownContent.style.display === 'block';
                dropdownContent.style.display = isVisible ? 'none' : 'block';

                console.log('Dropdown clicked. Mobile:', isMobile(), 'Visible:', !isVisible);
            });

            document.addEventListener('click', function(e) {
                if (!userDropdown.contains(e.target)) {
                    dropdownContent.style.display = 'none';
                }
            });
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            if (navbar && menuBars && !navbar.contains(e.target) && !menuBars.contains(e.target)) {
                navbar.classList.remove('active');
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (dropdownContent) dropdownContent.style.display = 'none';
            if (navbar) navbar.classList.remove('active');
            if (searchWrapper) searchWrapper.classList.remove('active');
        });
    });
</script>