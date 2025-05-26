<?php
session_start();
include 'db.php';

// Get all genres and their anime with better error handling
$genresQuery = "SELECT * FROM genre_categories ORDER BY display_order";
$genresResult = $mysqli->query($genresQuery);

if (!$genresResult) {
    die("Error fetching genres: " . $mysqli->error);
}

$genreAnime = [];
$totalAnimeFound = 0;

while ($genre = $genresResult->fetch_assoc()) {
    $animeQuery = "SELECT DISTINCT a.* FROM anime_series a 
                  JOIN anime_genres ag ON a.id = ag.anime_series_id 
                  JOIN genre_categories gc ON ag.genre_category_id = gc.id 
                  WHERE gc.id = ? 
                  ORDER BY a.rating DESC";

    $stmt = $mysqli->prepare($animeQuery);
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }

    $stmt->bind_param("i", $genre['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    $animeList = [];
    while ($anime = $result->fetch_assoc()) {
        $animeList[] = $anime;
    }

    $genreAnime[$genre['name']] = $animeList;
    $totalAnimeFound += count($animeList);
}

// If no anime found at all, show a helpful message
if ($totalAnimeFound === 0) {
    echo "<div style='text-align: center; padding: 50px;'>";
    echo "<h2>No anime found!</h2>";
    echo "<p>It looks like your anime aren't properly linked to genres yet.</p>";
    echo "<p><a href='populate_anime_genres.php'>Click here to populate anime-genre relationships</a></p>";
    echo "</div>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StrikeFlix - Genres</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <link rel="stylesheet" href="Assets/css/genres.css">
    <link rel="stylesheet" href="Assets/css/style.css">
    <style>
    /* Fallback styles in case your CSS file has issues */
    .swiper-slide {
        width: auto !important;
        margin-right: 20px;
    }

    .box {
        width: 200px;
        height: 300px;
        background-size: cover !important;
        background-position: center !important;
        border-radius: 10px;
        position: relative;
    }

    .content {
        padding: 15px 0;
        max-width: 200px;
    }

    .content h3 {
        margin: 10px 0 5px 0;
        font-size: 16px;
        font-weight: bold;
    }

    .content p {
        font-size: 12px;
        color: #666;
        margin: 5px 0;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .rating {
        display: flex;
        justify-content: space-between;
        margin: 10px 0;
        font-size: 12px;
    }

    .btn {
        background: #e50914;
        color: white;
        padding: 8px 16px;
        text-decoration: none;
        border-radius: 5px;
        font-size: 12px;
    }

    .genre-section {
        margin: 30px 0;
    }

    .heading {
        font-size: 24px;
        margin-bottom: 20px;
        color: #333;
    }
    </style>
</head>

<body>
    <?php include 'Assets/HTML/home-header.php' ?>

    <?php foreach ($genreAnime as $genreName => $animeList): ?>
    <?php if (count($animeList) > 0): ?>
    <section class="genre-section <?php echo strtolower($genreName); ?>" id="<?php echo strtolower($genreName); ?>">
        <h1 class="heading"><?php echo $genreName; ?> <span
                style="font-size: 14px; color: #666;">(<?php echo count($animeList); ?> anime)</span></h1>
        <hr />
        <div class="swiper anime-slider-<?php echo strtolower($genreName); ?>">
            <div class="swiper-wrapper">
                <?php foreach ($animeList as $anime): ?>
                <div class="swiper-slide">
                    <div class="box"
                        style="background: url('<?php echo htmlspecialchars($anime['thumbnail']); ?>') no-repeat center/cover;">
                        <?php if (empty($anime['thumbnail'])): ?>
                        <div
                            style="display: flex; align-items: center; justify-content: center; height: 100%; background: #f0f0f0; color: #666;">
                            No Image
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="content">
                        <h3><?php echo htmlspecialchars($anime['title']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($anime['description'] ?? 'No description available', 0, 100)); ?><?php echo strlen($anime['description'] ?? '') > 100 ? '...' : ''; ?>
                        </p>
                        <div class="rating">
                            <span class="stars">★ <?php echo $anime['rating']; ?></span>
                            <span class="episodes"><?php echo $anime['total_episodes']; ?> Episodes</span>
                        </div>
                        <a href="watch.php?series_id=<?php echo $anime['id']; ?>" class="btn">Watch</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>
    <?php endif; ?>
    <?php endforeach; ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
    // Initialize all swiper instances
    document.addEventListener('DOMContentLoaded', function() {
        <?php foreach ($genreAnime as $genreName => $animeList): ?>
        <?php if (count($animeList) > 0): ?>
        new Swiper('.anime-slider-<?php echo strtolower($genreName); ?>', {
            slidesPerView: 'auto',
            spaceBetween: 20,
            loop: false,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 10
                },
                480: {
                    slidesPerView: 2,
                    spaceBetween: 15
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 20
                },
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 20
                },
                1200: {
                    slidesPerView: 5,
                    spaceBetween: 20
                }
            }
        });
        <?php endif; ?>
        <?php endforeach; ?>
    });
    </script>

    <!-- Debug info (remove in production) -->
    <div
        style="position: fixed; top: 10px; right: 10px; background: rgba(0,0,0,0.8); color: white; padding: 10px; font-size: 12px; z-index: 9999;">
        Total anime found: <?php echo $totalAnimeFound; ?>
    </div>
</body>

</html>