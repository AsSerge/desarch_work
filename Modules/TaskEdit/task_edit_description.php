<?php
// Создание новой задачи
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта


$task_id = $_POST['TaskID'];
$task_description = $_POST['TaskDescription'];

$stmt = $pdo->prepare("UPDATE `tasks` SET `task_description` = ? WHERE `task_id` = ?");
$stmt->execute(array($task_description, $task_id));

echo $task_description;
?>

