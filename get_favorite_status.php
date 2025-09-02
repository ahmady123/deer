<?php
session_start();
include("connect.php");

if (!isset($_SESSION['user_id']) || !isset($_POST['title'])) {
    echo json_encode(['success' => false, 'message' => 'User  not logged in or article title not provided.']);
    exit;
}

$userId = $_SESSION['user_id'];
$articleTitle = $_POST['title'];

$query = "SELECT COUNT(*) FROM favorites WHERE user_id = ? AND title = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $userId, $articleTitle);
$stmt->execute();
$stmt->bind_result($isFavoriteCount);
$stmt->fetch();
$stmt->close();

$isFavorite = $isFavoriteCount > 0;

echo json_encode(['success' => true, 'isFavorite' => $isFavorite]);
?>