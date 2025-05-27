<?php
session_start();
include 'db.php';

// 1. Clear existing anime_genres table
$mysqli->query("DELETE FROM anime_genres");

// 2. Get all genres into a map
$genreMap = [];
$genreRes = $mysqli->query("SELECT id, name FROM genre_categories");
while ($row = $genreRes->fetch_assoc()) {
    $genreMap[strtolower(trim($row['name']))] = $row['id'];
}

// 3. For each anime, parse the genre field and insert links
$animeResult = $mysqli->query("SELECT id, genre FROM anime_series");
$count = 0;
while ($anime = $animeResult->fetch_assoc()) {
    $animeId = $anime['id'];
    $genreField = $anime['genre'];
    if (!$genreField) continue;
    $genres = array_map('trim', explode(',', $genreField));
    foreach ($genres as $gname) {
        $key = strtolower($gname);
        if (isset($genreMap[$key])) {
            $genreId = $genreMap[$key];
            $stmt = $mysqli->prepare("INSERT IGNORE INTO anime_genres (anime_series_id, genre_category_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $animeId, $genreId);
            $stmt->execute();
            $count++;
        }
    }
}

echo "<h2>Anime genres repopulated!</h2>";
echo "<p>Total anime-genre links inserted: $count</p>";
echo "<a href='insert_anime.php'>Back to Inserting Anime</a>";

$mysqli->close();
