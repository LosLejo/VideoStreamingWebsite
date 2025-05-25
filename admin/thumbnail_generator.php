<?php
// Combined thumbnail generator script
include '../db.php';  // Go up one directory to find db.php

echo "<h2>Thumbnail Generator</h2>";
echo "Starting thumbnail generation...<br>";

// thumbnail_generator.php - Script to auto-generate thumbnails from videos

function generateThumbnail($videoPath, $thumbnailPath, $timePosition = 30)
{
    // Create thumbnails directory if it doesn't exist
    $thumbnailDir = dirname($thumbnailPath);
    if (!is_dir($thumbnailDir)) {
        mkdir($thumbnailDir, 0755, true);
    }

    // FFmpeg command to extract thumbnail at specified time
    $command = "ffmpeg -i " . escapeshellarg($videoPath) .
        " -ss " . $timePosition .
        " -vframes 1 -vf scale=320:180 " .
        escapeshellarg($thumbnailPath) .
        " -y 2>&1";

    $output = shell_exec($command);

    return file_exists($thumbnailPath);
}

function generateThumbnailsForAllEpisodes()
{
    global $mysqli;

    // Get all episodes that don't have thumbnails
    $query = "SELECT id, anime_id, episode_number, video_url, thumbnail 
              FROM episodes 
              WHERE thumbnail IS NULL OR thumbnail = ''";

    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        while ($episode = $result->fetch_assoc()) {
            $videoPath = $episode['video_url'];

            // Create thumbnail path - FIXED: removed extra space
            $thumbnailDir = "../assets/thumbnails/anime_" . $episode['anime_id'];
            $thumbnailFilename = "episode_" . $episode['episode_number'] . ".jpg";
            $thumbnailPath = $thumbnailDir . "/" . $thumbnailFilename;

            // Generate thumbnail
            if (generateThumbnail($videoPath, $thumbnailPath)) {
                // Update database with thumbnail path
                $updateQuery = "UPDATE episodes SET thumbnail = ? WHERE id = ?";
                $stmt = $mysqli->prepare($updateQuery);
                $stmt->bind_param("si", $thumbnailPath, $episode['id']);
                $stmt->execute();

                echo "Generated thumbnail for Episode " . $episode['episode_number'] . "<br>";
            } else {
                echo "Failed to generate thumbnail for Episode " . $episode['episode_number'] . "<br>";
            }
        }
    } else {
        echo "No episodes found that need thumbnails.<br>";
    }
}

function getVideoDuration($videoPath)
{
    // FIXED: removed extra space in CSV format
    $command = "ffprobe -v quiet -show_entries format=duration -of csv=\"p=0\" " . escapeshellarg($videoPath);
    $duration = shell_exec($command);
    return floatval(trim($duration));
}

function generateMultipleThumbnails($videoPath, $baseDir, $episodeId, $count = 3)
{
    $thumbnails = [];
    $videoDuration = getVideoDuration($videoPath);

    for ($i = 1; $i <= $count; $i++) {
        $timePosition = ($videoDuration / ($count + 1)) * $i;
        $thumbnailPath = $baseDir . "/thumb_" . $i . ".jpg";

        if (generateThumbnail($videoPath, $thumbnailPath, $timePosition)) {
            $thumbnails[] = $thumbnailPath;
        }
    }

    // Return the middle thumbnail as the main one
    return isset($thumbnails[1]) ? $thumbnails[1] : $thumbnails[0];
}

function processNewEpisode($videoPath, $animeId, $episodeNumber)
{
    global $mysqli;

    // Generate thumbnail - FIXED: removed extra space
    $thumbnailDir = "../assets/thumbnails/anime_" . $animeId;
    $thumbnailPath = $thumbnailDir . "/episode_" . $episodeNumber . ".jpg";

    if (generateThumbnail($videoPath, $thumbnailPath)) {
        // Insert episode with thumbnail
        $query = "INSERT INTO episodes (anime_id, episode_number, video_url, thumbnail) 
                  VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("iiss", $animeId, $episodeNumber, $videoPath, $thumbnailPath);
        return $stmt->execute();
    }

    return false;
}

// Run the thumbnail generation
generateThumbnailsForAllEpisodes();

echo "<br>Thumbnail generation complete!";
