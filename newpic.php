<?php
session_start();
include("connect.php");

$email = $_SESSION['email'] ?? null;

if (!$email) {
    die("Session email not found. Please log in."); 
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $profilePictureUpload = $_FILES['profile_picture_upload'];

    if ($profilePictureUpload['error'] == 0) {
        $targetDir = 'uploads/';
        $targetFile = $targetDir . basename($profilePictureUpload['name']);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $check = getimagesize($profilePictureUpload['tmp_name']);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }

        if ($profilePictureUpload['size'] > 500000) {
            $uploadOk = 0;
        }

        if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg') {
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($profilePictureUpload['tmp_name'], $targetFile)) {
                $query = "UPDATE users SET profile_picture = ? WHERE email = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ss", $targetFile, $email);
                $stmt->execute();

                header('Content-Type: application/json');
                echo json_encode(['status' => 'success', 'filePath' => $targetFile]);
                exit;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
}