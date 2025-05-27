<?php
session_start();
include 'db.php';

// Get featured anime for home slider
$homeSliderQuery = "SELECT DISTINCT a.* FROM anime_series a 
                   JOIN display_sections ds ON a.id = ds.anime_series_id 
                   WHERE ds.section_type = 'home_slider' AND ds.is_active = 1 
                   ORDER BY ds.display_order";
$homeSliderResult = $mysqli->query($homeSliderQuery);

// Get browse section anime
$browseQuery = "SELECT DISTINCT a.* FROM anime_series a 
               JOIN display_sections ds ON a.id = ds.anime_series_id 
               WHERE ds.section_type = 'browse' AND ds.is_active = 1 
               ORDER BY ds.display_order";
$browseResult = $mysqli->query($browseQuery);

// Get new releases
$newReleasesQuery = "SELECT DISTINCT a.* FROM anime_series a 
                    JOIN display_sections ds ON a.id = ds.anime_series_id 
                    WHERE ds.section_type = 'new_releases' AND ds.is_active = 1 
                    ORDER BY ds.display_order";
$newReleasesResult = $mysqli->query($newReleasesQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>StrikeFlix</title>
    <link rel="icon" type="image/svg+xml" href="assets/bolt-solid.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <link rel="stylesheet" href="Assets/css/style.css">

    <style>
        .add-watchlist-btn {
            background: #222 !important;
            color: yellow !important;
            border: 0.2rem solid yellow;
            font-weight: bold;
            transition: background 0.18s, color 0.18s;
        }

        .add-watchlist-btn:hover,
        .add-watchlist-btn.added {
            background: yellow !important;
            color: #181818 !important;
            border-color: #fff700;
        }

        .add-watchlist-btn.added {
            cursor: default;
        }
    </style>

</head>

<body>
    <?php include 'Assets/HTML/home-header.php' ?>

    <!-- HOME SLIDER SECTION -->
    <section class="home" id="home">
        <div class="swiper home-slider">
            <div class="swiper-wrapper">
                <?php while ($anime = $homeSliderResult->fetch_assoc()): ?>
                    <div class="swiper-slide">
                        <div class="box"
                            style="background: url('<?php echo htmlspecialchars($anime['thumbnail']); ?>') no-repeat center/cover;">
                            <div class="content">
                                <h3><?php echo htmlspecialchars($anime['title']); ?></h3>
                                <p><?php echo htmlspecialchars($anime['description']); ?></p>
                                <a href="watch.php?series_id=<?php echo $anime['id']; ?>" class="btn">Watch</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- BROWSE SECTION -->
    <section class="anime" id="browse">
        <h1 class="heading">Browse</h1>
        <div class="swiper anime-slider">
            <div class="swiper-wrapper">
                <?php while ($anime = $browseResult->fetch_assoc()): ?>
                    <div class="swiper-slide">
                        <div class="anime-card">
                            <div class="thumbnail"
                                style="background-image: url('<?php echo htmlspecialchars($anime['thumbnail']); ?>');">
                            </div>
                            <div class="card-content">
                                <h3><?php echo htmlspecialchars($anime['title']); ?></h3>
                                <p><?php echo htmlspecialchars($anime['description']); ?></p>
                                <a href="watch.php?series_id=<?php echo $anime['id']; ?>" class="btn">Watch</a>
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <button class="btn add-watchlist-btn" data-anime-id="<?php echo $anime['id']; ?>">
                                        <i class="fa fa-plus"></i> Add to Watchlist
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- NEW RELEASES SECTION -->
    <section class="anime" id="new-releases">
        <h1 class="heading">New Releases</h1>
        <div class="swiper new-release-slider">
            <div class="swiper-wrapper">
                <?php while ($anime = $newReleasesResult->fetch_assoc()): ?>
                    <div class="swiper-slide">
                        <div class="anime-card">
                            <div class="thumbnail"
                                style="background-image: url('<?php echo htmlspecialchars($anime['thumbnail']); ?>');">
                            </div>
                            <div class="card-content">
                                <h3><?php echo htmlspecialchars($anime['title']); ?></h3>
                                <p><?php echo htmlspecialchars($anime['description']); ?></p>
                                <span class="new-badge">NEW</span>
                                <a href="watch.php?series_id=<?php echo $anime['id']; ?>" class="btn">Watch</a>
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <button class="btn add-watchlist-btn" data-anime-id="<?php echo $anime['id']; ?>">
                                        <i class="fa fa-plus"></i> Add to Watchlist
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <?php include 'Assets/HTML/footer.html' ?>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="Assets/js/main.js"></script>
    <script>
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
                        });
                });
            });
        });
    </script>
</body>

</html>