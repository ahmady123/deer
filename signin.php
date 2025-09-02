<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

function handleError($errno, $errstr, $errfile, $errline) {
    echo json_encode(['success' => false, 'error' => "Error: $errstr in $errfile on line $errline"]);
    exit;
}
set_error_handler("handleError");

$servername = "localhost";
$username = "root";
$dbpassword = "";
$dbname = "signup";

$conn = new mysqli($servername, $username, $dbpassword, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed.']);
    exit;
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'error' => 'Missing email or password.']);
    exit;
}

$sql = "SELECT id, password FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Failed to prepare statement.']);
    exit;
}
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'unregistered_email']);
} else {
    $row = $result->fetch_assoc();
    $hashed_password = $row['password'];

    if (password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['email'] = $email;
        
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'wrong_password']);
    }
}

$stmt->close();
$conn->close();
?>
