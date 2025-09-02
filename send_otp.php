<?php
// Include PHPMailer manually
require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendOtp($toEmail, $otp) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth = true;
        $mail->Username = 'mrahmady90@gmail.com'; // SMTP username
        $mail->Password = 'zkja snav kaoo qzxf'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('mrahmady90@gmail.com', 'Your Name');
        $mail->addAddress($toEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = '
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f9f9f9;
                    margin: 0;
                    padding: 0;
                }
                .email-container {
                    background-color: #ffffff;
                    margin: 20px auto;
                    padding: 20px;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    max-width: 400px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                }
                .email-header {
                    text-align: center;
                    font-size: 24px;
                    color: #333;
                    font-weight: bold;
                    margin-bottom: 10px;
                }
                .email-logo {
                    text-align: center;
                    margin-bottom: 15px;
                }
                .email-logo img {
                    width: 100px;
                }
                .email-body {
                    font-size: 16px;
                    color: #555;
                    text-align: center;
                }
                .otp-code {
                    font-size: 28px;
                    font-weight: bold;
                    color: #4caf50;
                    margin: 10px 0;
                }
                .email-footer {
                    font-size: 12px;
                    color: #aaa;
                    text-align: center;
                    margin-top: 15px;
                }
            </style>
        </head>
        <body>
            <div class="email-container">
          
                <div class="email-header">Your OTP Code</div>
                <div class="email-body">
                    Please use the code below to verify your account:
                    <div class="otp-code">' . $otp . '</div>
                    This code is valid for 5 minutes.
                </div>
                <div class="email-footer">
                    If you didn’t request this code, please ignore this email.
                </div>
            </div>
        </body>
        </html>';
        $mail->send();
        return json_encode(['success' => true]);
    } catch (Exception $e) {
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        error_log('Exception Message: ' . $e->getMessage());
        return json_encode(['success' => false, 'error' => $mail->ErrorInfo]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $toEmail = $_POST['toEmail'];
    $otp = $_POST['otp'];
    echo sendOtp($toEmail, $otp);
}
