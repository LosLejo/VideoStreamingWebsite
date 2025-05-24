
<?php
session_start();
$isLoggedIn = isset($_SESSION['user_email']);
?>
<header>
    <a href="home" class="logo"><i class="fa-solid fa-bolt"></i>StrikeFlix</a>

    <nav class="navbar">
        <a href="home">Home</a>
        <a href="home#browse">Browse</a>
        <a href="home#new-releases">New Releases</a>
        <a class="active" href="genres">Genres</a>
        <a href="home#popular">Popular Now</a>
    </nav>

    <div class="icons">

        <div class="search-wrapper">
            <input type="text" placeholder="Search...">
            <a href="#" class="fas fa-search"></a>
        </div>

        <div class="dropdown" id="userDropdown">
      <a class="fa fa-user" id="userIcon"></a>
      <div class="dropdown-content" id="dropdownContent">
        <button class="dropdown-btn" id="favoritesBtn">Favorites</button>
        <?php if ($isLoggedIn): ?>
          <button class="dropdown-btn" onclick="location.href='logout.php'">Logout</button>
        <?php else: ?>
          <button class="dropdown-btn" onclick="location.href='login.php'">Log in</button>
          <button class="dropdown-btn" onclick="location.href='register.php'">Sign up</button>
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
      top: 100%;
      margin-top: 2px;
      border-radius: 4px;
    }

    .dropdown-content button {
      background: none;
      border: none;
      color: white;
      padding: 12px 16px;
      text-align: left;
      width: 100%;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }

    .dropdown-content button {
      all: unset;
      display: block;
      text-align: center;
      color: white;
      font-size: 1.6rem;
      cursor: pointer;
      padding: 8px 12px;
      border-radius: 4px;
      transition: background-color 0.3s ease;
      width: auto;
      margin: 0 auto;
    }

    .dropdown-content button:hover {
      background-color: var(--yellow);
      color: var(--black);
    }
      .dropdown-content button:first-child {
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
      }

      .dropdown-content button:last-child {
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
      }

      #userDropdown:hover .dropdown-content,
      .dropdown-content:hover {
        display: block;
      }
    </style>

</header>