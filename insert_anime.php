<?php
// insert_anime.php - Enhanced version with easy episode data entry
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db.php';

// Check database connection
if (!$mysqli || $mysqli->connect_error) {
    die("Database connection failed: " . ($mysqli->connect_error ?? "Unknown error"));
}

echo "<h2>🎬 Anime Data Insertion</h2>";
echo "<hr>";

// =====================================================
// 📝 EASY EPISODE ENTRY - Just add your episodes here!
// =====================================================
// Format: 'Episode Title' => 'Video URL'
// Leave episodes array empty [] if you don't have episodes yet

$sampleAnime = [
    [
        'title' => 'Solo Leveling',
        'description' => 'Humanity fights for survival against giant humanoid Titans in this epic dark fantasy series.',
        'thumbnail' => 'Assets/images/solo.jpg',
        'total_episodes' => 12,
        'status' => 'Completed',
        'genre' => 'Action',
        'release_date' => '2025-04-01',
        'rating' => 9.9,
        'is_featured' => 1,
        'is_new_release' => 0,
        'episodes' => [
            'What Is Your Level?' => '',
            'If I Had One More Chance' => '',
            'It\'s Like a Game' => '',
            'The Weakest Hunter' => '',
            "A Pretty Good Deal" => '',
            "The Real Hunt Begins" => '',
            "Let's See How Far I Can Go" => '',
            "This Is Frustrating" => '',
            "You've Been Hiding Your Skills" => '',
            "What Is This, a Picnic?" => '',
            "A Knight Who Defends an Empty Throne" => '',
            "Arise" => ''
        ]
    ],
    [
        'title' => 'Kuroiwa Medaka!',
        'description' => 'follows Mona Kawai, a popular high school girl, and her attempts to win over Medaka Kuroiwa, a stoic and recently transferred student.',
        'thumbnail' => 'Assets/images/kuroiwa.jpg',
        'total_episodes' => 12,
        'status' => 'Completed',
        'genre' => 'Romance',
        'release_date' => '2025-06-01',
        'rating' => 9.4,
        'is_featured' => 1,
        'is_new_release' => 0,
        'episodes' => [
            'First Meeting' => '',
            'The Confession' => '',
            "Can't Charm Him" => '',
            "In Love with Him" => '',
            "School Festival With Him" => '',
            "Legend with Him" => '',
            "Basketball Girl with Him" => '',
            "Nursing With Him" => '',
            "Lockscreen With Him" => '',
            "Halloween With Him" => '',
            "Best Friend With Him" => '',
            "Theme Park With Him" => '',
            "Alone With Him" => '',
            "First Love With Him" => '',
        ]
    ],
    [
        'title' => 'Ao no Hako',
        'description' => 'Taiki, a devoted badminton player, lives with his crush, basketball star Chinatsu. As he chases both love and athletic dreams, Blue Box delivers a heartfelt blend of romance, sports, and youthful ambition.',
        'thumbnail' => 'Assets/images/bluebox.jpg',
        'total_episodes' => 25,
        'status' => 'Completed',
        'genre' => 'Romance',
        'release_date' => '2024-03-20',
        'rating' => 9,
        'is_featured' => 1,
        'is_new_release' => 0,
        'episodes' => [
            "Chinatsu Senpai" => '',
            "You Have to Go to Nationals" => '',
            "Chii" => '',
            "If He Wins" => '',
            "Aquarium" => '',
            "Wish Me Luck" => '',
            "Can I Have One?" => '',
            "Score!" => '',
            "I'll Be Rooting For You" => '',
            "It's Not a Good Thing" => '',
            "Uncool!" => '',
            "Girls..." => '',
            "I Want a Back-and-Forth Rally" => '',
            "What's the Connection?" => '',
            "August 26" => '',
            "Unfair Woman" => '',
            "Of Course I Want to See It" => '',
            "I Know" => '',
            "I've Got Plans" => '',
            "As One of Her Closest Friends" => '',
            "A Chance to Blossom" => '',
            "Inota!" => '',
            "Spinning" => '',
            "A Roller Coaster" => '',
            "Even So" => '',


        ]
    ],
    [
        'title' => 'I Have a Crush at Work!',
        'description' => 'Yui Mitsuya and Masugu Tateishi are coworkers who genuinely love each other in the office. However, they try to keep their new relationship a secret, but the funny thing is, they can spill their little secret every day at work.',
        'thumbnail' => 'Assets/images/crush.webp',
        'total_episodes' => 12,
        'status' => 'Completed',
        'genre' => 'Romance',
        'release_date' => '2025-03-24',
        'rating' => 9.8,
        'is_featured' => 1,
        'is_new_release' => 1,
        'episodes' => [
            "Don't Let Anyone Find Out" => '',
            "Distinction" => '',
            "Tonight is Special" => '',
            "Hazy Feelings" => '',
            "First Trip" => '',
            "What I Need Now" => '',
            "Anniversary" => '',
            "A Modest Christmas" => '',
            "Our Valentine's Day" => '',
            "True Feelings" => '',
            "Each One's Start" => '',
            "I Have a Crush at Work" => '',
        ]
    ]
];

