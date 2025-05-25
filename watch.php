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
            <?php include 'Assets/HTML/header-watch.php' ?>
        </header>

        <main>
            <section class="video-section">
                <div class="breadcrumbs">Home > Watching Solo Leveling</div>

                <div id="videoWrapper">
                    <div class="video-player">
                        <div class="video-player">
                            <video controls width="100%" height="auto">
                                <source
                                    src="https://macdn.hakunaymatata.com/resource/bcd4255d03682395796faeb9ab9e1efa.mp4?Expires=1749314587&Signature=IdCKAZZQgr3e8HuLzFfZ~7GwBHJhKk1CNAoPWbz0CcH6PXkYqxTH~PnjXDLvK7crYF5xvFC7UGvnM2kcq6IUnSb0k4v7uCfvawzBC2Gb~kLysuZ~H5b20OjTD1h98aD~H0QFj-MZ2Uzs086OnsrnPS1Sye1tR1o2K39TycOsutSyhXNG3JxYQ7By~Rhrx-8QsDQz98dv4WTOb8tmB1rAyPU3aPeCMvshfjiMEzeCRQcgwKDjZpzvTj1XVtjk0j-WXiGDegc7T-sdFDco2RPKrND~vTxmZEMYL5fGni~QUTEMVaZsVo9CdhogKo9qI3PlCnGccGNUEz7dM5YBHeemKA__&Key-Pair-Id=KMHN1LQ1HEUPL"
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
                            <button>12</button>
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