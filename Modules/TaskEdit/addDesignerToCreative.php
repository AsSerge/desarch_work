<?php
// Создание новой задачи
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта

$creative_id = $_POST['creative_id'];
$user_id = $_POST['user_id'];

$stmt = $pdo->prepare("UPDATE сreatives SET user_id = :user_id WHERE creative_id = :creative_id");
$stmt->execute(array(
	'creative_id' => $creative_id,
	'user_id' => $user_id
	)
);

echo "Добавлено";

?>