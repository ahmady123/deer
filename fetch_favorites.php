<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("connect.php");

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['status' => 'error', 'message' => 'User  not logged in.']);
    exit;
}

$query = "SELECT title FROM favorites WHERE user_id = ? ORDER BY title DESC"; 
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Query preparation failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("i", $user_id);
$stmt->execute();

if ($stmt->error) {
    echo json_encode(['status' => 'error', 'message' => 'Execution failed: ' . $stmt->error]);
    exit;
}

$stmt->bind_result($title);
$favourites = array();

while ($stmt->fetch()) {
    $favourites[] = $title;
}

$stmt->close();

$count = count($favourites);

echo json_encode(array(
    "status" => "success",
    "favourites" => $favourites,
    "count" => $count
));
?>