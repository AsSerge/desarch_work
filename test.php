<?php
$email = "z00m.serge@gmail.com";
$ph = password_hash($email, PASSWORD_BCRYPT);
echo $ph;

?>