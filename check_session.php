
<?php
session_start();

// Check if the session is started and a specific session variable is set
if (isset($_SESSION['user_id'])) {
    echo json_encode(['sessionStarted' => true]);
} else {
    echo json_encode(['sessionStarted' => false]);
}
?>
