<?php
// File to store user count
$userCountFile = 'user_count.txt';

// Read the current user count
if (file_exists($userCountFile)) {
    $userCount = (int)file_get_contents($userCountFile);
} else {
    $userCount = 0;
}

// Increment the user count
$userCount++;

// Write the updated user count back to the file
file_put_contents($userCountFile, $userCount);

// Return the user count
echo $userCount;
?>