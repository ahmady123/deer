<?php
session_start();
include("connect.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    $articleTitle = trim($_POST['title']);
    error_log("Attempting to delete article with title: " . $articleTitle);

    $query = "SELECT article_id FROM upload_articles WHERE title = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare SELECT statement.']);
        exit;
    }

    $stmt->bind_param("s", $articleTitle);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($articleId);
    $stmt->fetch();

    if ($stmt->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Article not found.']);
        $stmt->close();
        $conn->close();
        exit;
    }

    $stmt->close();

    $deleteQuery = "DELETE FROM upload_articles WHERE article_id = ?";
    $deleteStmt = $conn->prepare($deleteQuery);

    if (!$deleteStmt) {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare DELETE statement.']);
        exit;
    }

    $deleteStmt->bind_param("i", $articleId);
    $deleteStmt->execute();

    if ($deleteStmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Article deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete the article.']);
    }

    $deleteStmt->close(); 
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

$conn->close();
?>
