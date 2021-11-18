<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
$customer_id = $_POST['customer_id'];

if ($customer_id != ""){
	$stmt = $pdo->prepare("SELECT * FROM customers WHERE customer_id = ?");
	$stmt->execute(array($customer_id));
}else{
	$stmt = $pdo->prepare("SELECT * FROM customers WHERE 1");
	$stmt->execute();
}
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

//Возарвщеаем использованные теги
echo json_encode($result);

?>