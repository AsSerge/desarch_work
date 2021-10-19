<?php
// Соединямся с БД
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
if(isset($_POST['add_customer']) and $_POST['customer_id'] == ''){
	if($_POST['customer_name'] != "" and $_POST['customer_type'] != ""){

		$customer_name = $_POST['customer_name'];
		$customer_type = $_POST['customer_type'];
		$customer_description = $_POST['customer_description'];

		$stmt = $pdo->prepare("INSERT INTO `customers` SET `customer_name` = :customer_name, `customer_type` = :customer_type, `customer_description` = :customer_description");
		$stmt->execute(array(
			'customer_name' => $customer_name,
			'customer_type' => $customer_type,
			'customer_description' => $customer_description
		));
		echo "Заказчик добавлен";
	}
	else{
		echo "NO";
	}
}elseif (isset($_POST['add_customer']) and $_POST['customer_id'] != ''){

	if($_POST['customer_name'] != "" and $_POST['customer_type'] != ""){

		$customer_id = $_POST['customer_id'];
		$customer_name = $_POST['customer_name'];
		$customer_type = $_POST['customer_type'];
		$customer_description = $_POST['customer_description'];		
		
		$stmt = $pdo->prepare("UPDATE `customers` SET `customer_name`=:customer_name, `customer_type`=:customer_type,`customer_description`=:customer_description WHERE `customer_id` =:customer_id");

		$stmt->execute(array(
			'customer_name' => $customer_name,
			'customer_type' => $customer_type,
			'customer_description' => $customer_description,
			'customer_id' => $customer_id
		));
		echo "Заказчик добавлен";
	}
	else{
		echo "NO";
	}
	
}


?>