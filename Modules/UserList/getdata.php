<?php
// Соединямся с БД
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo

$query = $pdo->prepare("SELECT user_id, user_login, user_name, user_surname, user_role, user_superior FROM users WHERE 1");
$query->execute();
$userdata = json_encode($query->fetchAll(PDO::FETCH_ASSOC));
echo $userdata;
?>