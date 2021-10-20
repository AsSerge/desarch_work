<?php
// Утверждение креатива
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
// 'Розница'	    РОЗ-2021-00001
// 'Сети'          СЕТ
// 'Опт'           ОПТ
// 'Маркетплейс'   МПЛ
// 'Логотип'       ЛОГ

// $customer_id = $_POST['customer_id'];
$customer_id = "3";
// Получаем тип заказчика по ID
$stmt = $pdo->prepare("SELECT customer_type FROM customers WHERE customer_id = ?");
$stmt->execute(array($customer_id));
$customer_type = $stmt->fetch(PDO::FETCH_COLUMN); // Тип заказчика

// Получаем номер последней добавленной задачи
$stmt = $pdo->prepare("SELECT task_number FROM tasks WHERE customer_id = ?");
$stmt->execute(array($customer_id));
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC); // Тип заказчика



// echo $customer_type;
// echo "<pre>";
// print_r($tasks);
// echo "</pre>";


// $stmt = $pdo->prepare("SELECT * FROM tasks AS T LEFT JOIN customers AS C ON (T.customer_id = C.customer_id) WHERE C.customer_id = ?");
// $stmt->execute(array($customer_id));
// $task_number = $stmt->fetchALL(PDO::FETCH_ASSOC);

// echo "<pre>";
// print_r($task_number);
// echo "</pre>";


?>