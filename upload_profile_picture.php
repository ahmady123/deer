<?php
session_start();
header('Content-Type: application/json');

include("connect.php");

ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    if (!isset($_SESSION['email'])) {
        throw new Exception("User not logged in.");
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['profile_picture_upload'])) {
        throw new Exception("No file uploaded.");
    }

    $email = $_SESSION['email'];
    $profilePicture = $_FILES['profile_picture_upload'];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

    if (!in_array($profilePicture['type'], $allowedTypes)) {
        throw new Exception("Invalid file type.");
    }

    $fileName = uniqid() . "_" . basename($profilePicture["name"]);
    $targetFilePath = __DIR__ . "/uploads/" . $fileName;

    if (!is_dir(__DIR__ . "/uploads")) {
        mkdir(__DIR__ . "/uploads", 0777, true);
    }

    if (!move_uploaded_file($profilePicture["tmp_name"], $targetFilePath)) {
        throw new Exception("File upload failed.");
    }

    $relativePath = "uploads/" . $fileName;

    $query = "UPDATE users SET profile_picture = ? WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $relativePath, $email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["status" => "success", "filePath" => $relativePath]);
    } else {
        throw new Exception("Database update failed.");
    }
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}

?>
