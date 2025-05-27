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
    echo "<div class='error-message-container'>";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>StrikeFlix - Genres</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <link rel="stylesheet" href="Assets/css/style.css">
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
                            <i class="fas fa-image"></i>
                            <span>No Image Available</span>
                        </div>
                        <?php endif; ?>

                        <!-- THIS WAS MISSING - The content section -->
                        <div class="content">
                            <h3><?php echo htmlspecialchars($anime['title']); ?></h3>
                            <p><?php echo htmlspecialchars(substr($anime['description'] ?? 'No description available.', 0, 100)) . '...'; ?>
                            </p>
                            <div class="rating">
                                <span class="stars">★ <?php echo number_format($anime['rating'], 1); ?></span>
                                <span class="episodes"><?php echo $anime['total_episodes']; ?> Episodes</span>
                            </div>
                            <a href="watch.php?series_id=<?php echo $anime['id']; ?>" class="btn">Watch</a>
                            <button class="btn add-watchlist-btn" data-anime-id="<?php echo $anime['id']; ?>">
                                <i class="fa fa-plus"></i> Add to Watchlist
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <!-- Navigation buttons -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>
    <?php endif; ?>
    <?php endforeach; ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
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

    // Watchlist functionality
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.add-watchlist-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const animeId = this.getAttribute('data-anime-id');
                const button = this;
                button.disabled = true;

                fetch('add_to_watchlist.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'anime_id=' + encodeURIComponent(animeId)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            button.innerHTML = '<i class="fa fa-check"></i> Added!';
                            button.classList.add('added');
                        } else {
                            button.innerHTML = '<i class="fa fa-check"></i> ' + (data
                                .message || 'Already in Watchlist');
                        }
                    })
                    .catch(() => {
                        button.innerHTML = 'Error';
                    })
                    .finally(() => {
                        button.disabled = false;
                    });
            });
        });
    });
    </script>

    <!-- Debug info (remove in production) -->
    <div class="debug-info">
        Total anime found: <?php echo $totalAnimeFound; ?><br>
        Genres loaded: <?php echo count($genreAnime); ?>
    </div>

    <?php include 'Assets/HTML/footer.html' ?>
</body>

</html>