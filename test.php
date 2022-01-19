<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
$textSearch = $_GET['textSearch'];

if ($textSearch != ""){
	$stmt = $pdo->prepare("SELECT * FROM customers WHERE customer_type = ?");
	$stmt->execute(array($textSearch));
}else{
	$stmt = $pdo->prepare("SELECT * FROM customers WHERE 1");
	$stmt->execute();
}
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

//Возарвщеаем использованные теги
echo json_encode($result);
?>