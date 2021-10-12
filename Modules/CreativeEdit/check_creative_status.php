<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта

$creative_id = $_POST['creative_id'];

$stmt = $pdo->prepare("SELECT * FROM сreatives WHERE creative_id = ?");
$stmt->execute(array($creative_id));
$result = $stmt->fetch(PDO::FETCH_ASSOC);
echo $result['creative_status'];
?>