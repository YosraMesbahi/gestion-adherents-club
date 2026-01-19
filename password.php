<?php 
$password = "4321";
$salt = "rl";
$hash = crypt ($password, $salt);
echo $hash; 
?>