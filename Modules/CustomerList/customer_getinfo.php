<?php
// Соединяемся с базой
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo

$customer_id = $_POST['customer_id'];

$query = $pdo->prepare("SELECT * FROM customers WHERE `customer_id` = ?");
$query->execute(array($customer_id));
// $userdata = json_encode($query->fetchAll(PDO::FETCH_ASSOC));// Все записи
$customerdata = json_encode($query->fetch(PDO::FETCH_ASSOC)); // Одна запись

echo $customerdata;
?>