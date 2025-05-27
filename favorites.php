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
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
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
      max-width: 12rem;
      margin: 4rem auto 2rem auto;
      padding: 0 2rem;
    }

    .watchlist-heading {
      font-size: 2.4rem;
      color: yellow;
      font-weight: bold;
      margin-bottom: 1.5rem;
      text-align: left;
      letter-spacing: 0.1rem;
    }

    .watchlist-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(23.5rem, 1fr));
      gap: 2rem;
      margin-top: 2rem;
    }

    .watchlist-card {
      background: #181818;
      border-radius: 1.2rem;
      overflow: hidden;
      box-shadow: 0 4rem 20rem 0 rgba(0, 0, 0, 0.25);
      display: flex;
      flex-direction: column;
      transition: transform 0.22s, box-shadow 0.22s;
      position: relative;
    }

    .watchlist-card:hover {
      transform: translateY(-0.8rem) scale(1.03);
      box-shadow: 0 0.8rem 3.4rem 0 rgba(255, 255, 0, 0.12), 0 8rem 30rem rgba(0, 0, 0, 0.4);
      z-index: 2;
    }

    .watchlist-thumb {
      width: 100%;
      height: 32rem;
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
      letter-spacing: 0.5rem;
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
      letter-spacing: 0.8rem;
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
      max-width: 42rem;
      box-shadow: 0 2rem 10rem rgba(0, 0, 0, 0.22);
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
    @media (max-width: 80rem) {
      .container {
        padding: 0 0.7rem;
      }

      .watchlist-grid {
        gap: 1.1rem;
      }

      .watchlist-thumb {
        height: 22rem;
      }
    }

    @media (max-width: 48rem) {
      .watchlist-thumb {
        height: 16.5rem;
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
  </section>


  <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

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
</body>
<?php include 'Assets/HTML/footer.html' ?>

</html>