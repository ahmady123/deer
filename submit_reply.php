<?php
session_start();
include("connect.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reply_text'], $_POST['comment_id'])) {
    $replyText = $_POST['reply_text'];
    $commentId = $_POST['comment_id'];
    $userId = $_SESSION['user_id']; 

    $query = "INSERT INTO replies (comment_id, user_id, reply_text, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $commentId, $userId, $replyText);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
}
?>