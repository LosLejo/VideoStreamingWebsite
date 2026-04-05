<?php
// insert_anime.php - User-friendly Anime Insertion UI
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['populate_genres'])) {
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
        $message = "<div class='success-message'>✅ Anime and episodes inserted successfully!</div>";
    } else {
        $message = "<div class='error-message'>❌ Insert failed or anime already exists.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=5.0">
    <title>Insert Anime - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        :root {
            --primary-color: #007cba;
            --primary-dark: #005a87;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --warning-dark: #ff9800;
            --light-bg: #f8f9fa;
            --white: #ffffff;
            --border-color: #dee2e6;
            --text-color: #495057;
            --shadow: 0 0.2rem 1.2rem rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--light-bg);
            color: var(--text-color);
            line-height: 1.6;
            padding: 2rem 1rem;
        }

        .container {
            max-width: 80rem;
            margin: 0 auto;
            background: var(--white);
            padding: 3rem;
            border-radius: 1rem;
            box-shadow: var(--shadow);
        }

        h2 {
            margin-bottom: 2rem;
            color: var(--primary-color);
            font-size: 2.4rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        h3 {
            margin: 2rem 0 1rem 0;
            color: var(--primary-color);
            font-size: 2rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        /* Message Styles */
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid #c3e6cb;
            margin-bottom: 2rem;
            font-weight: 500;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid #f5c6cb;
            margin-bottom: 2rem;
            font-weight: 500;
        }

        /* Form Styles */
        label {
            font-weight: 600;
            display: block;
            margin: 1.2rem 0 0.4rem;
            color: var(--text-color);
            font-size: 1.4rem;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--border-color);
            border-radius: 0.5rem;
            font-size: 1.4rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            background: var(--white);
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.3rem rgba(0, 124, 186, 0.1);
        }

        textarea {
            min-height: 10rem;
            resize: vertical;
            font-family: inherit;
        }

        /* Layout Styles */
        .row {
            display: flex;
            gap: 2rem;
            margin: 1rem 0;
        }

        .row>div {
            flex: 1;
        }

        .col-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .col-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 2rem;
        }

        /* Episodes Section */
        .episodes {
            margin: 2rem 0;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 0.8rem;
            border: 1px solid var(--border-color);
        }

        .episodes h4 {
            margin-bottom: 1.5rem;
            color: var(--primary-color);
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .ep-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            align-items: center;
            background: var(--white);
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid var(--border-color);
        }

        .ep-row input[type="text"]:first-child {
            flex: 2;
        }

        .ep-row input[type="text"]:last-of-type {
            flex: 3;
        }

        .ep-row .remove-btn {
            background: var(--danger-color);
            color: var(--white);
            border: none;
            border-radius: 0.4rem;
            padding: 0.8rem 1rem;
            cursor: pointer;
            font-size: 1.2rem;
            transition: background 0.3s ease;
            flex-shrink: 0;
        }

        .ep-row .remove-btn:hover {
            background: #c82333;
        }

        /* Button Styles */
        .btn {
            border: none;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            font-size: 1.4rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.8rem;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary-color);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-0.1rem);
        }

        .btn-warning {
            background: var(--warning-color);
            color: #333;
        }

        .btn-warning:hover {
            background: var(--warning-dark);
            transform: translateY(-0.1rem);
        }

        .btn-success {
            background: var(--success-color);
            color: var(--white);
        }

        .btn-success:hover {
            background: #218838;
            transform: translateY(-0.1rem);
        }

        #add-ep-btn {
            margin: 1.5rem 0;
        }

        /* Checkbox Styles */
        .checkbox-group {
            display: flex;
            gap: 2rem;
            margin: 1rem 0;
            align-items: center;
        }

        .checkbox-label {
            font-weight: 400;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            font-size: 1.4rem;
            cursor: pointer;
        }

        .checkbox-label input[type="checkbox"] {
            width: auto;
            margin: 0;
            transform: scale(1.2);
        }

        /* Table Styles */
        .summary-table {
            margin-top: 4rem;
            overflow-x: auto;
        }

        .summary-table table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            background: var(--white);
            border-radius: 0.8rem;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .summary-table th,
        .summary-table td {
            padding: 1.2rem 1.5rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .summary-table th {
            background: var(--primary-color);
            color: var(--white);
            font-weight: 600;
            font-size: 1.3rem;
        }

        .summary-table td {
            background: var(--white);
            font-size: 1.3rem;
        }

        .summary-table tr:hover td {
            background: #f8f9fa;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 1rem 0.5rem;
            }

            .container {
                padding: 2rem 1.5rem;
                margin: 0 0.5rem;
            }

            h2 {
                font-size: 2rem;
                flex-direction: column;
                text-align: center;
            }

            .row {
                flex-direction: column;
                gap: 1rem;
            }

            .col-2,
            .col-3 {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .checkbox-group {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .ep-row {
                flex-direction: column;
                gap: 1rem;
            }

            .ep-row input {
                flex: none !important;
            }

            .summary-table {
                font-size: 1.2rem;
            }

            .summary-table th,
            .summary-table td {
                padding: 0.8rem 1rem;
            }

            .btn {
                width: 100%;
                justify-content: center;
                margin: 0.5rem 0;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 1.5rem 1rem;
            }

            h2 {
                font-size: 1.8rem;
            }

            h3 {
                font-size: 1.6rem;
            }

            input[type="text"],
            input[type="number"],
            input[type="date"],
            textarea {
                font-size: 1.6rem;
                padding: 1.2rem;
            }

            .summary-table {
                font-size: 1.1rem;
            }
        }

        /* Loading state */
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Focus styles for accessibility */
        .btn:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        /* Print styles */
        @media print {
            .btn {
                display: none;
            }

            .container {
                box-shadow: none;
                padding: 1rem;
            }
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
                <button type="button" class="remove-btn" onclick="this.parentElement.remove()">
                    <i class="fas fa-trash"></i>
                </button>
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
                    $url = $_POST['ep_url'][$i] ?? '';
            ?>
                    addEpisodeRow("<?= htmlspecialchars($title) ?>", "<?= htmlspecialchars($url) ?>");
                <?php endforeach;
            else: ?>
                addEpisodeRow();
            <?php endif; ?>
        }

        // Form validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form[method="post"]');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const requiredFields = form.querySelectorAll('[required]');
                    let isValid = true;

                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            field.style.borderColor = 'var(--danger-color)';
                            isValid = false;
                        } else {
                            field.style.borderColor = 'var(--border-color)';
                        }
                    });

                    if (!isValid) {
                        e.preventDefault();
                        alert('Please fill in all required fields.');
                    }
                });
            }
        });
    </script>
