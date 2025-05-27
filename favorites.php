<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

$user_id = $_SESSION['user_id'];

// Fetch the user's watchlist with anime info
$query = "
    SELECT 
        uw.anime_series_id, 
        asr.title, 
        asr.thumbnail, 
        asr.total_episodes,
        asr.rating as anime_rating,
        uw.status, 
        uw.current_episode, 
        uw.rating as user_rating, 
        uw.added_date
    FROM user_watchlist uw
    JOIN anime_series asr ON uw.anime_series_id = asr.id
    WHERE uw.user_id = ?
    ORDER BY uw.added_date DESC
";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$watchlist = [];
while ($row = $result->fetch_assoc()) {
  $watchlist[] = $row;
}

$stmt->close();
$mysqli->close();
?>

<?php include 'Assets/HTML/home-header.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Your Watchlist - StrikeFlix</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/svg+xml" href="assets/bolt-solid.svg">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="Assets/css/style.css">
  <style>
    body {
      background: #141414 !important;
      color: #fff !important;
      font-family: 'Arial', sans-serif;
      margin: 0;
      min-height: 100vh;
    }

    .container {
      max-width: 1200px;
      margin: 4rem auto 2rem auto;
      padding: 0 2rem;
    }

    .watchlist-heading {
      font-size: 2.4rem;
      color: yellow;
      font-weight: bold;
      margin-bottom: 1.5rem;
      text-align: left;
      letter-spacing: 1px;
    }

    .watchlist-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(235px, 1fr));
      gap: 2rem;
      margin-top: 2rem;
    }

    .watchlist-card {
      background: #181818;
      border-radius: 1.2rem;
      overflow: hidden;
      box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.25);
      display: flex;
      flex-direction: column;
      transition: transform 0.22s, box-shadow 0.22s;
      position: relative;
    }

    .watchlist-card:hover {
      transform: translateY(-8px) scale(1.03);
      box-shadow: 0 8px 34px 0 rgba(255, 255, 0, 0.12), 0 8px 30px rgba(0, 0, 0, 0.4);
      z-index: 2;
    }

    .watchlist-thumb {
      width: 100%;
      height: 320px;
      object-fit: cover;
      background: linear-gradient(135deg, #333, #444 80%);
      border-top-left-radius: 1.2rem;
      border-top-right-radius: 1.2rem;
      display: block;
    }

    .card-content {
      padding: 1.2rem 1.3rem 1.1rem 1.3rem;
      display: flex;
      flex-direction: column;
      flex: 1;
    }

    .watchlist-title {
      margin: 0 0 0.7rem 0;
      font-size: 1.18rem;
      font-weight: bold;
      color: #fff;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .watchlist-meta {
      font-size: 0.98rem;
      color: #b3b3b3;
      margin-bottom: 0.5rem;
    }

    .watchlist-status {
      color: yellow;
      font-weight: bold;
      letter-spacing: 0.5px;
      font-size: 1.06rem;
    }

    .watchlist-progress {
      font-size: 0.99rem;
      color: #b3b3b3;
    }

    .watchlist-rating {
      display: flex;
      align-items: center;
      font-size: 1rem;
      margin-top: 0.5rem;
    }

    .watchlist-rating .fa-star {
      color: #ffd700;
      margin-right: 0.3rem;
    }

    .user-rating-badge {
      background: yellow;
      color: #181818;
      font-size: 0.96rem;
      font-weight: bold;
      border-radius: 0.7rem;
      padding: 0.22rem 0.95rem;
      margin-left: 0.6rem;
      margin-top: 0.1rem;
      display: inline-block;
    }

    .watchlist-card .btn {
      background: yellow !important;
      color: #181818 !important;
      font-weight: bold;
      border-radius: 2rem;
      padding: 0.7rem 1.5rem;
      border: none;
      margin-top: 1rem;
      font-size: 1.03rem;
      text-align: center;
      transition: background 0.22s;
      cursor: pointer;
      text-decoration: none;
      letter-spacing: 0.8px;
    }

    .watchlist-card .btn:hover {
      background: #fff700 !important;
    }

    .added-date {
      font-size: 0.89rem;
      color: #888;
      margin-top: 0.6rem;
    }

    /* Empty state */
    .empty-watchlist {
      margin: 5rem auto 4rem auto;
      padding: 2.5rem 1.5rem 2rem 1.5rem;
      text-align: center;
      background: rgba(24, 24, 24, 0.98);
      border-radius: 1rem;
      max-width: 420px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.22);
    }

    .empty-watchlist i {
      font-size: 2.2rem;
      color: #ffd700;
      margin-bottom: 1.1rem;
    }

    .empty-watchlist h3 {
      color: yellow;
      margin-bottom: 0.7rem;
    }

    .empty-watchlist p {
      color: #b3b3b3;
      font-size: 1.13rem;
    }

    /* Responsive */
    @media (max-width: 800px) {
      .container {
        padding: 0 0.7rem;
      }

      .watchlist-grid {
        gap: 1.1rem;
      }

      .watchlist-thumb {
        height: 220px;
      }
    }

    @media (max-width: 480px) {
      .watchlist-thumb {
        height: 165px;
      }

      .container {
        padding: 0 0.1rem;
      }

      .watchlist-card .btn {
        padding: 0.5rem 0.9rem;
        font-size: 0.98rem;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="watchlist-heading"><i class="fa-solid fa-star"></i> Your Watchlist</div>
    <?php if (count($watchlist) === 0): ?>
      <div class="empty-watchlist">
        <i class="fa-regular fa-face-sad-tear"></i>
        <h3>No Anime in Watchlist</h3>
        <p>You haven't added any anime to your watchlist yet.<br>
          Start browsing and add your favorites!</p>
        <a href="home.php" class="btn" style="margin-top: 1.1rem;">Browse Anime</a>
      </div>
    <?php else: ?>
      <div class="watchlist-grid">
        <?php foreach ($watchlist as $item): ?>
          <div class="watchlist-card">
            <img src="<?= htmlspecialchars($item['thumbnail']) ?>" alt="Thumbnail" class="watchlist-thumb"
              loading="lazy" />
            <div class="card-content">
              <div class="watchlist-title"><?= htmlspecialchars($item['title']) ?></div>
              <div class="watchlist-meta">
                <span class="watchlist-status"><?= ucfirst(htmlspecialchars($item['status'])) ?></span>
                <span class="watchlist-progress">
                  &nbsp;| Ep
                  <?= htmlspecialchars($item['current_episode']) ?> /
                  <?= htmlspecialchars($item['total_episodes']) ?>
                </span>
              </div>
              <div class="watchlist-rating">
                <i class="fa fa-star"></i>
                <span><?= number_format($item['anime_rating'], 1) ?>/10</span>
                <?php if ($item['user_rating'] !== null): ?>
                  <span class="user-rating-badge"><?= number_format($item['user_rating'], 1) ?>/10</span>
                <?php endif; ?>
              </div>
              <div class="added-date">
                Added <?= htmlspecialchars(date('M d, Y', strtotime($item['added_date']))) ?>
              </div>
              <a href="watch.php?series_id=<?= $item['anime_series_id'] ?>" class="btn">Continue Watching</a>
              <button class="btn remove-watchlist-btn" data-anime-id="<?= $item['anime_series_id'] ?>">
                <i class="fa fa-trash"></i> Remove
              </button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
<<<<<<< Updated upstream
</header>

<section class="anime" id="anime">
  <h1 class="heading"><i class="fa-solid fa-heart"></i>Favorites</h1>
  <hr />
  <div class="swiper anime-slider">
    <div class="swiper-wrapper">

      <div class="swiper-slide">
        <div class="box" style="background-image: url('Assets/images/dbz.jpg');"></div>
        <div class="content">
          <h3>Aho-Girl</h3>
          <p>Yoshiko Hanabatake is an idiot beyond all belief. <br>
            Somehow managing to consistently score zeroes on <br>
            all and consumed by an absurd obsession with bananas, <br>
            her senseless acts have caused even her own mother to lose all hope.</p>
          <a href="#" class="btn">Watch</a>
          
        </div>
      </div>

      <div class="swiper-slide">
        <div class="box" style="background-image: url('Assets/images/dbz.jpg');"></div>
        <div class="content">
          <h3>Shadows House</h3>
          <p>Yoshiko Hanabatake is an idiot beyond all belief. <br>
            Somehow managing to consistently score zeroes on <br>
            all and consumed by an absurd obsession with bananas, <br>
            her senseless acts have caused even her own mother to lose all hope.</p>
          <a href="#" class="btn">Watch</a>
          
        </div>
      </div>

      <div class="swiper-slide">
        <div class="box" style="background-image: url('Assets/images/dbz.jpg');"></div>
        <div class="content">
          <h3>Shadows House</h3>
          <p>Yoshiko Hanabatake is an idiot beyond all belief. <br>
            Somehow managing to consistently score zeroes on <br>
            all and consumed by an absurd obsession with bananas, <br>
            her senseless acts have caused even her own mother to lose all hope.</p>
          <a href="#" class="btn">Watch</a>
          <div class="like-container">
            <button class="like-btn" aria-label="Like button">
              <i class="fa-regular fa-heart"></i>
              <i class="fa-solid fa-heart liked"></i>
            </button>
            <span class="like-count">0</span>
          </div>
        </div>
      </div>

      <div class="swiper-slide">
        <div class="box" style="background-image: url('Assets/images/dbz.jpg');"></div>
        <div class="content">
          <h3>Shadows House</h3>
         <p>Yoshiko Hanabatake is an idiot beyond all belief. <br>
            Somehow managing to consistently score zeroes on <br>
            all and consumed by an absurd obsession with bananas, <br>
            her senseless acts have caused even her own mother to lose all hope.</p>
          <a href="#" class="btn">Watch</a>
          <div class="like-container">
            <button class="like-btn" aria-label="Like button">
              <i class="fa-regular fa-heart"></i>
              <i class="fa-solid fa-heart liked"></i>
            </button>
            <span class="like-count">0</span>
          </div>
        </div>
      </div>


      <div class="swiper-slide">
        <div class="box" style="background-image: url('Assets/images/dbz.jpg');"></div>
        <div class="content">
          <h3>Shadows House</h3>
         <p>Yoshiko Hanabatake is an idiot beyond all belief. <br>
            Somehow managing to consistently score zeroes on <br>
            all and consumed by an absurd obsession with bananas, <br>
            her senseless acts have caused even her own mother to lose all hope.</p>
          <a href="#" class="btn">Watch</a>
          <div class="like-container">
            <button class="like-btn" aria-label="Like button">
              <i class="fa-regular fa-heart"></i>
              <i class="fa-solid fa-heart liked"></i>
            </button>
            <span class="like-count">0</span>
          </div>
        </div>
      </div>


    </div>
  </div>
</section>

    <?php include 'Assets/HTML/footer.html' ?>
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
=======
  <?php include 'Assets/HTML/footer.html'; ?>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.remove-watchlist-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          if (!confirm('Remove this anime from your watchlist?')) return;
          const animeId = this.getAttribute('data-anime-id');
          const card = this.closest('.watchlist-card');
          btn.disabled = true;
          fetch('remove_from_watchlist.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
              body: 'anime_id=' + encodeURIComponent(animeId)
            })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                card.style.opacity = '0.5';
                setTimeout(() => card.remove(), 350);
              } else {
                alert(data.message || 'Could not remove from watchlist.');
                btn.disabled = false;
              }
            })
            .catch(() => {
              alert('Error removing. Please try again.');
              btn.disabled = false;
            });
        });
      });
    });
  </script>
>>>>>>> Stashed changes
</body>

</html>