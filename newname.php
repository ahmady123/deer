<?php
session_start();
include("connect.php");

$email = $_SESSION['email'] ?? null;

if (!$email) {
    die("Session email not found. Please log in.");
}

$data = json_decode(file_get_contents('php://input'), true);
$newName = $data['name'] ?? null;

if ($newName) {
    $query = "UPDATE users SET name = ? WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $newName, $email);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update name.']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Name cannot be empty.']);
}
?>
