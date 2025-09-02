<?php
$host = 'localhost';
$db = 'signup';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['section']) && isset($_GET['id'])) {
        $section = $_GET['section'];
        $id = (int)$_GET['id']; 

        if ($section === 'articles') {
            $stmt = $pdo->prepare("DELETE FROM upload_articles WHERE article_id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true, 'message' => 'Article deleted successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No article found with that ID.']);
            }
        } elseif ($section === 'comments') {
            $stmt = $pdo->prepare("DELETE FROM comments WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true, 'message' => 'Comment deleted successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No comment found with that ID.']);
            }
        } elseif ($section === 'replies') {
            $stmt = $pdo->prepare("DELETE FROM replies WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true, 'message' => 'Reply deleted successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No reply found with that ID.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid section.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid parameters.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>