<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$anime_id = isset($_POST['anime_id']) ? intval($_POST['anime_id']) : 0;

if (!$anime_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid anime ID']);
    exit;
}

$stmt = $mysqli->prepare("DELETE FROM user_watchlist WHERE user_id=? AND anime_series_id=?");
$stmt->bind_param("ii", $user_id, $anime_id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Anime not found in your watchlist']);
}
$stmt->close();
$mysqli->close();
