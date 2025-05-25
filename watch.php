<?php
session_start();
include 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid anime ID.");
}

$id = (int)$_GET['id'];

$stmt = $mysqli->prepare("SELECT * FROM anime WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Anime not found.");
}

$anime = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($anime['title']); ?> - StrikeFlix</title>
    <link rel="icon" type="image/svg+xml" href="assets/bolt-solid.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <link rel="stylesheet" href="Assets/css/watch.css">
</head>

<body>
    <div class="containers">
        <header>
            <?php include 'Assets/HTML/home-header.php' ?>
        </header>

        <main>
            <section class="video-section">
                <div class="breadcrumbs">Home > Watching <?php echo htmlspecialchars($anime['title']); ?></div>

                <div id="videoWrapper">

                    <div class="video-player">
                        <div class="video-player">
                            <video controls width="100%" height="auto">
                                <source src="<?php echo htmlspecialchars($anime['video_url']); ?>" type="video/mp4" />
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    </div>

                    <div class="video-controls">
                        <button>Next <i class="fa-solid fa-forward"></i></button>
                        <button onclick="toggleExpand()">Expand <i class="fa-solid fa-expand"></i></button>
                    </div>
                </div>

                <div class="server-episode-wrapper">
                    <div class="bg">
                        <div class="bg-header">Servers</div>
                        <div class="servers">
                            <button>DauVideo</button>
                            <button>Vidstreaming</button>
                            <button>Vidcloud</button>
                        </div>
                    </div>

                    <div class="bg">
                        <div class="bg-header">Episodes</div>
                        <div class="episodes">
                            <?php
                            // Generate episode buttons dynamically based on the episode count from database
                            for ($i = 1; $i <= $anime['episode']; $i++) {
                                $activeClass = ($i == 1) ? 'class="active"' : '';
                                echo "<button $activeClass>$i</button>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </section>

            <aside class="episode-sidebar block_area">
                <div class="block_area-header">
                    <h2 class="cat-heading"><?php echo htmlspecialchars($anime['title']); ?> - Episodes</h2>
                </div>

                <div class="episode-list">
                    <?php
                    // Generate episode list dynamically
                    for ($i = 1; $i <= $anime['episode']; $i++) {
                        echo '<div class="episode-item">';
                        echo '<div class="episode-thumb">';
                        echo '<img src="' . htmlspecialchars($anime['thumbnail']) . '" alt="' . htmlspecialchars($anime['title']) . ' Episode ' . $i . '">';
                        echo '<div class="episode-overlay">';
                        echo '<span>Episode ' . $i . '</span>';
                        echo '</div>';
                        echo '</div>';
                        echo '<div class="episode-title">';
                        echo htmlspecialchars($anime['title']) . ' - Episode ' . $i;
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </aside>
        </main>
    </div>

    <script>
        document.querySelector("main").classList.add("expanded");

        function toggleExpand() {
            const videoWrapper = document.getElementById("videoWrapper");
            const episodeSidebar = document.querySelector(".episode-sidebar");
            const serverWrapper = document.querySelector(".server-episode-wrapper");

            videoWrapper.classList.toggle("expanded");

            if (videoWrapper.classList.contains("expanded")) {
                episodeSidebar.style.display = "none";
                serverWrapper.style.display = "flex";
                serverWrapper.style.justifyContent = "center";
                serverWrapper.style.width = "100%";
            } else {
                episodeSidebar.style.display = "block";
                serverWrapper.style.display = "flex";
                serverWrapper.style.justifyContent = "flex-start";
                serverWrapper.style.width = "";
            }
        }
    </script>
    <?php include 'Assets/HTML/footer.html' ?>
</body>

</html>