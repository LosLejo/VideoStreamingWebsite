<?php
// insert_anime.php - User-friendly Anime Insertion UI
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $thumbnail = $_POST['thumbnail'] ?? '';
    $total_episodes = (int)($_POST['total_episodes'] ?? 0);
    $status = $_POST['status'] ?? '';
    $genre = $_POST['genre'] ?? '';
    $release_date = $_POST['release_date'] ?? '';
    $rating = floatval($_POST['rating'] ?? 0);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_new_release = isset($_POST['is_new_release']) ? 1 : 0;

    // Prepare and insert anime
    $stmt = $mysqli->prepare("INSERT IGNORE INTO anime_series (title, description, thumbnail, total_episodes, status, genre, release_date, rating, is_featured, is_new_release) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Error preparing statement: " . $mysqli->error);
    }
    $stmt->bind_param(
        "sssisssdii",
        $title,
        $description,
        $thumbnail,
        $total_episodes,
        $status,
        $genre,
        $release_date,
        $rating,
        $is_featured,
        $is_new_release
    );
    $success = $stmt->execute();
    $animeId = $mysqli->insert_id;
    $stmt->close();

    if ($success && $animeId) {
        // Insert into display sections
        $sections = [['section' => 'browse', 'order' => rand(1, 10)]];
        if ($is_featured) {
            $sections[] = ['section' => 'home_slider', 'order' => rand(1, 5)];
            $sections[] = ['section' => 'featured', 'order' => rand(1, 5)];
        }
        if ($is_new_release) {
            $sections[] = ['section' => 'new_releases', 'order' => rand(1, 5)];
        }
        $sectionStmt = $mysqli->prepare("INSERT IGNORE INTO display_sections (anime_series_id, section_type, display_order) VALUES (?, ?, ?)");
        foreach ($sections as $section) {
            $sectionStmt->bind_param("isi", $animeId, $section['section'], $section['order']);
            $sectionStmt->execute();
        }
        $sectionStmt->close();

        // Episodes
        if (!empty($_POST['ep_title']) && is_array($_POST['ep_title'])) {
            $episodeStmt = $mysqli->prepare("INSERT IGNORE INTO episodes (anime_series_id, episode_number, episode_title, video_url) VALUES (?, ?, ?, ?)");
            $episodeNumber = 1;
            foreach ($_POST['ep_title'] as $i => $epTitle) {
                $epTitle = trim($epTitle);
                $epUrl = trim($_POST['ep_url'][$i]);
                if ($epTitle !== '') {
                    $episodeStmt->bind_param("iiss", $animeId, $episodeNumber, $epTitle, $epUrl);
                    $episodeStmt->execute();
                    $episodeNumber++;
                }
            }
            $episodeStmt->close();
        }
        $message = "<div style='background:#e8f5e8;padding:10px;border-radius:5px;'>✅ Anime and episodes inserted successfully!</div>";
    } else {
        $message = "<div style='background:#ffdddd;padding:10px;border-radius:5px;'>❌ Insert failed or anime already exists.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Insert Anime - Admin</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f8f9fa;
            padding: 0 0 40px 0;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 12px #0002;
        }

        h2 {
            margin-top: 0;
            color: #007cba;
        }

        label {
            font-weight: 500;
            display: block;
            margin: 12px 0 4px;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        textarea {
            min-height: 60px;
        }

        .row {
            display: flex;
            gap: 16px;
        }

        .row>div {
            flex: 1;
        }

        .episodes {
            margin: 18px 0;
        }

        .ep-row {
            display: flex;
            gap: 10px;
            margin-bottom: 7px;
        }

        .ep-row input {
            flex: 2;
        }

        .ep-row input[type="text"]:last-child {
            flex: 3;
        }

        .ep-row button {
            background: #e33;
            color: #fff;
            border: none;
            border-radius: 3px;
            padding: 0 8px;
            cursor: pointer;
        }

        .ep-row button:hover {
            background: #c00;
        }

        #add-ep-btn {
            margin: 10px 0;
            background: #007cba;
            color: #fff;
            border: none;
            padding: 6px 20px;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
        }

        #add-ep-btn:hover {
            background: #005a87;
        }

        .checkbox-label {
            font-weight: 400;
        }

        .checkbox-group {
            display: flex;
            gap: 18px;
            margin: 10px 0;
        }

        .summary-table {
            margin-top: 40px;
        }

        .summary-table th,
        .summary-table td {
            padding: 6px 10px;
        }

        .summary-table th {
            background: #f0f0f0;
        }

        .summary-table td {
            background: #fbfbfb;
        }
    </style>
    <script>
        function addEpisodeRow(title = '', url = '') {
            const container = document.getElementById('episodes-container');
            const div = document.createElement('div');
            div.className = 'ep-row';
            div.innerHTML = `
            <input type="text" name="ep_title[]" placeholder="Episode Title" value="${title.replace(/"/g, '&quot;')}" required>
            <input type="text" name="ep_url[]" placeholder="Episode Video URL (optional)" value="${url.replace(/"/g, '&quot;')}">
            <button type="button" onclick="this.parentElement.remove()">✖</button>
        `;
            container.appendChild(div);
        }
        window.onload = function() {
            document.getElementById('add-ep-btn').onclick = function() {
                addEpisodeRow();
            };
            // If page is loaded after error, preserve episode fields
            <?php if (!empty($_POST['ep_title'])):
                foreach ($_POST['ep_title'] as $i => $title):
                    $url = $_POST['ep_url'][$i];
            ?>
                    addEpisodeRow("<?= htmlspecialchars($title) ?>", "<?= htmlspecialchars($url) ?>");
                <?php endforeach;
            else: ?>
                addEpisodeRow();
            <?php endif; ?>
        }
    </script>
