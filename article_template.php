<?php

$conn = new mysqli($host, $user, $pass, $db);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$stmt = $conn->prepare("SELECT article_template FROM upload_articles WHERE id = ?");
$stmt->bind_param("i", $articleId);
$stmt->execute();
$stmt->bind_result($articleTemplate);
$stmt->fetch();
$stmt->close();


echo $articleTemplate;


$conn->close();
?>