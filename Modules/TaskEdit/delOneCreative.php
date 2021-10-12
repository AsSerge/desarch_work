<?php
// Создание новой задачи
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта

$creative_id = $_POST['creative_id'];

$stmt = $pdo->prepare("DELETE FROM сreatives WHERE creative_id = ?");
$stmt->execute(array($creative_id));

?>