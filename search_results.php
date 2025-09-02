<?php
header('Content-Type: text/html; charset=UTF-8');

require_once 'connect.php'; 

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");

function escapeLike($string) {
    return str_replace(['%', '_', '\\'], ['\\%', '\\_', '\\\\'], $string);
}

$query = isset($_GET['query']) ? $_GET['query'] : '';

$query = $conn->real_escape_string(trim($query));
$query = escapeLike($query);

$sql = "SELECT article_id, title FROM upload_articles WHERE title LIKE '%$query%'";

$result = $conn->query($sql);

if (!$result) {
    echo "Error executing query: " . $conn->error;
    exit; 
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $url_title = urlencode($row['title']); 

        echo "<div class='article-title'><a href='view_article.php?title=" . htmlspecialchars($url_title) . "'>" . htmlspecialchars($row['title']) . "</a></div>";
    }
} else {
    echo "<p class='no-results'>No results found for: " . htmlspecialchars($query) . "</p>";
}

$conn->close();
?>