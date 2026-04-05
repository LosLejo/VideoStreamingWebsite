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
      background: var(--black) !important;
      color: #fff !important;
      font-family: 'Nunito', Arial, sans-serif;
      margin: 0;
      min-height: 100vh;
      padding-top: 8rem;
    }

    .container {
      max-width: 120rem;
      margin: 2rem auto;
      padding: 0 2rem;
    }

    .watchlist-heading {
      font-size: 3rem;
      color: var(--yellow);
      font-weight: bold;
      margin-bottom: 2rem;
      text-align: center;
      letter-spacing: 0.1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 1rem;
    }

    .watchlist-heading i {
      font-size: 2.8rem;
    }

    .watchlist-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(28rem, 1fr));
      gap: 2.5rem;
      margin-top: 3rem;
    }

    .watchlist-card {
      background: #1a1a1a;
      border-radius: 1.5rem;
      overflow: hidden;
      box-shadow: 0 0.8rem 2.4rem rgba(0, 0, 0, 0.3);
      display: flex;
      flex-direction: column;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      position: relative;
      border: 0.1rem solid rgba(255, 255, 255, 0.1);
    }

    .watchlist-card:hover {
      transform: translateY(-0.8rem) scale(1.02);
      box-shadow: 0 1.2rem 3.6rem rgba(255, 247, 0, 0.2), 0 0.8rem 2.4rem rgba(0, 0, 0, 0.4);
      border-color: var(--yellow);
    }

    .watchlist-thumb {
      width: 100%;
      height: 35rem;
      object-fit: cover;
      background: linear-gradient(135deg, #333, #444);
      display: block;
    }

    .card-content {
      padding: 2rem;
      display: flex;
      flex-direction: column;
      flex: 1;
      gap: 1rem;
    }

    .watchlist-title {
      margin: 0;
      font-size: 1.8rem;
      font-weight: bold;
      color: #fff;
      line-height: 1.3;
      overflow: hidden;
      text-overflow: ellipsis;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
    }

    .watchlist-meta {
      font-size: 1.4rem;
      color: #b3b3b3;
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      align-items: center;
    }

    .watchlist-status {
      color: var(--yellow);
      font-weight: bold;
      font-size: 1.4rem;
      background: rgba(255, 247, 0, 0.1);
      padding: 0.4rem 0.8rem;
      border-radius: 0.5rem;
    }

    .watchlist-progress {
      font-size: 1.3rem;
      color: #b3b3b3;
    }

    .watchlist-rating {
      display: flex;
      align-items: center;
      font-size: 1.4rem;
      gap: 0.5rem;
    }

    .watchlist-rating .fa-star {
      color: #ffd700;
    }

    .user-rating-badge {
      background: var(--yellow);
      color: var(--black);
      font-size: 1.2rem;
      font-weight: bold;
      border-radius: 0.8rem;
      padding: 0.4rem 0.8rem;
      display: inline-block;
    }

    .button-group {
      display: flex;
      gap: 1rem;
      margin-top: 1rem;
    }

    .btn {
      flex: 1;
      background: var(--yellow) !important;
      color: var(--black) !important;
      font-weight: bold;
      border-radius: 0.8rem;
      padding: 1.2rem 1.5rem;
      border: none;
      font-size: 1.3rem;
      text-align: center;
      transition: all 0.3s ease;
      cursor: pointer;
      text-decoration: none;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      min-height: 4.8rem;
    }

    .btn:hover {
      background: #fff !important;
      transform: translateY(-0.2rem);
    }

    .btn.remove-watchlist-btn {
      background: #dc3545 !important;
      color: #fff !important;
    }

    .btn.remove-watchlist-btn:hover {
      background: #c82333 !important;
      color: #fff !important;
    }

    .added-date {
      font-size: 1.2rem;
      color: #888;
      font-style: italic;
    }

    /* Empty state */
    .empty-watchlist {
      margin: 8rem auto;
      padding: 4rem 2rem;
      text-align: center;
      background: rgba(26, 26, 26, 0.9);
      border-radius: 2rem;
      max-width: 60rem;
      box-shadow: 0 1.6rem 3.2rem rgba(0, 0, 0, 0.3);
      border: 0.1rem solid rgba(255, 255, 255, 0.1);
    }

    .empty-watchlist i {
      font-size: 6rem;
      color: var(--yellow);
      margin-bottom: 2rem;
    }

    .empty-watchlist h3 {
      color: var(--yellow);
      margin-bottom: 1.5rem;
      font-size: 2.4rem;
    }

    .empty-watchlist p {
      color: #b3b3b3;
      font-size: 1.6rem;
      line-height: 1.6;
      margin-bottom: 2rem;
    }

    .empty-watchlist .btn {
      display: inline-flex;
      width: auto;
      min-width: 20rem;
    }

    /* Mobile Responsive */
    @media (max-width: 76.8rem) {
      body {
        padding-top: 12rem;
      }

      .container {
        padding: 0 1.5rem;
      }

      .watchlist-heading {
        font-size: 2.4rem;
        margin-bottom: 1.5rem;
      }

      .watchlist-grid {
        grid-template-columns: repeat(auto-fit, minmax(25rem, 1fr));
        gap: 2rem;
        margin-top: 2rem;
      }

      .watchlist-thumb {
        height: 30rem;
      }

      .card-content {
        padding: 1.5rem;
        gap: 0.8rem;
      }

      .watchlist-title {
        font-size: 1.6rem;
      }

      .watchlist-meta {
        font-size: 1.2rem;
        gap: 0.8rem;
      }

      .watchlist-status {
        font-size: 1.2rem;
        padding: 0.3rem 0.6rem;
      }

      .btn {
        padding: 1rem 1.2rem;
        font-size: 1.2rem;
        min-height: 4.4rem;
      }

      .button-group {
        flex-direction: column;
        gap: 0.8rem;
      }

      .empty-watchlist {
        margin: 4rem auto;
        padding: 3rem 1.5rem;
      }

      .empty-watchlist i {
        font-size: 4rem;
      }

      .empty-watchlist h3 {
        font-size: 2rem;
      }

      .empty-watchlist p {
        font-size: 1.4rem;
      }
    }

    @media (max-width: 48rem) {
      body {
        padding-top: 14rem;
      }

      .container {
        padding: 0 1rem;
      }

      .watchlist-heading {
        font-size: 2rem;
        flex-direction: column;
        gap: 0.5rem;
      }

      .watchlist-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
      }

      .watchlist-card {
        border-radius: 1rem;
      }

      .watchlist-thumb {
        height: 25rem;
      }

      .card-content {
        padding: 1.2rem;
      }

      .watchlist-title {
        font-size: 1.4rem;
      }

      .btn {
        padding: 0.8rem 1rem;
        font-size: 1.1rem;
        min-height: 4rem;
      }

      .empty-watchlist {
        margin: 3rem auto;
        padding: 2rem 1rem;
        border-radius: 1.5rem;
      }

      .empty-watchlist i {
        font-size: 3rem;
        margin-bottom: 1.5rem;
      }

      .empty-watchlist h3 {
        font-size: 1.8rem;
        margin-bottom: 1rem;
      }

      .empty-watchlist p {
        font-size: 1.2rem;
      }
    }

    @media (max-width: 32rem) {
      body {
        padding-top: 16rem;
      }

      .container {
        padding: 0 0.5rem;
      }

      .watchlist-heading {
        font-size: 1.8rem;
      }

      .watchlist-grid {
        gap: 1rem;
      }

      .watchlist-thumb {
        height: 20rem;
      }

      .card-content {
        padding: 1rem;
        gap: 0.6rem;
      }

      .watchlist-title {
        font-size: 1.3rem;
      }

      .watchlist-meta {
        font-size: 1.1rem;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
      }

      .btn {
        padding: 0.7rem 0.8rem;
        font-size: 1rem;
        min-height: 3.6rem;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="watchlist-heading">
      <i class="fa-solid fa-star"></i>
      Your Watchlist
    </div>

    <?php if (count($watchlist) === 0): ?>
      <div class="empty-watchlist">
        <i class="fa-regular fa-face-sad-tear"></i>
        <h3>No Anime in Watchlist</h3>
        <p>You haven't added any anime to your watchlist yet.<br>
          Start browsing and add your favorites!</p>
        <a href="home.php" class="btn">
          <i class="fa-solid fa-home"></i>
          Browse Anime
        </a>
      </div>
    <?php else: ?>
      <div class="watchlist-grid">
        <?php foreach ($watchlist as $item): ?>
          <div class="watchlist-card">
            <img src="<?= htmlspecialchars($item['thumbnail']) ?>" alt="<?= htmlspecialchars($item['title']) ?>"
              class="watchlist-thumb" loading="lazy" />
            <div class="card-content">
              <div class="watchlist-title"><?= htmlspecialchars($item['title']) ?></div>

              <div class="watchlist-meta">
                <span class="watchlist-status"><?= ucfirst(htmlspecialchars($item['status'])) ?></span>
                <span class="watchlist-progress">
                  Episode <?= htmlspecialchars($item['current_episode']) ?> /
                  <?= htmlspecialchars($item['total_episodes']) ?>
                </span>
              </div>

              <div class="watchlist-rating">
                <i class="fa fa-star"></i>
                <span><?= number_format($item['anime_rating'], 1) ?>/10</span>
                <?php if ($item['user_rating'] !== null): ?>
                  <span class="user-rating-badge">Your Rating:
                    <?= number_format($item['user_rating'], 1) ?>/10</span>
                <?php endif; ?>
              </div>

              <div class="added-date">
                Added on <?= htmlspecialchars(date('M d, Y', strtotime($item['added_date']))) ?>
              </div>

              <div class="button-group">
                <a href="watch.php?series_id=<?= $item['anime_series_id'] ?>" class="btn">
                  <i class="fa-solid fa-play"></i>
                  Continue Watching
                </a>
                <button class="btn remove-watchlist-btn" data-anime-id="<?= $item['anime_series_id'] ?>">
                  <i class="fa fa-trash"></i>
                  Remove
                </button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.remove-watchlist-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          if (!confirm('Remove this anime from your watchlist?')) return;

          const animeId = this.getAttribute('data-anime-id');
          const card = this.closest('.watchlist-card');
          btn.disabled = true;
          btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Removing...';

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
                card.style.transform = 'scale(0.8)';
                card.style.opacity = '0';
                setTimeout(() => {
                  card.remove();
                  // Check if no cards remain
                  if (document.querySelectorAll('.watchlist-card')
                    .length === 0) {
                    location.reload();
                  }
                }, 300);
              } else {
                alert(data.message || 'Could not remove from watchlist.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-trash"></i> Remove';
              }
            })
            .catch(() => {
              alert('Error removing. Please try again.');
              btn.disabled = false;
              btn.innerHTML = '<i class="fa fa-trash"></i> Remove';
            });
        });
      });
    });
  </script>
</body>

<?php include 'Assets/HTML/footer.html' ?>

</html>