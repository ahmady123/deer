<?php
// Get the raw input and decode it
$input = file_get_contents('php://input');
$article = json_decode($input, true);

// Debug: Log the raw input and decoded JSON (only for testing purposes)
file_put_contents("debug_log.txt", "Raw Input: " . $input . "\nDecoded JSON: " . print_r($article, true) . "\n", FILE_APPEND);

// Check if the title key exists in the decoded JSON
if (!isset($article['title'])) {
    header('Content-Type: application/json');
    echo json_encode(array("status" => "error", "message" => "No article title provided."));
    exit;
}

// Connect to the database
$conn = new mysqli("localhost", "root", "", "signup");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 1: Retrieve the article_id based on the title
$title = $article['title'];
$stmt = $conn->prepare("SELECT article_id FROM upload_articles WHERE title = ?");
$stmt->bind_param("s", $title);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($article_id);
$stmt->fetch();

if ($stmt->num_rows == 0) {
    header('Content-Type: application/json');
    echo json_encode(array("status" => "error", "message" => "Article not found."));
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close(); 
$stmt = $conn->prepare("DELETE FROM upload_articles WHERE article_id = ?");
$stmt->bind_param("i", $article_id);

if ($stmt->execute()) {
    header('Content-Type: application/json');
    echo json_encode(array("status" => "success", "message" => "Article deleted successfully."));
} else {
    header('Content-Type: application/json');
    echo json_encode(array("status" => "error", "message" => "Error deleting article: " . $stmt->error));
}

$stmt->close(); 
$conn->close();
?>
