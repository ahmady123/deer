<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $articleId = $_POST['article_id'] ?? null; 
    $title = $_POST['title'] ?? null; 
    $content = $_POST['content'] ?? null; 
    $imagePath = null;
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = $_FILES['image']['name'];
        $imageDestinationPath = 'uploads/' . basename($imageName);
        $imagePath = 'uploads/' . basename($imageName);

        if (!move_uploaded_file($imageTmpPath, $imageDestinationPath)) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file.']);
            exit;
        }
    }

    $checkQuery = "SELECT title, content, image_path FROM upload_articles WHERE article_id = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("i", $articleId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows > 0) {
        $existingArticle = $checkResult->fetch_assoc();
        if (!$imagePath) {
            $imagePath = $existingArticle['image_path'];
        }

        if ($existingArticle['title'] === $title && $existingArticle['content'] === $content && $existingArticle['image_path'] === $imagePath) {
   
            header("Location: profile.php");
            exit;
        }

        $articleTemplate = "
            <h1>$title</h1>
            <img src='$imagePath' alt='Article Image'>
            <div>$content</div>
        ";

        $updateQuery = "UPDATE upload_articles SET title = ?, content = ?, image_path = ?, article_template = ? WHERE article_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        
        if (!$updateStmt) {
            echo json_encode(['status' => 'error', 'message' => 'SQL Error: ' . $conn->error]);
            exit;
        }

        $updateStmt->bind_param("ssssi", $title, $content, $imagePath, $articleTemplate, $articleId);
        $updateStmt->execute();

        if ($updateStmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Article updated successfully.']);
            header("Location: profile.php?status=success");
            exit;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update article.']);
            exit;
        }
        

        $updateStmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Article not found.']);
    }

    $checkStmt->close();
}
?>