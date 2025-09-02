<?php
session_start();

$host = 'localhost'; 
$db = 'signup';
$user = 'root'; 
$pass = ''; 

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
    die("User  ID not found for the given email.");
}

if ($_FILES['article_image']['error'] == UPLOAD_ERR_OK) {
    $imageTmpPath = $_FILES['article_image']['tmp_name'];
    $imageName = $_FILES['article_image']['name'];
    $imagePath = 'uploads/' . basename($imageName); 

    if (move_uploaded_file($imageTmpPath, $imagePath)) {
        $stmt = $conn->prepare("INSERT INTO upload_articles (user_id, title, content, image_path, article_template, created_at) VALUES (?, ?, ?, ?, ?, NOW())");

        if ($stmt) {
            $title = trim($_POST['article_title']);
            $content = trim($_POST['article_content']);

            if (empty($title) || empty($content)) {
                die("Title and content cannot be empty.");
            }

            $articleTemplate = "
                <h1>$title</h1>
                <img src='$imagePath' alt='$title'><br><br>
                <div>$content</div>
            ";

            $stmt->bind_param("issss", $user_id, $title, $content, $imagePath, $articleTemplate);
            
            if ($stmt->execute()) {
                echo "Article uploaded successfully!";
                header("Location: index.php?upload=success");
                exit; 
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    } else {
        echo "Error uploading image.";
    }
} else {
   echo "ff";
}

$conn->close();
?>