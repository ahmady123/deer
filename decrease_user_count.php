<?php

$userCountFile = 'user_count.txt';


if (file_exists($userCountFile)) {
    $userCount = (int)file_get_contents($userCountFile);
} else {
    $userCount = 0;
}


$userCount = max(0, $userCount - 1); 


file_put_contents($userCountFile, $userCount);
?>