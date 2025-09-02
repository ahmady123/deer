<?php
session_start();
require_once 'vendor/autoload.php';


$client = new Google_Client();
$client->setClientId('295250178189-lgp6p1s9hnahqrtpck4opc1k0tusb6fa.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('https://localhost/website/callback.php');
$client->addScope('email');
$client->addScope('profile');

if (isset($_GET['code'])) {
    
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    
    if (!isset($token['error'])) {
        $client->setAccessToken($token['access_token']);
        
        
        $oauth2 = new Google_Service_Oauth2($client);
        $userInfo = $oauth2->userinfo->get();

        
        $_SESSION['email'] = $userInfo->email;
        $_SESSION['name'] = $userInfo->name;
        $_SESSION['picture'] = $userInfo->picture;

        
        $conn = new mysqli('localhost', 'root', '', 'signup');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $email = $conn->real_escape_string($userInfo->email);
        $name = $conn->real_escape_string($userInfo->name);
        $picture = $conn->real_escape_string($userInfo->picture);

        
        $result = $conn->query("SELECT * FROM users WHERE email = '$email'");
        if ($result->num_rows == 0) {
        
            $conn->query("INSERT INTO users (email, name, profile_picture) VALUES ('$email', '$name', '$picture')");
            
            $userId = $conn->insert_id;
        } else {
            
            $row = $result->fetch_assoc();
            $userId = $row['id'];
        }

        
        $_SESSION['user_id'] = $userId;

        
        header('Location: index.php');
        exit();
    } else {
        
        echo "Error fetching access token.";
    }
} else {
    
    echo "Authorization code not found.";
}
?>
