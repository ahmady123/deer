<?php
header('Content-Type: application/json');
include 'connect.php';

if (isset($_GET['id'])) {
    $articleId = $_GET['id'];

    $query = "SELECT article_template FROM articles WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $articleId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        $articleData = json_decode($row['article_template'], true);

        if ($articleData) {
            echo json_encode([
                "status" => "success",
                "article" => [
                    "title" => $articleData['title'],
                    "picture" => $articleData['picture'],
                    "content" => $articleData['content']
                ]
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Article data could not be decoded"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Article not found"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "No article ID provided"]);
}
?>
