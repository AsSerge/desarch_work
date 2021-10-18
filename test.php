<?php
// Утверждение креатива
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo

$pass = "aser22";
$hash = password_hash($pass, PASSWORD_DEFAULT);

echo $pass ." -> ".$hash;

// if(password_verify("Abrakadabra202у1", $hash)){
// 	echo "Совпадает!";
// }else{
// 	echo "NOT";
// }

?>