// Function to add anime to display sections
function addToDisplaySections($mysqli, $animeId, $anime)
{
    $sections = [];

    // All anime go to browse section
    $sections[] = ['section' => 'browse', 'order' => rand(1, 10)];

    // Featured anime go to home slider
    if ($anime['is_featured']) {
        $sections[] = ['section' => 'home_slider', 'order' => rand(1, 5)];
        $sections[] = ['section' => 'featured', 'order' => rand(1, 5)];
    }

    // New releases
    if ($anime['is_new_release']) {
        $sections[] = ['section' => 'new_releases', 'order' => rand(1, 5)];
    }

    $sectionStmt = $mysqli->prepare("INSERT IGNORE INTO display_sections (anime_series_id, section_type, display_order) VALUES (?, ?, ?)");

    if ($sectionStmt) {
        foreach ($sections as $section) {
            $sectionStmt->bind_param("isi", $animeId, $section['section'], $section['order']);
            $sectionStmt->execute();
        }
        $sectionStmt->close();
        return count($sections);
    }
    return 0;
}

// Function to add real episodes from the easy format
function addRealEpisodes($mysqli, $animeId, $episodes)
{
    if (empty($episodes)) {
        return 0;
    }

    $episodeStmt = $mysqli->prepare("INSERT IGNORE INTO episodes (anime_series_id, episode_number, episode_title, video_url) VALUES (?, ?, ?, ?)");

    if ($episodeStmt) {
        $addedEpisodes = 0;
        $episodeNumber = 1;

        foreach ($episodes as $title => $url) {
            $episodeStmt->bind_param("iiss", $animeId, $episodeNumber, $title, $url);

            if ($episodeStmt->execute() && $episodeStmt->affected_rows > 0) {
                $addedEpisodes++;
            }
            $episodeNumber++;
        }
        $episodeStmt->close();
        return $addedEpisodes;
    }
    return 0;
}

// Function to add default episodes for anime without predefined episodes
function addDefaultEpisodes($mysqli, $animeId, $animeTitle, $totalEpisodes)
{
    $episodeStmt = $mysqli->prepare("INSERT IGNORE INTO episodes (anime_series_id, episode_number, episode_title, video_url) VALUES (?, ?, ?, ?)");

    if ($episodeStmt) {
        $addedEpisodes = 0;
        $maxEpisodesToAdd = min($totalEpisodes, 5); // Add max 5 default episodes

        for ($ep = 1; $ep <= $maxEpisodesToAdd; $ep++) {
            $episodeTitle = "Episode $ep";
            $videoUrl = "https://your-video-host.com/anime/" . strtolower(str_replace([' ', '!', ':', "'"], ['_', '', '', ''], $animeTitle)) . "/episode_$ep.mp4";

            $episodeStmt->bind_param("iiss", $animeId, $ep, $episodeTitle, $videoUrl);

            if ($episodeStmt->execute()) {
                $addedEpisodes++;
            }
        }
        $episodeStmt->close();
        return $addedEpisodes;
    }
    return 0;
}

// Start insertion process
echo "<strong>Starting anime insertion...</strong><br><br>";

