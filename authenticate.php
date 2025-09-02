<?php
session_start();

ob_clean();

error_reporting(0);
ini_set('display_errors', 0);

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['email']) && isset($data['password'])) {
    $email = $data['email'];
    $password = $data['password'];

    $allowed_emails = ['mrahmady90@gmail.com'];

    if (in_array($email, $allowed_emails)) {
        
        $conn = new mysqli('localhost', 'root', '', 'signup');
        
        if ($conn->connect_error) {
            echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
            exit;
        }

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id']; // Store user_id in session
                $_SESSION['email'] = $email; // Optionally store email as well
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid password']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Email not found']);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Email not authorized']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>