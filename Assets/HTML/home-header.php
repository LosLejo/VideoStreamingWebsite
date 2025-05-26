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
    <div class="search-wrapper">
        <input type="text" placeholder="Search..." id="searchInput" autocomplete="off">
        <a href="#" class="fas fa-search" id="searchIcon"></a>
        <div id="searchResultsDropdown" class="search-results-dropdown" style="display:none;"></div>
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
                <button class="dropdown-btn" id="favoritesBtn">Favorites</button>
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
        color: var(--white);
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

    .search-results-dropdown {
        position: absolute;
        top: 110%;
        left: 0;
        width: 100%;
        background: #181818;
        border-radius: 0 0 10px 10px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        max-height: 320px;
        overflow-y: auto;
        z-index: 999;
        display: none;
    }

    .search-result-item {
        display: flex;
        align-items: center;
        padding: 0.7em 1em;
        gap: 1em;
        cursor: pointer;
        border-bottom: 1px solid #232323;
        transition: background 0.15s;
    }

    .search-result-item:last-child {
        border-bottom: none;
    }

    .search-result-item:hover,
    .search-result-item:focus {
        background: #222;
    }

    .search-result-thumb {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: 6px;
        background: #333;
    }

    .search-result-title {
        font-size: 1.1em;
        color: #ffe452;
        font-weight: bold;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        color: var(--white);
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
        // --- Search Bar Expand/Collapse ---
        const searchInput = document.getElementById('searchInput');
        const searchIcon = document.getElementById('searchIcon');
        const searchWrapper = searchInput.parentElement;
        const searchResults = document.getElementById('searchResultsDropdown');
        let animeSearchTimeout = null;
        let searchTimeout = null;

        const userDropdown = document.getElementById('userDropdown');
        const dropdownContent = document.getElementById('dropdownContent');

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
                    // Also hide results on mouse leave
                    searchResults.style.display = "none";
                }, 200);
            }
        });

        // Handle blur (when input loses focus)
        searchInput.addEventListener('blur', function() {
            searchTimeout = setTimeout(() => {
                searchWrapper.classList.remove('active');
                searchResults.style.display = "none";
            }, 200);
        });

        // Optional: Focus input when clicking the search icon
        searchIcon.addEventListener('click', function(e) {
            e.preventDefault();
            searchWrapper.classList.add('active');
            setTimeout(() => searchInput.focus(), 100);
        });

        // --- Live Dropdown Search ---
        function hideSearchResults() {
            searchResults.style.display = "none";
            searchResults.innerHTML = "";
        }

        searchInput.addEventListener('input', function() {
            const q = this.value.trim();
            if (q.length < 1) {
                hideSearchResults();
                return;
            }
            clearTimeout(animeSearchTimeout);
            animeSearchTimeout = setTimeout(() => {
                fetch(`search_anime.php?q=${encodeURIComponent(q)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (!Array.isArray(data) || data.length === 0) {
                            hideSearchResults();
                            return;
                        }
                        let html = "";
                        data.forEach(anime => {
                            html += `
                        <div class="search-result-item" tabindex="0" data-id="${anime.id}">
                            <img class="search-result-thumb" src="${anime.thumbnail ? anime.thumbnail : 'Assets/images/placeholder.png'}" alt="${anime.title}">
                            <span class="search-result-title">${anime.title}</span>
                        </div>`;
                        });
                        searchResults.innerHTML = html;
                        searchResults.style.display = "block";
                    });
            }, 200); // debounce
        });

        // Hide dropdown when input loses focus (with tiny delay for click)
        searchInput.addEventListener('blur', function() {
            setTimeout(hideSearchResults, 200);
        });

        // Go to anime on click
        searchResults.addEventListener('mousedown', function(e) {
            const item = e.target.closest('.search-result-item');
            if (item) {
                const animeId = item.getAttribute('data-id');
                if (animeId) {
                    window.location.href = `watch.php?series_id=${animeId}`;
                }
            }
        });

        // Block Enter key from submitting form or causing page navigation
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === "Enter") {
                e.preventDefault();
                return false;
            }
            // Optional: Hide dropdown on Escape
            if (e.key === "Escape") hideSearchResults();
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