$stmt = $mysqli->prepare("INSERT IGNORE INTO anime_series (title, description, thumbnail, total_episodes, status, genre, release_date, rating, is_featured, is_new_release) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

if (!$stmt) {
    die("Error preparing statement: " . $mysqli->error);
}

$totalInserted = 0;
$totalSkipped = 0;

foreach ($sampleAnime as $index => $anime) {
    echo "<div style='background: #f5f5f5; padding: 10px; margin: 10px 0; border-left: 4px solid #007cba;'>";
    echo "<strong>" . ($index + 1) . ". {$anime['title']}</strong><br>";

    $stmt->bind_param(
        "sssisssdii",
        $anime['title'],
        $anime['description'],
        $anime['thumbnail'],
        $anime['total_episodes'],
        $anime['status'],
        $anime['genre'],
        $anime['release_date'],
        $anime['rating'],
        $anime['is_featured'],
        $anime['is_new_release']
    );

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $animeId = $mysqli->insert_id;
            echo "✅ <span style='color: green;'>Successfully inserted!</span> (ID: $animeId)<br>";

            // Add to display sections
            $sectionsAdded = addToDisplaySections($mysqli, $animeId, $anime);
            echo "&nbsp;&nbsp;📂 Added to $sectionsAdded display sections<br>";

            // Add episodes - use real episodes if available, otherwise default
            if (isset($anime['episodes']) && !empty($anime['episodes'])) {
                $episodesAdded = addRealEpisodes($mysqli, $animeId, $anime['episodes']);
                echo "&nbsp;&nbsp;🎬 Added $episodesAdded real episodes with custom titles<br>";

                // Show episode details
                $epNum = 1;
                foreach ($anime['episodes'] as $title => $url) {
                    echo "&nbsp;&nbsp;&nbsp;&nbsp;Episode $epNum: \"$title\"<br>";
                    $epNum++;
                }
            } else {
                $episodesAdded = addDefaultEpisodes($mysqli, $animeId, $anime['title'], $anime['total_episodes']);
                echo "&nbsp;&nbsp;🎬 Added $episodesAdded default episodes (no custom episodes provided)<br>";
            }

            $totalInserted++;
        } else {
            echo "⚠️ <span style='color: orange;'>Already exists - skipped</span><br>";
            $totalSkipped++;
        }
    } else {
        echo "❌ <span style='color: red;'>Error:</span> " . $stmt->error . "<br>";
    }

    echo "</div>";
}

$stmt->close();

// Summary
echo "<hr>";
echo "<h3>📊 Insertion Summary</h3>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";
echo "<strong>✅ Successfully inserted:</strong> $totalInserted anime<br>";
echo "<strong>⚠️ Already existed (skipped):</strong> $totalSkipped anime<br>";
echo "<strong>📺 Total anime in database:</strong> ";

// Count total anime
$result = $mysqli->query("SELECT COUNT(*) as total FROM anime_series");
if ($result) {
    $row = $result->fetch_assoc();
    echo $row['total'] . "<br>";
}

// Count total episodes
$result = $mysqli->query("SELECT COUNT(*) as total FROM episodes");
if ($result) {
    $row = $result->fetch_assoc();
    echo "<strong>🎬 Total episodes in database:</strong> " . $row['total'] . "<br>";
}

echo "</div>";

// Show current anime list with episodes
echo "<br><h3>📋 Current Anime in Database</h3>";
$result = $mysqli->query("
    SELECT 
        a.id, 
        a.title, 
        a.status, 
        a.total_episodes, 
        a.rating, 
        a.is_featured, 
        a.is_new_release,
        COUNT(e.id) as actual_episodes
    FROM anime_series a 
    LEFT JOIN episodes e ON a.id = e.anime_series_id 
    GROUP BY a.id 
    ORDER BY a.id DESC
");

if ($result && $result->num_rows > 0) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background: #f0f0f0;'>";
    echo "<th style='padding: 8px;'>ID</th>";
    echo "<th style='padding: 8px;'>Title</th>";
    echo "<th style='padding: 8px;'>Status</th>";
    echo "<th style='padding: 8px;'>Episodes (Total/Added)</th>";
    echo "<th style='padding: 8px;'>Rating</th>";
    echo "<th style='padding: 8px;'>Featured</th>";
    echo "<th style='padding: 8px;'>New Release</th>";
    echo "</tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td style='padding: 8px;'>{$row['id']}</td>";
        echo "<td style='padding: 8px;'><strong>{$row['title']}</strong></td>";
        echo "<td style='padding: 8px;'>{$row['status']}</td>";
        echo "<td style='padding: 8px;'>{$row['total_episodes']} / {$row['actual_episodes']}</td>";
        echo "<td style='padding: 8px;'>{$row['rating']}/10</td>";
        echo "<td style='padding: 8px;'>" . ($row['is_featured'] ? '⭐ Yes' : 'No') . "</td>";
        echo "<td style='padding: 8px;'>" . ($row['is_new_release'] ? '🆕 Yes' : 'No') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No anime found in database.</p>";
}

$mysqli->close();

echo "<br><div style='background: #d4edda; padding: 15px; border-radius: 5px; margin-top: 20px;'>";
echo "<strong>🎉 Process completed!</strong><br>";
echo "Your anime data and episodes have been successfully inserted into the database.<br>";
echo "You can run this script multiple times - it will skip duplicates automatically.<br><br>";
echo "<strong>💡 How to add episodes easily:</strong><br>";
echo "1. In the anime array, add episodes like: 'Episode Title' => 'Video URL'<br>";
echo "2. Leave episodes array empty [] if you don't have episodes yet<br>";
echo "3. Re-run this script to add new episodes<br><br>";
echo "<strong>📝 Episode Format Example:</strong><br>";
echo "<code>'Episode Title Here' => 'https://your-video-url.com/video.mp4'</code>";
echo "</div>";
