<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'db.php';

$q = trim($_GET['q'] ?? '');

$results = [];
if ($q !== '') {
    $stmt = $mysqli->prepare(
        "SELECT id, title, thumbnail FROM anime_series
         WHERE title COLLATE utf8mb4_unicode_ci LIKE CONCAT('%', ?, '%') COLLATE utf8mb4_unicode_ci
         ORDER BY title LIMIT 20"
    );
    if ($stmt) {
        $stmt->bind_param("s", $q);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $results[] = $row;
        }
    } else {
        die("SQL Prepare failed: " . $mysqli->error);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Search Results for <?= htmlspecialchars($q) ?> - StrikeFlix</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <link rel="stylesheet" href="Assets/css/style.css">
    <style>
    /* --- Swiper and Card Styles (from genres.php) --- */
    .container {
        margin: 40rem 20rem;
    }

    .anime-search-section {
        background: transparent !important;
    }

    .heading {
        font-size: 28rem;
        margin-bottom: 20rem;
        color: yellow !important;
        font-weight: bold;
        text-transform: capitalize;
    }

    html {
        background: black;
    }

    .swiper {
        width: 100%;
        padding: 0 !important;
        overflow: visible;
    }

    .swiper-wrapper {
        display: flex;
        align-items: stretch;
    }

    .swiper-slide {
        width: auto !important;
        margin-right: 20rem;
        background: transparent !important;
    }

    .box {
        width: 200rem;
        height: 300rem;
        background-size: cover !important;
        background-position: center !important;
        background-repeat: no-repeat !important;
        border-radius: 10rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4rem 8rem rgba(0, 0, 0, 0.3);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }

    .box:hover {
        transform: scale(1.05);
        box-shadow: 0 8rem 16rem rgba(0, 0, 0, 0.5);
    }

    .box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.7) 100%);
        z-index: 1;
    }

    .no-image-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        background: linear-gradient(135deg, #333333, #555555);
        color: #cccccc;
        font-size: 14rem;
        text-align: center;
        border-radius: 10rem;
        position: relative;
        z-index: 2;
        flex-direction: column;
    }

    .content {
        padding: 15rem 0;
        max-width: 200rem;
        background: transparent !important;
    }

    .content h3 {
        margin: 10rem 0 8rem 0;
        font-size: 16rem;
        font-weight: bold;
        color: #ffffff !important;
        line-height: 1.3;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .btn {
        background: yellow !important;
        color: black !important;
        padding: 10rem 20rem;
        text-decoration: none;
        border-radius: 5rem;
        font-size: 13rem;
        font-weight: bold;
        display: inline-block;
        transition: background-color 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn:hover {
        background: white !important;
        text-decoration: none;
    }

    .swiper-button-next,
    .swiper-button-prev {
        color: #ffffff !important;
        background: rgba(0, 0, 0, 0.5);
        width: 40rem;
        height: 40rem;
        border-radius: 50%;
        margin-top: -20rem;
    }

    .swiper-button-next::after,
    .swiper-button-prev::after {
        font-size: 16rem;
        font-weight: bold;
    }

    .swiper-button-next:hover,
    .swiper-button-prev:hover {
        background: rgba(0, 0, 0, 0.8);
    }

    @media (max-width: 768rem) {
        .container {
            margin: 20rem 10rem;
        }

        .heading {
            font-size: 24rem;
            color: yellow;
        }

        .box {
            width: 160rem;
            height: 240rem;
        }

        .content {
            max-width: 160rem;
        }
    }

    @media (max-width: 480rem) {
        .box {
            width: 140rem;
            height: 210rem;
        }

        .content {
            max-width: 140rem;
        }

        .heading {
            font-size: 20rem;
            color: yellow;
        }
    }
    </style>
</head>

<body>
    <?php include 'Assets/HTML/home-header.php'; ?>
    <div class="container anime-search-section">
        <h2 class="heading">Search Results for: <em><?= htmlspecialchars($q) ?></em></h2>
        <?php if (empty($results)): ?>
        <p>No anime found.</p>
        <?php else: ?>
        <div class="swiper anime-search-swiper">
            <div class="swiper-wrapper">
                <?php foreach ($results as $anime): ?>
                <div class="swiper-slide">
                    <div class="box"
                        style="background-image: url('<?= htmlspecialchars($anime['thumbnail'] ?: 'Assets/images/placeholder.png') ?>');">
                        <?php if (empty($anime['thumbnail'])): ?>
                        <div class="no-image-placeholder">
                            <i class="fas fa-image" style="font-size: 24rem; margin-bottom: 8rem;"></i><br>
                            No Image Available
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="content">
                        <h3><?= htmlspecialchars($anime['title']) ?></h3>
                        <a href="watch.php?series_id=<?= $anime['id'] ?>" class="btn">Watch Now</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        new Swiper('.anime-search-swiper', {
            slidesPerView: 'auto',
            spaceBetween: 20,
            grabCursor: true,
            centeredSlides: true,
            navigation: {
                nextEl: '.anime-search-swiper .swiper-button-next',
                prevEl: '.anime-search-swiper .swiper-button-prev',
            },
            breakpoints: {
                320: {
                    slidesPerView: 1.5,
                    spaceBetween: 10
                },
                480: {
                    slidesPerView: 2.5,
                    spaceBetween: 15
                },
                768: {
                    slidesPerView: 3.5,
                    spaceBetween: 20
                },
                1024: {
                    slidesPerView: 4.5,
                    spaceBetween: 20
                },
                1200: {
                    slidesPerView: 5.5,
                    spaceBetween: 20
                },
                1400: {
                    slidesPerView: 6.5,
                    spaceBetween: 20
                }
            }
        });
    });
    </script>
</body>

</html>