<?php
require_once 'db.php';

// This script will help you populate the anime_genres table
// You'll need to manually assign genres to your anime

// First, let's see what we have
echo "<h2>Current Data</h2>";

// Get all anime
$animeResult = $mysqli->query("SELECT id, title, genre FROM anime_series");
echo "<h3>Anime Series:</h3>";
while ($anime = $animeResult->fetch_assoc()) {
    echo "<p>ID: {$anime['id']} - {$anime['title']} - Genre: {$anime['genre']}</p>";
}

// Get all genre categories
$genreResult = $mysqli->query("SELECT id, name FROM genre_categories");
echo "<h3>Genre Categories:</h3>";
$genres = [];
while ($genre = $genreResult->fetch_assoc()) {
    $genres[$genre['name']] = $genre['id'];
    echo "<p>ID: {$genre['id']} - {$genre['name']}</p>";
}

echo "<hr>";
echo "<h2>Auto-Assigning Genres</h2>";

// Reset the anime query
$animeResult = $mysqli->query("SELECT id, title, genre FROM anime_series");

while ($anime = $animeResult->fetch_assoc()) {
    $animeId = $anime['id'];
    $animeGenre = $anime['genre']; // This is the genre field from anime_series table

    echo "<p>Processing: {$anime['title']} (Genre: {$animeGenre})</p>";

    // Try to match the anime's genre with our genre categories
    $matchedGenreId = null;

    if ($animeGenre) {
        // Check if the genre exists in our categories (case-insensitive)
        foreach ($genres as $categoryName => $categoryId) {
            if (stripos($animeGenre, $categoryName) !== false || stripos($categoryName, $animeGenre) !== false) {
                $matchedGenreId = $categoryId;
                break;
            }
        }
    }

    // If no match found, assign to 'Action' as default (you can change this)
    if (!$matchedGenreId && isset($genres['Action'])) {
        $matchedGenreId = $genres['Action'];
        echo " → No match found, assigning to Action<br>";
    }

    if ($matchedGenreId) {
        // Insert the relationship (ignore if already exists)
        $stmt = $mysqli->prepare("INSERT IGNORE INTO anime_genres (anime_series_id, genre_category_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $animeId, $matchedGenreId);

        if ($stmt->execute()) {
            echo " → Successfully linked to genre ID: {$matchedGenreId}<br>";
        } else {
            echo " → Error: " . $stmt->error . "<br>";
        }
    } else {
        echo " → Could not find matching genre<br>";
    }
}

echo "<hr>";
echo "<h2>Manual Assignment Examples</h2>";
echo "<p>If the auto-assignment didn't work well, you can manually assign genres like this:</p>";

// Example manual assignments (uncomment and modify as needed)
/*
$manualAssignments = [
    1 => [1, 2], // Anime ID 1 gets genres 1 and 2
    2 => [2, 3], // Anime ID 2 gets genres 2 and 3
    // Add more as needed
];

foreach ($manualAssignments as $animeId => $genreIds) {
    foreach ($genreIds as $genreId) {
        $stmt = $mysqli->prepare("INSERT IGNORE INTO anime_genres (anime_series_id, genre_category_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $animeId, $genreId);
        $stmt->execute();
        echo "<p>Assigned anime {$animeId} to genre {$genreId}</p>";
    }
}
*/

echo "<h2>Final Check</h2>";
$relationCount = $mysqli->query("SELECT COUNT(*) as count FROM anime_genres")->fetch_assoc();
echo "<p>Total anime-genre relationships: " . $relationCount['count'] . "</p>";

if ($relationCount['count'] > 0) {
    echo "<p style='color: green;'>✓ Great! Now try your genres.php page again.</p>";
} else {
    echo "<p style='color: red;'>✗ Still no relationships. You may need to manually assign them.</p>";
}

$mysqli->close();