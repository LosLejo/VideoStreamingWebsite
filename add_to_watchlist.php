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

// Check if already in watchlist
$stmt = $mysqli->prepare("SELECT id FROM user_watchlist WHERE user_id=? AND anime_series_id=?");
$stmt->bind_param("ii", $user_id, $anime_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Already in watchlist']);
    $stmt->close();
    $mysqli->close();
    exit;
}
$stmt->close();

// Insert into watchlist
$stmt = $mysqli->prepare("INSERT INTO user_watchlist (user_id, anime_series_id) VALUES (?, ?)");
$stmt->bind_param("ii", $user_id, $anime_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
$stmt->close();
$mysqli->close();
