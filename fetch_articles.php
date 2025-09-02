<?php
session_start();
include("connect.php");

$email = $_SESSION['email'] ?? null;

if (!$email) {
    die("Session email not found. Please log in.");
}

$query = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

if (!$user_id) {
    die("User ID not found for the given email.");
}

$query = "SELECT title, view_count FROM upload_articles WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($title, $view_count);
$articles = array();

while ($stmt->fetch()) {
    $articles[] = array('title' => $title, 'view_count' => $view_count);
}

$stmt->close();

$count = count($articles);

echo json_encode(array(
    "status" => "success",
    "articles" => $articles,
    "count" => $count
));
?>
