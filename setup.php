<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'db.php';

// Users table
$createUserTable = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    google_id VARCHAR(255) NOT NULL UNIQUE,
    user_name VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    profile_pic VARCHAR(255),
    birthdate DATE,
    bio VARCHAR(255),
    join_date DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;";

// Main anime series table (this will hold ALL anime series)
$createAnimeTable = "CREATE TABLE IF NOT EXISTS anime_series (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    thumbnail TEXT,
    total_episodes INT DEFAULT 0,
    status VARCHAR(50) DEFAULT 'ongoing',
    genre VARCHAR(255),
    release_date DATE,
    rating DECIMAL(3,1) DEFAULT 5.0,
    is_featured BOOLEAN DEFAULT FALSE,
    is_new_release BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;";

// Episodes table - connected to anime_series
$createEpisodesTable = "CREATE TABLE IF NOT EXISTS episodes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    anime_series_id INT NOT NULL,
    episode_number INT NOT NULL,
    episode_title VARCHAR(255),
    video_url TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (anime_series_id) REFERENCES anime_series(id) ON DELETE CASCADE,
    UNIQUE KEY unique_series_episode (anime_series_id, episode_number)
) ENGINE=InnoDB;";

// Genre categories table (for organizing display)
$createGenreCategoriesTable = "CREATE TABLE IF NOT EXISTS genre_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    genre_name VARCHAR(100) NOT NULL UNIQUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;";

// Watchlist table
$createWatchlistTable = "CREATE TABLE IF NOT EXISTS watchlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    anime_series_id INT NOT NULL,
    current_episode INT DEFAULT 1,
    status VARCHAR(20) DEFAULT 'watching',
    user_rating DECIMAL(2,1),
    added_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (anime_series_id) REFERENCES anime_series(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_anime (user_id, anime_series_id)
) ENGINE=InnoDB;";

// Execute all table creation queries
$tables = [
    "Users Table" => $createUserTable,
    "Anime Series Table" => $createAnimeTable,
    "Episodes Table" => $createEpisodesTable,
    "Genre Categories Table" => $createGenreCategoriesTable,
    "Watchlist Table" => $createWatchlistTable
];

echo "<h2>Database Setup</h2>";
foreach ($tables as $name => $query) {
    if ($mysqli->query($query)) {
        echo "<p style='color: green;'>✓ $name created successfully</p>";
    } else {
        echo "<p style='color: red;'>✗ Error creating $name: {$mysqli->error}</p>";
    }
}

$mysqli->close();
echo "<p>Setup complete! Your database tables have been created.</p>";
?>
