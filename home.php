<?php
session_start();
include 'db.php';

// Fixed query - added ORDER BY id to display anime in order by their ID
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
                            style="background: url('<?php echo $anime['thumbnail']; ?>') no-repeat center/cover;">
                            <div class="content">
                                <h3><?php echo htmlspecialchars($anime['title']); ?></h3>
                                <p><?php echo htmlspecialchars($anime['description']); ?></p>
                                <a href="watch.php?id=<?php echo $anime['id']; ?>" class="btn">Watch</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>

                <!-- Static slides (if you want to keep them) -->
                <div class="swiper-slide">
                    <div class="box second" style="background: url('Assets/images/death.jpg') no-repeat center/cover;">
                        <div class="content">
                            <h3>Death Note</h3>
                            <p>
                                When a Japanese high schooler comes into possession of a mystical<br>
                                notebook, he finds he has the power to kill anybody whose name he<br>
                                enters in it.
                            </p>
                            <a href="watch" class="btn">Watch</a>
                        </div>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="box second" style="background: url('Assets/images/boku.jpg') no-repeat center/cover;">
                        <div class="content">
                            <h3>Boku no Hero</h3>
                            <p>
                                When a powerless teen in a superhuman society inherits the abilities of the world's
                                greatest hero, he must train to become the symbol of peace and survive a high
                                school where danger is part of the curriculum
                            </p>
                            <a href="watch" class="btn">Watch</a>
                        </div>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="box second" style="background: url('Assets/images/solo.jpg') no-repeat center/cover;">
                        <div class="content">
                            <h3>Solo Leveling</h3>
                            <p>
                                When the world is invaded by deadly dungeons, a weak hunter gains the power to
                                level up without limit turning from the weakest of all into humanity's ultimate
                                weapon
                            </p>
                            <a href="watch" class="btn">Watch</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Rest of your sections remain the same -->
    <section class="anime" id="browse">
        <h1 class="heading">Browse <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
        <div class="swiper anime-slider">
            <div class="swiper-wrapper">
                <!-- Your existing browse section content -->
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