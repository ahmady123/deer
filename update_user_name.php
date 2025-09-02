<?php
session_start();
include("connect.php");

$email = $_SESSION['email'] ?? null; 
if (!$email) {
    die(json_encode(["status" => "error", "message" => "Session email not found. Please log in."]));
}

$data = json_decode(file_get_contents('php://input'), true);
$name = $data['name'] ?? '';

if (!$name) {
    die(json_encode(["status" => "error", "message" => "Name is required."]));
}

$query = "UPDATE users SET name = ? WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $name, $email);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["status" => "success", "message" => "Name updated successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update name."]);
}

$stmt->close();
?>
