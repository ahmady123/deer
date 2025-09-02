<?php
session_start();
include("connect.php");

if (isset($_POST['comment_text']) && isset($_SESSION['user_id']) && isset($_POST['article_title'])) {
    $commentText = $_POST['comment_text'];
    $userId = $_SESSION['user_id'];
    $articleTitle = $_POST['article_title'];

    $insertCommentQuery = "INSERT INTO comments (article_title, comment, user_id, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($insertCommentQuery);
    $stmt->bind_param("ssi", $articleTitle, $commentText, $userId);

    if ($stmt->execute()) {
        echo 'success'; 
    } else {
        echo 'error'; 
    }
    $stmt->close();
} else {
    echo 'error';
}
?>
