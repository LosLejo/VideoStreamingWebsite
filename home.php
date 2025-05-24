<?php
session_start();
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

                <div class="swiper-slide">
                    <div class="box" style="background: url('Assets/images/kake.jpg') no-repeat center/cover;">
                        <div class="content">
                            <h3>Kakegurui</h3>
                            <p>
                                High roller Yumeko Jabami plans to clean house at Hyakkaou Private<br>
                                Academy, a school where students are evaluated solely on their<br>
                                gambling skills.
                            </p>
                            <a href="watch" class="btn">Watch</a>
                        </div>
                    </div>
                </div>

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

    <section class="anime" id="browse">
        <h1 class="heading">Browse <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
        <div class="swiper anime-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide">

                    <div class="box" style="background: url('Assets/images/haik.jpg') no-repeat">

                    </div>

                    <div class="content">
                        <h3>Haikyuu!!</h3>
                        <p>When a short high schooler with big dreams joins <br>
                            a powerhouse volleyball team he must rise above <br>
                            the odds—spiking his way from underdog to unstoppable.
                        </p>
                        <a href="watch" class="btn">Watch</a>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="box" style="background: url('Assets/images/tobe.jpg') no-repeat">

                    </div>
                    <div class="content">
                        <h3>To Be Hero X</h3>
                        <p>When a short high schooler with big dreams joins <br>
                            a powerhouse volleyball team he must rise above <br>
                            the odds—spiking his way from underdog to unstoppable.
                        </p>
                        <a href="watch" class="btn">Watch</a>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="box" style="background: url('Assets/images/haik.jpg') no-repeat">

                    </div>
                    <div class="content">
                        <h3>Haikyuu</h3>
                        <p>When a short high schooler with big dreams joins <br>
                            a powerhouse volleyball team he must rise above <br>
                            the odds—spiking his way from underdog to unstoppable.
                        </p>
                        <a href="watch" class="btn">Watch</a>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="box" style="background: url('Assets/images/haik.jpg') no-repeat">

                    </div>
                    <div class="content">
                        <h3>Haikyuu</h3>
                        <p>When a short high schooler with big dreams joins <br>
                            a powerhouse volleyball team he must rise above <br>
                            the odds—spiking his way from underdog to unstoppable.
                        </p>
                        <a href="watch" class="btn">Watch</a>
                    </div>
                </div>

                <div class="swiper-slide">

                    <div class="box" style="background: url('Assets/images/haik.jpg') no-repeat">

                    </div>

                    <div class="content">
                        <h3>Haikyuu!!</h3>
                        <p>When a short high schooler with big dreams joins <br>
                            a powerhouse volleyball team he must rise above <br>
                            the odds—spiking his way from underdog to unstoppable.
                        </p>
                        <a href="watch" class="btn">Watch</a>
                    </div>
                </div>

                <div class="swiper-slide">

                    <div class="box" style="background: url('Assets/images/haik.jpg') no-repeat">

                    </div>

                    <div class="content">
                        <h3>Haikyuu!!</h3>
                        <p>When a short high schooler with big dreams joins <br>
                            a powerhouse volleyball team he must rise above <br>
                            the odds—spiking his way from underdog to unstoppable.
                        </p>
                        <a href="watch" class="btn">Watch</a>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="box" style="background: url('Assets/images/haik.jpg') no-repeat">

                    </div>
                    <div class="content">
                        <h3>Haikyuu</h3>
                        <p>When a short high schooler with big dreams joins <br>
                            a powerhouse volleyball team he must rise above <br>
                            the odds—spiking his way from underdog to unstoppable.
                        </p>
                        <a href="watch" class="btn">Watch</a>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="box" style="background: url('Assets/images/haik.jpg') no-repeat">

                    </div>
                    <div class="content">
                        <h3>Haikyuu!!</h3>
                        <p>When a short high schooler with big dreams joins <br>
                            a powerhouse volleyball team he must rise above <br>
                            the odds—spiking his way from underdog to unstoppable.
                        </p>
                        <a href="watch" class="btn">Watch</a>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="box" style="background: url('Assets/images/haik.jpg') no-repeat">

                    </div>
                    <div class="content">
                        <h3>Haikyuu!!</h3>
                        <p>When a short high schooler with big dreams joins <br>
                            a powerhouse volleyball team he must rise above <br>
                            the odds—spiking his way from underdog to unstoppable.
                        </p>
                        <a href="watch" class="btn">Watch</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="anime" id="new-releases">
        <h1 class="heading">New Releases <?php echo htmlspecialchars(strtolower($_SESSION['user_email'])); ?></h1>
        <div class="swiper new-release-slider">
            <div class="swiper-wrapper">

                <div class="swiper-slide">
                    <div class="box" style="background: url('Assets/images/jjk.png') no-repeat">

                    </div>
                    <div class="content">
                        <h3>Jujutsu Kaisen Season 2</h3>
                        <p>As curses grow stronger, Yuji and his allies face deadly battles<br>
                            in this darker, more intense season of the hit anime.</p>
                        <a href="watch" class="btn">Watch</a>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="box" style="background: url('Assets/images/aot.jpg') no-repeat">

                    </div>
                    <div class="content">
                        <h3>Attack on Titan: Final Season</h3>
                        <p>The epic finale begins as Eren's true plan unfolds and<br>
                            the fate of humanity hangs in the balance.</p>
                        <a href="watch" class="btn">Watch</a>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="box" style="background: url('Assets/images/demonslayer.jpg') no-repeat">

                    </div>
                    <div class="content">
                        <h3>Demon Slayer: Hashira Training Arc</h3>
                        <p>Tanjiro and his friends prepare for the toughest demons yet<br>
                            as they train under the mighty Hashira warriors.</p>
                        <a href="watch" class="btn">Watch</a>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="box" style="background: url('Assets/images/haik.jpg') no-repeat">

                    </div>

                    <div class="content">
                        <h3>Haikyuu!!</h3>
                        <p>When a short high schooler with big dreams joins <br>
                            a powerhouse volleyball team he must rise above <br>
                            the odds—spiking his way from underdog to unstoppable.
                        </p>
                        <a href="watch" class="btn">Watch</a>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="box" style="background: url('Assets/images/jjk.png') no-repeat">

                    </div>
                    <div class="content">
                        <h3>Jujutsu Kaisen Season 2</h3>
                        <p>As curses grow stronger, Yuji and his allies face deadly battles<br>
                            in this darker, more intense season of the hit anime.</p>
                        <a href="watch" class="btn">Watch</a>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="box" style="background: url('Assets/images/aot.jpg') no-repeat">

                    </div>
                    <div class="content">
                        <h3>Attack on Titan: Final Season</h3>
                        <p>The epic finale begins as Eren's true plan unfolds and<br>
                            the fate of humanity hangs in the balance.</p>
                        <a href="watch" class="btn">Watch</a>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="box" style="background: url('Assets/images/demonslayer.jpg') no-repeat">

                    </div>
                    <div class="content">
                        <h3>Demon Slayer: Hashira Training Arc</h3>
                        <p>Tanjiro and his friends prepare for the toughest demons yet<br>
                            as they train under the mighty Hashira warriors.</p>
                        <a href="watch" class="btn">Watch</a>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="box" style="background: url('Assets/images/haik.jpg') no-repeat">

                    </div>

                    <div class="content">
                        <h3>Haikyuu!!</h3>
                        <p>When a short high schooler with big dreams joins <br>
                            a powerhouse volleyball team he must rise above <br>
                            the odds—spiking his way from underdog to unstoppable.
                        </p>
                        <a href="watch" class="btn">Watch</a>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <?php include 'Assets/HTML/footer.html' ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="Assets/js/main.js"></script>
</body>

</html>