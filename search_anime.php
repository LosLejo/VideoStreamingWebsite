<?php
require_once 'db.php';
header('Content-Type: application/json');

$query = trim($_GET['q'] ?? '');
if (strlen($query) < 1) {
    echo json_encode([]);
    exit;
}

$stmt = $mysqli->prepare("SELECT id, title, thumbnail FROM anime_series WHERE title LIKE CONCAT('%', ?, '%') ORDER BY title LIMIT 10");
$stmt->bind_param("s", $query);
$stmt->execute();
$result = $stmt->get_result();

$animes = [];
while ($row = $result->fetch_assoc()) {
    $animes[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'thumbnail' => $row['thumbnail']
    ];
}
echo json_encode($animes);
