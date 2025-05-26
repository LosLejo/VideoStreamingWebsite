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
    <link rel="stylesheet" href="Assets/css/style.css">
    <link rel="stylesheet" href="Assets/css/genres.css">
    <style>
        /* Reset and base styles to ensure consistency */
        * {
            box-sizing: border-box;
        }

        html {
            background-color: black;
        }

        body {
            background-color: #141414 !important;
            color: #ffffff !important;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        /* Genre section styles */
        .genre-section {
            margin: 40px 20px;
            background: transparent !important;
        }

        .heading {
            font-size: 28px;
            margin-bottom: 20px;
            color: #ffffff !important;
            font-weight: bold;
            text-transform: capitalize;
        }

        .heading span {
            font-size: 16px !important;
            color: #b3b3b3 !important;
            font-weight: normal;
        }

        hr {
            border: none;
            height: 1px;
            background-color: #333333;
            margin-bottom: 20px;
        }

        /* Swiper container */
        .swiper {
            width: 100%;
            padding: 0 !important;
            overflow: visible;
        }

        .swiper-wrapper {
            display: flex;
            align-items: stretch;
        }

        /* Swiper slide styles */
        .swiper-slide {
            width: auto !important;
            margin-right: 20px;
            background: transparent !important;
        }

        /* Anime card styles */
        .box {
            width: 200px;
            height: 300px;
            background-size: cover !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
            border-radius: 10px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .box:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
        }

        .box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.7) 100%);
            z-index: 1;
        }

        /* No image placeholder */
        .no-image-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            background: linear-gradient(135deg, #333333, #555555);
            color: #cccccc;
            font-size: 14px;
            text-align: center;
            border-radius: 10px;
        }

        /* Content styles */
        .content {
            padding: 15px 0;
            max-width: 200px;
            background: transparent !important;
        }

        .content h3 {
            margin: 10px 0 8px 0;
            font-size: 16px;
            font-weight: bold;
            color: #ffffff !important;
            line-height: 1.3;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .content p {
            font-size: 13px;
            color: #b3b3b3 !important;
            margin: 8px 0;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 60px;
        }

        .rating {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px 0;
            font-size: 12px;
        }

        .stars {
            color: #ffd700 !important;
            font-weight: bold;
        }

        .episodes {
            color: #b3b3b3 !important;
        }

        .btn {
            background: yellow !important;
            color: black !important;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 13px;
            font-weight: bold;
            display: inline-block;
            transition: background-color 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background: white !important;
            text-decoration: none;
        }

        /* Navigation buttons */
        .swiper-button-next,
        .swiper-button-prev {
            color: #ffffff !important;
            background: rgba(0, 0, 0, 0.5);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-top: -20px;
        }

        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 16px;
            font-weight: bold;
        }

        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            background: rgba(0, 0, 0, 0.8);
        }

        /* Debug info */
        .debug-info {
            position: fixed;
            top: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.9);
            color: #ffffff;
            padding: 10px;
            font-size: 12px;
            z-index: 9999;
            border-radius: 5px;
            border: 1px solid #333;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .genre-section {
                margin: 20px 10px;
            }

            .heading {
                font-size: 24px;
                color: yellow !important;
            }

            .box {
                width: 160px;
                height: 240px;
            }

            .content {
                max-width: 160px;
            }
        }

        @media (max-width: 480px) {
            .box {
                width: 140px;
                height: 210px;
            }

            .content {
                max-width: 140px;
            }

            .heading {
                font-size: 20px;
                color: yellow !important;
            }
        }
    </style>
</head>

<body>
    <?php include 'Assets/HTML/home-header.php' ?>

    <?php foreach ($genreAnime as $genreName => $animeList): ?>
        <?php if (count($animeList) > 0): ?>
            <section class="genre-section" id="<?php echo strtolower(str_replace(' ', '-', $genreName)); ?>">
                <h1 class="heading">
                    <?php echo htmlspecialchars($genreName); ?>
                    <span>(<?php echo count($animeList); ?> anime)</span>
                </h1>
                <hr />
                <div class="swiper anime-slider-<?php echo strtolower(str_replace(' ', '-', $genreName)); ?>">
                    <div class="swiper-wrapper">
                        <?php foreach ($animeList as $anime): ?>
                            <div class="swiper-slide">
                                <div class="box" <?php if (!empty($anime['thumbnail'])): ?>
                                    style="background-image: url('<?php echo htmlspecialchars($anime['thumbnail']); ?>');"
                                    <?php endif; ?>>
                                    <?php if (empty($anime['thumbnail'])): ?>
                                        <div class="no-image-placeholder">
                                            <i class="fas fa-image" style="font-size: 24px; margin-bottom: 8px;"></i><br>
                                            No Image Available
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="content">
                                    <h3><?php echo htmlspecialchars($anime['title']); ?></h3>
                                    <p><?php
                                        $description = $anime['description'] ?? 'No description available';
                                        echo htmlspecialchars(strlen($description) > 120 ? substr($description, 0, 120) . '...' : $description);
                                        ?></p>
                                    <div class="rating">
                                        <span class="stars">★ <?php echo number_format($anime['rating'], 1); ?></span>
                                        <span class="episodes"><?php echo $anime['total_episodes']; ?> Episodes</span>
                                    </div>
                                    <a href="watch.php?series_id=<?php echo $anime['id']; ?>" class="btn">Watch Now</a>
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
            // Add a small delay to ensure DOM is fully loaded
            setTimeout(function() {
                <?php foreach ($genreAnime as $genreName => $animeList): ?>
                    <?php if (count($animeList) > 0): ?>
                        try {
                            new Swiper(
                                '.anime-slider-<?php echo strtolower(str_replace(' ', '-', $genreName)); ?>', {
                                    slidesPerView: 'auto',
                                    spaceBetween: 20,
                                    loop: false,
                                    grabCursor: true,
                                    navigation: {
                                        nextEl: '.anime-slider-<?php echo strtolower(str_replace(' ', '-', $genreName)); ?> .swiper-button-next',
                                        prevEl: '.anime-slider-<?php echo strtolower(str_replace(' ', '-', $genreName)); ?> .swiper-button-prev',
                                    },
                                    breakpoints: {
                                        320: {
                                            slidesPerView: 1.5,
                                            spaceBetween: 10
                                        },
                                        480: {
                                            slidesPerView: 2.5,
                                            spaceBetween: 15
                                        },
                                        768: {
                                            slidesPerView: 3.5,
                                            spaceBetween: 20
                                        },
                                        1024: {
                                            slidesPerView: 4.5,
                                            spaceBetween: 20
                                        },
                                        1200: {
                                            slidesPerView: 5.5,
                                            spaceBetween: 20
                                        },
                                        1400: {
                                            slidesPerView: 6.5,
                                            spaceBetween: 20
                                        }
                                    }
                                });
                        } catch (error) {
                            console.error('Error initializing swiper for <?php echo $genreName; ?>:', error);
                        }
                    <?php endif; ?>
                <?php endforeach; ?>
            }, 100);
        });
    </script>

    <!-- Debug info (remove in production) -->
    <div class="debug-info">
        Total anime found: <?php echo $totalAnimeFound; ?><br>
        Genres loaded: <?php echo count($genreAnime); ?>
    </div>
</body>

</html>