<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StrikeFlix</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <a href="#" class="logo"><i class="fa-solid fa-bolt"></i>StrikeFlix</a>
        <nav class="navbar">
            <a class="active" href="#home">Home</a>
            <a href="#browse">Browse</a>

            <div class="dropdown">
                <a href="#Genres" class="dropbtn">Genres</a>
                <div class="dropdown-content">
                    <a href="#action">Action</a>
                    <a href="#romance">Romance</a>
                    <a href="#comedy">Comedy</a>
                    <a href="#drama">Drama</a>
                    <a href="#horror">Horror</a>
                </div>
            </div>

            <a href="#popular">Popular Now</a>
            <a href="#new-release">New Releases</a>
        </nav>
        <div class="icons">
            <i class="fas fa-bars" id="menu-bars"></i>
            <i class="fas fa-search" id="search-icon"></i>
            <a href="#" class="fas fa-heart"></a>
            <i class = "fa fa-user" id ="sign-in"></i>
        </div>
    </header>

    <section class="home" id="home">
        <div class="swiper home-slider">
            <div class="swiper-wrapper">

                <div class="swiper-slide">
                    <div class="box" style="background: url('images/1.jpg') no-repeat center/cover;">
                        <div class="content">
                            <h3>Kakegurui</h3>
                            <p>
                                High roller Yumeko Jabami plans to clean house at Hyakkaou Private<br>
                                Academy, a school where students are evaluated solely on their<br>
                                gambling skills.
                            </p>
                            <a href="#" class="btn">Watch</a>
                        </div>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="box second" style="background: url('images/2.jpg') no-repeat center/cover;">
                        <div class="content">
                            <h3>Death Note</h3>
                            <p>
                                When a Japanese high schooler comes into possession of a mystical<br>
                                notebook, he finds he has the power to kill anybody whose name he<br>
                                enters in it.
                            </p>
                            <a href="#" class="btn">Watch</a>
                        </div>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="box second" style="background: url('images/3.png') no-repeat center/cover;">
                        <div class="content">
                            <h3>Boku no Hero</h3>
                            <p>
                                When a powerless teen in a superhuman society inherits the abilities of the world's
                                greatest hero, he must train to become the symbol of peace and survive a high
                                school where danger is part of the curriculum
                            </p>
                            <a href="#" class="btn">Watch</a>
                        </div>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="box second" style="background: url('images/4.jpg') no-repeat center/cover;">
                        <div class="content">
                            <h3>Solo Leveling</h3>
                            <p>
                                When the world is invaded by deadly dungeons, a weak hunter gains the power to
                                level up without limit turning from the weakest of all into humanity's ultimate
                                weapon
                            </p>
                            <a href="#" class="btn">Watch</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="main.js"></script>        
</body>
</html>
