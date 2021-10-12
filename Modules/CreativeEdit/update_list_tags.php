<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта

$hash_name = trim($_POST['hash_name']);

// Проверяем базу тегов
$stmt = $pdo->prepare("SELECT hash_name FROM hash_tags WHERE 1"); 
$stmt->execute();
$all_tags_array = $stmt->fetchAll(PDO::FETCH_COLUMN); // Значение одного поля

if(!in_array($hash_name, $all_tags_array)){
	$stmt = $pdo->prepare("INSERT INTO hash_tags SET hash_name = ?"); 
	$stmt->execute(array($hash_name));
	echo $hash_name. "ADDED";
}else{
	echo $hash_name. " Существует в базе!";
}
?>