<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
// 'Розница'	    РОЗ-2021-00001
// 'Сети'          СЕТ
// 'Опт'           ОПТ
// 'Маркетплейс'   МПЛ
// 'Логотип'       ЛОГ

// $customer_id = $_POST['customer_id'];
$customer_id = 3;
// Получаем тип заказчика по ID
$stmt = $pdo->prepare("SELECT customer_type FROM customers WHERE customer_id = ?");
$stmt->execute(array($customer_id));
$customer_type = $stmt->fetch(PDO::FETCH_COLUMN); // Тип заказчика

switch($customer_type){
	case 'Розница':
		$pref = 'РОЗ';
		break;
	case 'Сети':
		$pref = 'СЕТ';
		break;
	case 'Опт':
		$pref = 'ОПТ';
		break;
	case 'Маркетплейс':
		$pref = 'МПЛ';
		break;
	case 'Логотип':
		$pref = 'ЛОГ';
		break;
}

$year = date("Y"); // Год добавления
// Получаем номер последней добавленной задачи
// $stmt = $pdo->prepare("SELECT task_number FROM tasks AS T LEFT JOIN customers AS C ON (T.customer_id = C.customer_id) WHERE customer_type = ? ORDER BY T.task_update DESC");
$stmt = $pdo->prepare("SELECT MAX(task_number) FROM tasks AS T LEFT JOIN customers AS C ON (T.customer_id = C.customer_id) WHERE customer_type = ?");
$stmt->execute(array($customer_type));
$tasks = $stmt->fetch(PDO::FETCH_COLUMN); // Тип заказчика ВРОДЕ ПОСЛЕДНЯЯ ЗАПИСЬ т.к. фильтр по убыванию


echo $tasks;

echo "<br>";


// $num = 1;	// Номер добвления
// $num_str = sprintf("%08d", $num);// Заполнитель
// echo $pref."-".$year."-".$num_str;

?>