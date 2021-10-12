<?php
// Утверждение креатива РУКОВОДИТЕЛЕМ ДИЗАЙНЕРА
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта

$creative_id = $_POST['creative_id'];
$stmt = $pdo->prepare("UPDATE сreatives SET 
				creative_status = 'На рассмотрении' 
				WHERE creative_id = :creative_id
");
$stmt->execute(array('creative_id'=>$creative_id));

echo $creative_id;
?>