</head>

<body>
    <div class="container">
        <h2>
            <i class="fas fa-film"></i>
            Insert New Anime
        </h2>

        <?php if (!empty($message)) echo $message; ?>

        <!-- Populate Genres Button -->
        <form action="populate_genres.php" method="post" style="display:inline;">
            <button type="submit" class="btn btn-warning">
                <i class="fas fa-sync-alt"></i>
                Populate Genres
            </button>
        </form>

        <form action="" method="post" autocomplete="off" style="margin-top:2rem;">
            <label>Title *</label>
            <input type="text" name="title" required value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">

            <label>Description *</label>
            <textarea name="description" required
                placeholder="Enter anime description..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>

            <div class="col-2">
                <div>
                    <label>Thumbnail URL *</label>
                    <input type="text" name="thumbnail" required placeholder="https://example.com/image.jpg"
                        value="<?= htmlspecialchars($_POST['thumbnail'] ?? '') ?>">
                </div>
                <div>
                    <label>Release Date *</label>
                    <input type="date" name="release_date" required
                        value="<?= htmlspecialchars($_POST['release_date'] ?? '') ?>">
                </div>
            </div>

            <div class="col-3">
                <div>
                    <label>Genre *</label>
                    <input type="text" name="genre" required placeholder="Action, Adventure, etc."
                        value="<?= htmlspecialchars($_POST['genre'] ?? '') ?>">
                </div>
                <div>
                    <label>Status *</label>
                    <input type="text" name="status" required placeholder="Ongoing, Completed, etc."
                        value="<?= htmlspecialchars($_POST['status'] ?? '') ?>">
                </div>
                <div>
                    <label>Rating (0-10)</label>
                    <input type="number" step="0.1" min="0" max="10" name="rating" placeholder="8.5"
                        value="<?= htmlspecialchars($_POST['rating'] ?? '0') ?>">
                </div>
            </div>

            <div class="row">
                <div>
                    <label>Total Episodes *</label>
                    <input type="number" name="total_episodes" min="1" required placeholder="12"
                        value="<?= htmlspecialchars($_POST['total_episodes'] ?? '1') ?>">
                </div>
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_featured" <?= isset($_POST['is_featured']) ? 'checked' : '' ?>>
                        <i class="fas fa-star"></i> Featured
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_new_release"
                            <?= isset($_POST['is_new_release']) ? 'checked' : '' ?>>
                        <i class="fas fa-sparkles"></i> New Release
                    </label>
                </div>
            </div>

            <div class="episodes">
                <h4>
                    <i class="fas fa-list"></i>
                    Episodes
                </h4>
                <div id="episodes-container"></div>
                <button type="button" id="add-ep-btn" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Add Episode
                </button>
            </div>

            <button type="submit" class="btn btn-success"
                style="margin-top:2rem; font-size:1.6rem; padding:1.2rem 3rem;">
                <i class="fas fa-save"></i>
                Insert Anime
            </button>
        </form>

        <!-- Show summary table -->
        <div class="summary-table">
            <h3>
                <i class="fas fa-table"></i>
                Current Anime in Database
            </h3>
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
                LIMIT 20
            ");
            if ($result && $result->num_rows > 0) {
                echo "<table>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>ID</th><th>Title</th><th>Status</th><th>Episodes (Total/Added)</th><th>Rating</th><th>Featured</th><th>New Release</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td><strong>" . htmlspecialchars($row['title']) . "</strong></td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "<td>{$row['total_episodes']} / {$row['actual_episodes']}</td>";
                    echo "<td>{$row['rating']}/10</td>";
                    echo "<td>" . ($row['is_featured'] ? '<i class="fas fa-star" style="color: gold;"></i> Yes' : 'No') . "</td>";
                    echo "<td>" . ($row['is_new_release'] ? '<i class="fas fa-sparkles" style="color: #00d4ff;"></i> Yes' : 'No') . "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<p style='text-align: center; font-style: italic; color: #6c757d; padding: 2rem;'>No anime found in database.</p>";
            }
            ?>
        </div>
    </div>
</body>

</html>