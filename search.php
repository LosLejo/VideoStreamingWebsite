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
    /* --- Fixed Swiper and Card Styles --- */
    .container {
        margin: 4rem 2rem;
        /* Changed from 40rem 20rem */
        padding-top: 8rem;
        /* Add space for fixed header */
    }

    .anime-search-section {
        background: transparent !important;
    }

    .heading {
        font-size: 2.8rem;
        /* Changed from 28rem */
        margin-bottom: 2rem;
        /* Changed from 20rem */
        color: var(--yellow) !important;
        font-weight: bold;
        text-transform: capitalize;
        text-align: center;
    }

    html {
        background: var(--black);
    }

    body {
        background: var(--black);
        min-height: 100vh;
        overflow-x: hidden;
        /* Prevent horizontal scroll */
    }

    .swiper {
        width: 100%;
        padding: 2rem 0 !important;
        /* Changed from 0 */
        overflow: visible;
    }

    .swiper-wrapper {
        display: flex;
        align-items: stretch;
    }

    .swiper-slide {
        width: auto !important;
        margin-right: 2rem;
        /* Changed from 20rem */
        background: transparent !important;
    }

    .box {
        width: 20rem;
        /* Changed from 200rem */
        height: 30rem;
        /* Changed from 300rem */
        background-size: cover !important;
        background-position: center !important;
        background-repeat: no-repeat !important;
        border-radius: 1rem;
        /* Changed from 10rem */
        position: relative;
        overflow: hidden;
        box-shadow: 0 0.4rem 0.8rem rgba(0, 0, 0, 0.3);
        /* Changed from 0 4rem 8rem */
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
        border: 0.1rem solid rgba(255, 255, 255, 0.1);
    }

    .box:hover {
        transform: scale(1.05);
        box-shadow: 0 0.8rem 1.6rem rgba(0, 0, 0, 0.5);
        /* Changed from 0 8rem 16rem */
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
        font-size: 1.4rem;
        /* Changed from 14rem */
        text-align: center;
        border-radius: 1rem;
        /* Changed from 10rem */
        position: relative;
        z-index: 2;
        flex-direction: column;
    }

    .no-image-placeholder i {
        font-size: 2.4rem !important;
        /* Changed from 24rem */
        margin-bottom: 0.8rem !important;
        /* Changed from 8rem */
    }

    .content {
        padding: 1rem 0;
        /* Reduced from 1.5rem 0 */
        max-width: 20rem;
        /* Changed from 200rem */
        background: transparent !important;
    }

    .content h3 {
        margin: 1rem 0 0.8rem 0;
        /* Changed from 10rem 0 8rem 0 */
        font-size: 1.6rem;
        /* Changed from 16rem */
        font-weight: bold;
        color: #ffffff !important;
        line-height: 1.3;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .btn {
        background: var(--yellow) !important;
        color: var(--black) !important;
        padding: 0.8rem 1.5rem;
        /* Reduced from 1rem 1rem */
        text-decoration: none;
        border-radius: 0.5rem;
        font-size: 1.3rem;
        /* Changed from 13rem */
        font-weight: bold;
        display: inline-block;
        transition: background-color 0.3s ease, transform 0.3s ease;
        border: none;
        cursor: pointer;
        min-height: auto;
        /* Removed fixed min-height */
        text-align: center;
        line-height: 1;
        /* Reduced from 1.2 */
        margin-top: 0.5rem;
        /* Add slight margin instead of padding */
    }

    .btn:hover {
        background: #fff !important;
        text-decoration: none;
        transform: translateY(-0.2rem);
    }

    .swiper-button-next,
    .swiper-button-prev {
        color: #ffffff !important;
        background: rgba(0, 0, 0, 0.5);
        width: 4rem;
        /* Changed from 40rem */
        height: 4rem;
        /* Changed from 40rem */
        border-radius: 50%;
        margin-top: -2rem;
        /* Changed from -20rem */
        border: 0.1rem solid rgba(255, 255, 255, 0.2);
    }

    .swiper-button-next::after,
    .swiper-button-prev::after {
        font-size: 1.6rem;
        /* Changed from 16rem */
        font-weight: bold;
    }

    .swiper-button-next:hover,
    .swiper-button-prev:hover {
        background: rgba(255, 247, 0, 0.8);
        color: var(--black) !important;
    }

    /* No results message */
    .container p {
        color: #ffffff;
        font-size: 1.8rem;
        text-align: center;
        margin-top: 4rem;
    }

    /* Mobile Responsive Styles */
    @media (max-width: 76.8rem) {
        .container {
            margin: 2rem 1rem;
            /* Changed from 20rem 10rem */
            padding-top: 12rem;
            /* More space for mobile header with hamburger */
        }

        .heading {
            font-size: 2.4rem;
            margin-bottom: 1.5rem;
            text-align: center;
            padding: 0 1rem;
            /* Add padding for better readability */
        }

        .box {
            width: 16rem;
            height: 24rem;
        }

        .content {
            max-width: 16rem;
            padding: 0.8rem 0;
            /* Reduced padding */
        }

        .content h3 {
            font-size: 1.4rem;
            line-height: 1.2;
            margin: 1rem 0 0.8rem 0;
        }

        .btn {
            padding: 0.7rem 1.2rem;
            /* Reduced padding */
            font-size: 1.2rem;
            min-height: auto;
            /* Remove fixed height */
            width: 100%;
            /* Full width button on mobile */
            line-height: 1;
            margin-top: 0.5rem;
        }

        /* Better swiper navigation for mobile */
        .swiper-button-next,
        .swiper-button-prev {
            width: 4rem;
            height: 4rem;
            margin-top: -2rem;
            background: rgba(255, 247, 0, 0.8) !important;
            color: var(--black) !important;
        }

        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 1.6rem;
            font-weight: bold;
        }
    }

    @media (max-width: 48rem) {
        .container {
            margin: 1.5rem 0.5rem;
            padding-top: 14rem;
            /* Even more space for smaller screens */
        }

        .box {
            width: 14rem;
            height: 21rem;
        }

        .content {
            max-width: 14rem;
            padding: 0.7rem 0;
            /* Further reduced */
        }

        .btn {
            padding: 0.6rem 1rem;
            /* Smaller padding */
            font-size: 1.1rem;
            min-height: auto;
            /* Remove fixed height */
            border-radius: 0.4rem;
            line-height: 1;
            margin-top: 0.4rem;
        }

        .swiper-button-next,
        .swiper-button-prev {
            width: 3.5rem;
            height: 3.5rem;
            margin-top: -1.75rem;
        }

        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 1.4rem;
        }

        /* Hide navigation on very small screens and rely on swipe */
        .swiper-button-next,
        .swiper-button-prev {
            opacity: 0.7;
        }
    }

    /* Extra small screens (phones) */
    @media (max-width: 32rem) {
        .container {
            margin: 1rem 0.3rem;
            padding-top: 16rem;
            /* Maximum space for very small screens */
        }

        .heading {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            padding: 0 0.3rem;
            line-height: 1.3;
        }

        .box {
            width: 12rem;
            height: 18rem;
        }

        .content {
            max-width: 12rem;
            padding: 0.6rem 0;
            /* Minimal padding */
        }

        .content h3 {
            font-size: 1.2rem;
            margin: 0.6rem 0 0.5rem 0;
            line-height: 1.2;
        }

        .btn {
            padding: 0.5rem 0.8rem;
            /* Compact padding */
            font-size: 1rem;
            min-height: auto;
            /* Remove fixed height */
            border-radius: 0.3rem;
            line-height: 1;
            margin-top: 0.3rem;
        }

        /* Completely hide navigation on very small screens */
        .swiper-button-next,
        .swiper-button-prev {
            display: none;
        }
    }

    /* Landscape mode adjustments for mobile */
    @media (max-height: 500px) and (orientation: landscape) {
        .container {
            padding-top: 8rem;
            /* Reduce top padding in landscape */
        }

        .heading {
            font-size: 1.6rem;
            margin-bottom: 0.8rem;
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
                            <i class="fas fa-image"></i><br>
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
            centeredSlides: false, // Changed from true for better mobile UX
            freeMode: true, // Allow free scrolling on mobile
            freeModeSticky: true, // Snap to slides
            navigation: {
                nextEl: '.anime-search-swiper .swiper-button-next',
                prevEl: '.anime-search-swiper .swiper-button-prev',
            },
            // Better touch sensitivity
            touchRatio: 1,
            touchAngle: 45,
            simulateTouch: true,
            allowTouchMove: true,
            touchStartPreventDefault: false,
            breakpoints: {
                320: {
                    slidesPerView: 1.2,
                    spaceBetween: 10,
                    centeredSlides: true
                },
                480: {
                    slidesPerView: 1.8,
                    spaceBetween: 15,
                    centeredSlides: false
                },
                640: {
                    slidesPerView: 2.5,
                    spaceBetween: 15,
                    centeredSlides: false
                },
                768: {
                    slidesPerView: 3.2,
                    spaceBetween: 20,
                    centeredSlides: false
                },
                1024: {
                    slidesPerView: 4.2,
                    spaceBetween: 20,
                    centeredSlides: false
                },
                1200: {
                    slidesPerView: 5.2,
                    spaceBetween: 20,
                    centeredSlides: false
                },
                1400: {
                    slidesPerView: 6.2,
                    spaceBetween: 20,
                    centeredSlides: false
                }
            }
        });
    });
    </script>
</body>

</html>