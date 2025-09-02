<?php
session_start();
include("connect.php"); 
$data = json_decode(file_get_contents("php://input"), true);
$title = $data['title'] ?? null; 

if (!$title) {
    echo json_encode(['status' => 'error', 'message' => 'Title is required.']);
    exit;
}

$userId = $_SESSION['user_id'] ?? null; 
if (!$userId) {
    echo json_encode(['status' => 'error', 'message' => 'User  not logged in.']);
    exit;
}

$query = "DELETE FROM favorites WHERE user_id = ? AND title = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $userId, $title); 

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Favorite removed successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to remove favorite.']);
}

$stmt->close();
$conn->close();
?>