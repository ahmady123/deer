<?php
// Replace with your secret key
$secretKey = "6Ld3a5wqAAAAAMCxykgNuVsQSVj5mfuDba6uzWBG";

// Check if reCAPTCHA response is set
if (isset($_POST['g-recaptcha-response'])) {
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    // Send a request to Google for verification
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaResponse");

    // Decode JSON response
    $responseData = json_decode($response);

    if ($responseData->success) {
        echo "reCAPTCHA verified successfully.";
        // Proceed with your logic
    } else {
        echo "reCAPTCHA verification failed.";
    }
} else {
    echo "reCAPTCHA response not found.";
}
?>
