<?php
// Получение очередного номера
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
// 'Розница'	    РОЗ-2021-00001
// 'Сети'          СЕТ
// 'Опт'           ОПТ
// 'Маркетплейс'   МПЛ
// 'Логотип'       ЛОГ

$customer_id = $_POST['customer_id'];
// Получаем тип заказчика по 
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
$stmt = $pdo->prepare("SELECT task_number FROM tasks WHERE customer_id = ? ORDER BY task_update");
$stmt->execute(array($customer_id));
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC); // Тип заказчика








$num = 1;	// Номер добвления
$num_str = sprintf("%08d", $num);// Заполнитель
echo $pref."-".$year."-".$num_str;

?>