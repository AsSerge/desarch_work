<?php
// Утверждение креатива
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта
$creative_id = $_POST['creative_id'];

// $creative_id = "3";

$stmt = $pdo->prepare("SELECT creative_hash_list  FROM сreatives WHERE creative_id = ?");
$stmt->execute(array($creative_id));
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$result = explode("|", $result['creative_hash_list']);

function GetTagNameById($pdo, $hash_id){
	$stmt = $pdo->prepare("SELECT * FROM hash_tags WHERE hash_id = ?");
	$stmt->execute(array($hash_id));
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	return $result['hash_name'];
}
 //Возарвщеаем использованные теги
$itog = [];
foreach($result as $res){
	$itog[$res] = GetTagNameById($pdo, $res);
}
echo json_encode($result);
?>