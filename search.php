<?php
require_once 'connect.php';

$query = isset($_GET['query']) ? $_GET['query'] : '';

$query = mysqli_real_escape_string($conn, $query);

$sql = "SELECT * FROM upload_articles WHERE title LIKE '%$query%' OR content LIKE '%$query%'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h1>Search Results for: " . htmlspecialchars($query) . "</h1>";
    echo "<div class='articles-container'>";

    while ($row = $result->fetch_assoc()) {
        echo "<div class='article'>";
        echo "<h2>" . htmlspecialchars($row['title']) . "</h2>";
        echo "<p>" . htmlspecialchars($row['content']) . "</p>";
        echo "</div>";
    }

    echo "</div>";
} else {
    echo "<h1>No results found for: " . htmlspecialchars($query) . "</h1>";
}

$conn->close();
?>