</head>

<body>
    <div class="container">
        <h2>🎬 Insert New Anime</h2>
        <?php if (!empty($message)) echo $message; ?>
        <form action="" method="post" autocomplete="off">
            <label>Title *</label>
            <input type="text" name="title" required value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">

            <label>Description *</label>
            <textarea name="description" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>

            <div class="row">
                <div>
                    <label>Thumbnail URL *</label>
                    <input type="text" name="thumbnail" required
                        value="<?= htmlspecialchars($_POST['thumbnail'] ?? '') ?>">
                </div>
                <div>
                    <label>Release Date *</label>
                    <input type="date" name="release_date" required
                        value="<?= htmlspecialchars($_POST['release_date'] ?? '') ?>">
                </div>
            </div>
            <div class="row">
                <div>
                    <label>Genre *</label>
                    <input type="text" name="genre" required value="<?= htmlspecialchars($_POST['genre'] ?? '') ?>">
                </div>
                <div>
                    <label>Status *</label>
                    <input type="text" name="status" required value="<?= htmlspecialchars($_POST['status'] ?? '') ?>">
                </div>
                <div>
                    <label>Rating (0-10)</label>
                    <input type="number" step="0.1" min="0" max="10" name="rating"
                        value="<?= htmlspecialchars($_POST['rating'] ?? '0') ?>">
                </div>
            </div>
            <div class="row">
                <div>
                    <label>Total Episodes *</label>
                    <input type="number" name="total_episodes" min="1" required
                        value="<?= htmlspecialchars($_POST['total_episodes'] ?? '1') ?>">
                </div>
                <div class="checkbox-group">
                    <label class="checkbox-label"><input type="checkbox" name="is_featured"
                            <?= isset($_POST['is_featured']) ? 'checked' : '' ?>> Featured</label>
                    <label class="checkbox-label"><input type="checkbox" name="is_new_release"
                            <?= isset($_POST['is_new_release']) ? 'checked' : '' ?>> New Release</label>
                </div>
            </div>
            <div class="episodes">
                <label>Episodes</label>
                <div id="episodes-container"></div>
                <button type="button" id="add-ep-btn">+ Add Episode</button>
            </div>
            <button type="submit"
                style="margin-top:18px;background:#007cba;color:#fff;border:none;padding:10px 30px;font-size:1.1em;border-radius:5px;cursor:pointer;">Insert
                Anime</button>
        </form>

        <!-- Show summary table -->
        <div class="summary-table">
            <h3>📋 Current Anime in Database</h3>
            <?php
            $result = $mysqli->query("
            SELECT 
                a.id, 
                a.title, 
                a.status, 
                a.total_episodes, 
                a.rating, 
                a.is_featured, 
                a.is_new_release,
                COUNT(e.id) as actual_episodes
            FROM anime_series a 
            LEFT JOIN episodes e ON a.id = e.anime_series_id 
            GROUP BY a.id 
            ORDER BY a.id DESC
        ");
            if ($result && $result->num_rows > 0) {
                echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                echo "<tr>";
                echo "<th>ID</th><th>Title</th><th>Status</th><th>Episodes (Total/Added)</th><th>Rating</th><th>Featured</th><th>New Release</th>";
                echo "</tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td><strong>{$row['title']}</strong></td>";
                    echo "<td>{$row['status']}</td>";
                    echo "<td>{$row['total_episodes']} / {$row['actual_episodes']}</td>";
                    echo "<td>{$row['rating']}/10</td>";
                    echo "<td>" . ($row['is_featured'] ? '⭐ Yes' : 'No') . "</td>";
                    echo "<td>" . ($row['is_new_release'] ? '🆕 Yes' : 'No') . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No anime found in database.</p>";
            }
            ?>
        </div>
    </div>
</body>

</html>