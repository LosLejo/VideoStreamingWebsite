<?php
session_start();
include 'db.php';

// Get anime series (not individual episodes)
$result = $mysqli->query("SELECT * FROM anime ORDER BY id");
?>

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

    <section class="home" id="home">
        <div class="swiper home-slider">
            <div class="swiper-wrapper">

                <?php while ($anime = $result->fetch_assoc()): ?>
                    <div class="swiper-slide">
                        <div class="box"
                            style="background: url('<?php echo htmlspecialchars($anime['thumbnail']); ?>') no-repeat center/cover;">
                            <div class="content">
                                <h3><?php echo htmlspecialchars($anime['title']); ?></h3>
                                <p><?php echo htmlspecialchars($anime['description']); ?></p>
                                <!-- Updated: Link to anime series, not specific episode -->
                                <a href="watch.php?anime_id=<?php echo $anime['id']; ?>" class="btn">Watch</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>

            </div>
        </div>
    </section>

    <!-- Rest of your sections remain the same -->
    <section class="anime" id="browse">
        <h1 class="heading">Browse <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
        <div class="swiper anime-slider">
            <div class="swiper-wrapper">

            </div>
        </div>
    </section>

    <section class="anime" id="new-releases">
        <h1 class="heading">New Releases <?php echo htmlspecialchars(strtolower($_SESSION['user_email'])); ?></h1>
        <div class="swiper new-release-slider">
            <div class="swiper-wrapper">
                <!-- Your existing new releases section content -->
            </div>
        </div>
    </section>

    <?php include 'Assets/HTML/footer.html' ?>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="Assets/js/main.js"></script>
</body>

</html>