<?php
session_start();
include 'db.php';

// Get anime_id and episode from URL parameters
if (!isset($_GET['anime_id']) || !is_numeric($_GET['anime_id'])) {
    die("Invalid anime ID.");
}

$anime_id = (int)$_GET['anime_id'];
$episode_number = isset($_GET['episode']) ? (int)$_GET['episode'] : 1; // Default to episode 1

// Get anime information
$stmt = $mysqli->prepare("SELECT * FROM anime WHERE id = ?");
$stmt->bind_param("i", $anime_id);
$stmt->execute();
$anime_result = $stmt->get_result();

if ($anime_result->num_rows === 0) {
    die("Anime not found.");
}

$anime = $anime_result->fetch_assoc();

// Get current episode information
$episode_stmt = $mysqli->prepare("SELECT * FROM episodes WHERE anime_id = ? AND episode_number = ?");
$episode_stmt->bind_param("ii", $anime_id, $episode_number);
$episode_stmt->execute();
$episode_result = $episode_stmt->get_result();

if ($episode_result->num_rows === 0) {
    die("Episode not found.");
}

$current_episode = $episode_result->fetch_assoc();

// Get all episodes for this anime (for episode list)
$all_episodes_result = $mysqli->query("SELECT * FROM episodes WHERE anime_id = $anime_id ORDER BY episode_number");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($anime['title']); ?> Episode <?php echo $episode_number; ?> - StrikeFlix</title>
    <link rel="icon" type="image/svg+xml" href="assets/bolt-solid.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="Assets/css/watch.css">
</head>

<body>
    <div class="containers">
        <header>
            <?php include 'Assets/HTML/home-header.php' ?>
        </header>

        <main>
            <section class="video-section">
                <div class="breadcrumbs">
                    Home > <?php echo htmlspecialchars($anime['title']); ?> > Episode <?php echo $episode_number; ?>
                </div>

                <div id="videoWrapper">
                    <div class="video-player">
                        <video controls width="100%" height="auto">
                            <source src="<?php echo htmlspecialchars($current_episode['video_url']); ?>"
                                type="video/mp4" />
                            Your browser does not support the video tag.
                        </video>
                    </div>

                    <div class="video-controls">
                        <?php if ($episode_number < $anime['total_episodes']): ?>
                            <a
                                href="watch.php?anime_id=<?php echo $anime_id; ?>&episode=<?php echo $episode_number + 1; ?>">
                                <button>Next Episode <i class="fa-solid fa-forward"></i></button>
                            </a>
                        <?php endif; ?>
                        <button onclick="toggleExpand()">Expand <i class="fa-solid fa-expand"></i></button>
                    </div>
                </div>

                <div class="server-episode-wrapper">
                    <div class="bg">
                        <div class="bg-header">Episodes</div>
                        <div class="episodes">
                            <?php while ($episode = $all_episodes_result->fetch_assoc()): ?>
                                <?php
                                $activeClass = ($episode['episode_number'] == $episode_number) ? 'class="active"' : '';
                                ?>
                                <a
                                    href="watch.php?anime_id=<?php echo $anime_id; ?>&episode=<?php echo $episode['episode_number']; ?>">
                                    <button <?php echo $activeClass; ?>>
                                        <?php echo $episode['episode_number']; ?>
                                    </button>
                                </a>
                            <?php endwhile; ?>
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
                    // Reset result pointer for sidebar
                    $all_episodes_result->data_seek(0);
                    while ($episode = $all_episodes_result->fetch_assoc()):
                    ?>
                        <a
                            href="watch.php?anime_id=<?php echo $anime_id; ?>&episode=<?php echo $episode['episode_number']; ?>">
                            <div
                                class="episode-item <?php echo ($episode['episode_number'] == $episode_number) ? 'active' : ''; ?>">
                                <div class="episode-thumb">
                                    <img src="<?php echo htmlspecialchars($episode['thumbnail'] ?: $anime['thumbnail']); ?>"
                                        alt="<?php echo htmlspecialchars($anime['title']); ?> Episode <?php echo $episode['episode_number']; ?>">
                                    <div class="episode-overlay">
                                        <span>Episode <?php echo $episode['episode_number']; ?></span>
                                    </div>
                                </div>
                                <div class="episode-title">
                                    <?php echo htmlspecialchars($episode['episode_title'] ?: $anime['title'] . ' - Episode ' . $episode['episode_number']); ?>
                                </div>
                            </div>
                        </a>
                    <?php endwhile; ?>
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