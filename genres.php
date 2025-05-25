<?php
session_start();
include 'db.php';

// Get all genres and their anime
$genresQuery = "SELECT * FROM genre_categories ORDER BY display_order";
$genresResult = $mysqli->query($genresQuery);

$genreAnime = [];
while ($genre = $genresResult->fetch_assoc()) {
    $animeQuery = "SELECT DISTINCT a.* FROM anime_series a 
                  JOIN anime_genres ag ON a.id = ag.anime_series_id 
                  JOIN genre_categories gc ON ag.genre_category_id = gc.id 
                  WHERE gc.id = ? 
                  ORDER BY a.rating DESC";

    $stmt = $mysqli->prepare($animeQuery);
    $stmt->bind_param("i", $genre['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    $animeList = [];
    while ($anime = $result->fetch_assoc()) {
        $animeList[] = $anime;
    }

    $genreAnime[$genre['name']] = $animeList;
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
</head>

<body>
    <?php include 'Assets/HTML/home-header.php' ?>

    <?php foreach ($genreAnime as $genreName => $animeList): ?>
    <section class="<?php echo strtolower($genreName); ?>" id="<?php echo strtolower($genreName); ?>">
        <h1 class="heading"><?php echo $genreName; ?></h1>
        <hr />
        <div class="swiper anime-slider-<?php echo strtolower($genreName); ?>">
            <div class="swiper-wrapper">
                <?php foreach ($animeList as $anime): ?>
                <div class="swiper-slide">
                    <div class="box"
                        style="background: url('<?php echo htmlspecialchars($anime['thumbnail']); ?>') no-repeat center/cover;">
                    </div>
                    <div class="content">
                        <h3><?php echo htmlspecialchars($anime['title']); ?></h3>
                        <p><?php echo htmlspecialchars($anime['description']); ?></p>
                        <div class="rating">
                            <span class="stars">★ <?php echo $anime['rating']; ?></span>
                            <span class="episodes"><?php echo $anime['total_episodes']; ?> Episodes</span>
                        </div>
                        <a href="watch.php?series_id=<?php echo $anime['id']; ?>" class="btn">Watch</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endforeach; ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="Assets/js/genres.js"></script>
</body>

</html>