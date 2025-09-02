<?php
include 'connect.php'; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $newPassword = password_hash($_POST['newPassword'], PASSWORD_DEFAULT); 

    $sql = "UPDATE users SET password='$newPassword' WHERE email='$email'";

    if (mysqli_query($conn, $sql)) {
        echo "Password updated successfully.";
    } else {
        echo "Error updating password: " . mysqli_error($conn);
    }
}
?>
