<?php
$password = 'dyci2023';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Password hash for 'dyci2023': " . $hash . "\n";
?> 