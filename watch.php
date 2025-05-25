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
    <title>StrikeFlix</title>
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
                <div class="breadcrumbs">Home > Watching Solo Leveling</div>

                <div id="videoWrapper">
                    <div class="video-player">
                        <div class="video-player">
                            <video controls width="100%" height="auto">
                                <source
                                    src="https://rr4---sn-n4v7sney.googlevideo.com/videoplayback?expire=1748169768&ei=qIMyaJ_xGbGp2OMPsq7lyA4&ip=196.51.204.206&id=30dbceffcb42e409&itag=18&source=blogger&xpc=Egho7Zf3LnoBAQ%3D%3D&met=1748140968,&mh=k4&mm=31&mn=sn-n4v7sney&ms=au&mv=m&mvi=4&pl=17&rms=au,au&susc=bl&eaua=uN7j3T-ZPmc&mime=video/mp4&vprv=1&rqh=1&dur=1415.906&lmt=1747594127558530&mt=1748140605&txp=1311224&sparams=expire,ei,ip,id,itag,source,xpc,susc,eaua,mime,vprv,rqh,dur,lmt&sig=AJfQdSswRQIgUP_P9XfZaTu-IC52mgVG4z7XvYD3GuNqM1KvG1sOVJgCIQCzaxfTe-nKH6fW5XdE7hR4ZbYE_-P7PgqtbqAgcKC-lA%3D%3D&lsparams=met,mh,mm,mn,ms,mv,mvi,pl,rms&lsig=ACuhMU0wRQIhAI2DqC3g3nYHv3sJLM4i3hQ_LoGQuqwOaa9k-Lv8XRvvAiBS6dedYcoPRlSn9S3Tebs7Rd8adFZIiyNtmy0DGaQyRA%3D%3D"
                                    type="video/mp4" />
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
                            <button class="active">1</button>
                            <button>2</button>
                            <button>3</button>
                            <button>4</button>
                            <button>5</button>
                            <button>6</button>
                            <button>7</button>
                            <button>8</button>
                            <button>9</button>
                            <button>10</button>
                            <button>11</button>
                        </div>
                    </div>
                </div>
            </section>

            <aside class="episode-sidebar block_area">
                <div class="block_area-header">
                    <h2 class="cat-heading">Solo Leveling - Seasons</h2>
                </div>

                <div class="episode-list">
                    <div class="episode-item">
                        <div class="episode-thumb">
                            <img src="Assets/images/solo_ep1.jpe" alt="Solo Leveling Episode 1">
                            <div class="episode-overlay">
                                <span>Season 1</span>
                            </div>
                        </div>
                        <div class="episode-title">
                            Solo Leveling - Season 1
                        </div>
                    </div>

                    <div class="episode-item">
                        <div class="episode-thumb">
                            <img src="Assets/images/solo_s2.png" alt="Solo Leveling Episode 2">
                            <div class="episode-overlay">
                                <span>Season 2</span>
                            </div>
                        </div>
                        <div class="episode-title">
                            Solo Leveling - Season 2
                        </div>
                    </div>

                    <div class="episode-item">
                        <div class="episode-thumb">
                            <img src="Assets/images/solo.jpg" alt="Solo Leveling Episode 3">
                            <div class="episode-overlay">
                                <span>Season 3</span>
                            </div>
                        </div>
                        <div class="episode-title">
                            Solo Leveling - Season 3
                        </div>
                    </div>

                    <div class="episode-item">
                        <div class="episode-thumb">
                            <img src="Assets/images/solo.jpg" alt="Solo Leveling Episode 3">
                            <div class="episode-overlay">
                                <span>Season 3</span>
                            </div>
                        </div>
                        <div class="episode-title">
                            Solo Leveling - Season 3
                        </div>
                    </div>
                </div>
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