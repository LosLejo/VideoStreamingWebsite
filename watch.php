<?php
session_start();
include 'db.php';

// Get series_id and episode from URL parameters (changed from anime_id to series_id)
if (!isset($_GET['series_id']) || !is_numeric($_GET['series_id'])) {
    die("Invalid anime ID.");
}

$series_id = (int)$_GET['series_id'];
$episode_number = isset($_GET['episode']) ? (int)$_GET['episode'] : 1; // Default to episode 1

// Get anime information (changed table name from anime to anime_series)
$stmt = $mysqli->prepare("SELECT * FROM anime_series WHERE id = ?");
$stmt->bind_param("i", $series_id);
$stmt->execute();
$anime_result = $stmt->get_result();

if ($anime_result->num_rows === 0) {
    die("Anime not found.");
}

$anime = $anime_result->fetch_assoc();

// Get current episode information (changed anime_id to anime_series_id)
$episode_stmt = $mysqli->prepare("SELECT * FROM episodes WHERE anime_series_id = ? AND episode_number = ?");
$episode_stmt->bind_param("ii", $series_id, $episode_number);
$episode_stmt->execute();
$episode_result = $episode_stmt->get_result();

if ($episode_result->num_rows === 0) {
    die("Episode not found.");
}

$current_episode = $episode_result->fetch_assoc();

