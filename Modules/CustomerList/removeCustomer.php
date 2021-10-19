<?php
// Соединямся с БД
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
$customer_id = $_POST['customer_id'];

$query = $pdo->prepare("DELETE FROM `customers` WHERE `customer_id` = ?");
$query->execute(array($customer_id));

echo $customer_id;

?>