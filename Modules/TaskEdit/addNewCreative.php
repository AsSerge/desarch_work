<?php
// Создание новой задачи
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта

$task_id = $_POST['TaskID'];

$stmt2 = $pdo->prepare("INSERT INTO сreatives SET task_id = ?");
$stmt2->execute(array($task_id));

?>