<?php

$servername = "localhost";  
$username = "root";         
$password = "";             
$dbname = "signup";         

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$section = isset($_GET['section']) ? $_GET['section'] : '';

if ($section) {
    switch ($section) {
        case 'articles':
            $sql = "SELECT article_id, title FROM upload_articles ORDER BY article_id DESC"; 
            break;
        case 'comments':
            $sql = "SELECT id, comment FROM comments ORDER BY id DESC"; 
            break;
        case 'replies':
            $sql = "SELECT id, reply_text FROM replies ORDER BY id DESC"; 
            break;
        default:
            $sql = "";
    }

    if ($sql) {
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $items = [];
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
            echo json_encode($items);
        } else {
            echo json_encode([]);
        }
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}

$conn->close();
?>
