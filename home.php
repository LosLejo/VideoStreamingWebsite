

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StrikeFlix</title>
    <link rel="icon" type="image/svg+xml" href="assets/bolt-solid.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <link rel="stylesheet" href="Assets/css/style.css">
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
        <h1 class="heading">Browse <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
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
</body>

</html>