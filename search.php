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
    <title>Search Results for <?= htmlspecialchars($q) ?> - StrikeFlix</title>
    <link rel="stylesheet" href="Assets/css/style.css">
</head>

<body>
    <?php include 'Assets/HTML/home-header.php'; ?>
    <div class="container">
        <h2>Search Results for: <em><?= htmlspecialchars($q) ?></em></h2>
        <?php if (empty($results)): ?>
            <p>No anime found.</p>
        <?php else: ?>
            <div class="anime-search-results">
                <?php foreach ($results as $anime): ?>
                    <a class="anime-search-result" href="watch.php?series_id=<?= $anime['id'] ?>">
                        <img src="<?= htmlspecialchars($anime['thumbnail'] ?: 'Assets/images/placeholder.png') ?>"
                            alt="<?= htmlspecialchars($anime['title']) ?>" width="70" height="100">
                        <span><?= htmlspecialchars($anime['title']) ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <style>
        .anime-search-results {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            margin-top: 2rem;
        }

        .anime-search-result {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #181818;
            border-radius: 8px;
            padding: 1rem;
            text-decoration: none;
            color: #ffe452;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.14);
            transition: background 0.2s;
        }

        .anime-search-result:hover {
            background: #222;
        }

        .anime-search-result img {
            border-radius: 4px;
            margin-bottom: 0.5rem;
        }
    </style>
</body>

</html>