// Get all episodes for this anime (for episode list) - changed anime_id to anime_series_id
$all_episodes_stmt = $mysqli->prepare("SELECT * FROM episodes WHERE anime_series_id = ? ORDER BY episode_number");
$all_episodes_stmt->bind_param("i", $series_id);
$all_episodes_stmt->execute();
$all_episodes_result = $all_episodes_stmt->get_result();
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
    <!-- Theater Mode Overlay -->
    <div class="theater-mode" id="theaterMode">
        <div class="theater-close-hint">Click anywhere to exit theater mode</div>
        <div class="theater-video-container" id="theaterVideoContainer">
            <!-- Video will be cloned here -->
        </div>
        <!-- Theater controls -->
        <div class="theater-controls" id="theaterControls">
            <?php if ($episode_number > 1): ?>
            <button onclick="navigateEpisode(<?php echo $episode_number - 1; ?>)">
                <i class="fa-solid fa-backward"></i> Previous
            </button>
            <?php endif; ?>
            <?php if ($episode_number < $anime['total_episodes']): ?>
            <button onclick="navigateEpisode(<?php echo $episode_number + 1; ?>)">
                Next <i class="fa-solid fa-forward"></i>
            </button>
            <?php endif; ?>
            <button onclick="exitTheaterMode()">
                <i class="fa-solid fa-compress"></i> Exit Theater
            </button>
        </div>
    </div>

    <!-- Move header OUTSIDE containers -->
    <?php include 'Assets/HTML/home-header.php' ?>

    <main>
        <section class="video-section">
            <div class="breadcrumbs">
                Home > <?php echo htmlspecialchars($anime['title']); ?> > Episode <?php echo $episode_number; ?>
            </div>

            <div id="videoWrapper">
                <div class="video-player" id="mainVideoPlayer">
                    <?php
                    $video_url = $current_episode['video_url'];
                    $is_youtube = (strpos($video_url, 'youtube.com/embed/') !== false);
                    ?>
                    <?php if ($is_youtube): ?>
                    <iframe width="100%" height="500" src="<?php echo htmlspecialchars($video_url); ?>" frameborder="0"
                        allowfullscreen id="mainVideo"></iframe>
                    <?php else: ?>
                    <video controls width="100%" height="auto" id="mainVideo">
                        <source src="<?php echo htmlspecialchars($video_url); ?>" type="video/mp4" />
                        Your browser does not support the video tag.
                    </video>
                    <?php endif; ?>
                </div>

                <div class="video-controls">
                    <?php if ($episode_number < $anime['total_episodes']): ?>
                    <a href="watch.php?series_id=<?php echo $series_id; ?>&episode=<?php echo $episode_number + 1; ?>">
                        <button>Next Episode <i class="fa-solid fa-forward"></i></button>
                    </a>
                    <?php endif; ?>
                    <button onclick="toggleExpand()">Expand <i class="fa-solid fa-expand"></i></button>
                    <button class="theater-btn" onclick="enterTheaterMode()">
                        <i class="fa-solid fa-tv"></i> Theater Mode
                    </button>
                </div>

                <div class="server-episode-wrapper">
                    <div class="bg">
                        <div class="bg-header">Episodes</div>
                        <div class="episodes">
                            <?php while ($episode = $all_episodes_result->fetch_assoc()): ?>
                            <?php $activeClass = ($episode['episode_number'] == $episode_number) ? 'class="active"' : ''; ?>
                            <a
                                href="watch.php?series_id=<?php echo $series_id; ?>&episode=<?php echo $episode['episode_number']; ?>">
                                <button <?php echo $activeClass; ?>>
                                    <?php echo $episode['episode_number']; ?>
                                </button>
                            </a>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <aside class="episode-sidebar">
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
                    href="watch.php?series_id=<?php echo $series_id; ?>&episode=<?php echo $episode['episode_number']; ?>">
                    <div
                        class="episode-item <?php echo ($episode['episode_number'] == $episode_number) ? 'active' : ''; ?>">
                        <div class="episode-thumb">
                            <img src="<?php echo htmlspecialchars($anime['thumbnail']); ?>"
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

    <script>
    let theaterModeActive = false;

    // Theater Mode Functions - FIXED VERSION
    function enterTheaterMode() {
        const theaterMode = document.getElementById('theaterMode');
        const theaterContainer = document.getElementById('theaterVideoContainer');
        const mainVideo = document.getElementById('mainVideo');

        // Clone the video element
        const videoClone = mainVideo.cloneNode(true);
        videoClone.style.width = '100%';
        videoClone.style.height = 'auto';

        // Clear theater container and add cloned video
        theaterContainer.innerHTML = '';
        theaterContainer.appendChild(videoClone);

        // Show theater mode
        theaterMode.classList.add('active');
        document.body.classList.add('theater-active');
        theaterModeActive = true;

        // If it's a video element, sync the time
        if (mainVideo.tagName === 'VIDEO' && videoClone.tagName === 'VIDEO') {
            videoClone.currentTime = mainVideo.currentTime;
            if (!mainVideo.paused) {
                videoClone.play();
            }
        }

        console.log('Theater mode entered successfully');
    }

    function exitTheaterMode() {
        const theaterMode = document.getElementById('theaterMode');
        const theaterContainer = document.getElementById('theaterVideoContainer');
        const mainVideo = document.getElementById('mainVideo');
        const theaterVideo = document.querySelector('#theaterVideoContainer video, #theaterVideoContainer iframe');

        // Sync time back to main video if applicable
        if (mainVideo.tagName === 'VIDEO' && theaterVideo && theaterVideo.tagName === 'VIDEO') {
            mainVideo.currentTime = theaterVideo.currentTime;
            if (!theaterVideo.paused) {
                mainVideo.play();
            }
        }

        // Clear theater container
        theaterContainer.innerHTML = '';

        // Hide theater mode
        theaterMode.classList.remove('active');
        document.body.classList.remove('theater-active');
        theaterModeActive = false;

        console.log('Theater mode exited successfully');
    }

    function navigateEpisode(episodeNum) {
        window.location.href = `watch.php?series_id=<?php echo $series_id; ?>&episode=${episodeNum}`;
    }

    // Click to exit theater mode - IMPROVED VERSION
    document.getElementById('theaterMode').addEventListener('click', function(e) {
        // Only exit if clicking on the theater mode overlay itself
        if (e.target === this) {
            exitTheaterMode();
        }
    });

    // Prevent video container clicks from closing theater mode
    document.getElementById('theaterVideoContainer').addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Prevent theater controls clicks from closing theater mode
    document.getElementById('theaterControls').addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Keyboard shortcuts - IMPROVED VERSION
    document.addEventListener('keydown', function(e) {
        if (theaterModeActive) {
            if (e.key === 'Escape') {
                exitTheaterMode();
            }
        } else {
            if (e.key === 't' || e.key === 'T') {
                e.preventDefault(); // Prevent any default behavior
                enterTheaterMode();
            }
        }
    });

    // Add click event listener to theater button - ENSURE IT WORKS
    document.addEventListener('DOMContentLoaded', function() {
        const theaterBtn = document.querySelector('.theater-btn');
        if (theaterBtn) {
            theaterBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (!theaterModeActive) {
                    enterTheaterMode();
                }
            });
        }
    });

    // Existing expand function (unchanged)
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