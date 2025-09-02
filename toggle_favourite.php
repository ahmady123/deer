<?php
session_start();
include("connect.php");

if (!isset($_SESSION['user_id']) || !isset($_POST['title'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$userId = $_SESSION['user_id'];
$articleTitle = $_POST['title'];

$query = "SELECT * FROM favorites WHERE user_id = ? AND title = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $userId, $articleTitle);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $deleteQuery = "DELETE FROM favorites WHERE user_id = ? AND title = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("is", $userId, $articleTitle);
    $deleteStmt->execute();
    $deleteStmt->close();
    $isFavorite = false; 
} else {
    $insertQuery = "INSERT INTO favorites (user_id, title) VALUES (?, ?)";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param("is", $userId, $articleTitle);
    $insertStmt->execute();
    $insertStmt->close();
    $isFavorite = true;
}

$stmt->close();
echo json_encode(['success' => true, 'isFavorite' => $isFavorite]);
?>