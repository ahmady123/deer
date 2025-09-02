<?php
session_start();

$email = $_POST['email'];


$conn = mysqli_connect("localhost", "root", "", "signup");


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$query = "SELECT profile_picture, name FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    if (!empty($row['profile_picture']) && !empty($row['name'])) {
        echo "complete";
    } else {
        echo "incomplete";
    }
} else {
    echo "incomplete";
}

mysqli_close($conn);
?>