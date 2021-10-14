<?php
// Утверждение креатива
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта
$hash_name = $_POST['hash_name'];

if ($hash_name ==""){
	$stmt = $pdo->prepare("SELECT creative_name, creative_hash_list FROM сreatives WHERE 1");	
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($result);
}else{
	$stmt = $pdo->prepare("SELECT creative_name, creative_hash_list FROM сreatives WHERE creative_hash_list LIKE ?");
	$stmt->execute(array("%".$hash_name."%"));	
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($result);
}
?>