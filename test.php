<?php

include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
// 'Розница'	    РОЗ-2021-00001
// 'Сети'          СЕТ - вставка
// 'Опт'           ОПТ
// 'Маркетплейс'   МПЛ - вставка
// 'Логотип'       ЛОГ

// $customer_id = $_POST['customer_id'];
$customer_id = 7;
// Получаем тип заказчика по ID
$stmt = $pdo->prepare("SELECT customer_type FROM customers WHERE customer_id = ?");
$stmt->execute(array($customer_id));
$customer_type = $stmt->fetch(PDO::FETCH_COLUMN); // Тип заказчика

switch($customer_type){
	case 'Розница':
		$pref = 'РОЗ';
		break;
	case 'Сети':
		$pref = '';
		break;
	case 'Опт':
		$pref = 'ОПТ';
		break;
	case 'Маркетплейс':
		$pref = '';
		break;
	case 'Логотип':
		$pref = 'ЛОГ';
		break;
}

if($pref != ""){
	$year = date("y"); // Год добавления

	// Получаем номер последней добавленной задачи
	// $stmt = $pdo->prepare("SELECT task_number FROM tasks AS T LEFT JOIN customers AS C ON (T.customer_id = C.customer_id) WHERE customer_type = ? ORDER BY T.task_update DESC");
	$stmt = $pdo->prepare("SELECT MAX(task_number) FROM tasks AS T LEFT JOIN customers AS C ON (T.customer_id = C.customer_id) WHERE customer_type = ?");
	$stmt->execute(array($customer_type));
	$task_number = $stmt->fetch(PDO::FETCH_COLUMN); // Тип заказчика ВРОДЕ ПОСЛЕДНЯЯ ЗАПИСЬ т.к. фильтр по убыванию

	// Проверяем совпадение года
	/*
	Если год последней даты не совпадает с текущин
	ставим номер очередной записи в 00001
	Если нет - продолжаем годовую нумерацию
	*/

	$last_task_year = (int)explode("-", $task_number)[1]; // Получаем год последней записи
	$last_task_number = (int)explode("-", $task_number)[2]; // Получаем год последней записи
	//Если год последней записи меньше текущего года
	if($last_task_year < $year){
		$next_task_year = date("y"); // Берем текущий (новый) год
		$next_task_number = 1; // Ставим первый номер нового года
	}elseif($last_task_year == $year){
		$next_task_year = $last_task_year; // Берем год последней записи 
		$next_task_number = $last_task_number + 1; // Увеличиваем номер последней записи на 1
	}
	// echo $next_task_year;
	// echo "<br>";
	// echo $next_task_number;
	// echo "<br>";

	// echo $last_task_number;
	// echo "<br>";
	// $next_num = (int)explode("-", $last_task_number)[2] + 1;
	// $next_year = (int)explode("-", $last_task_number)[1] + 1;
	// echo $next_num;
	// echo "<br>";
	// echo $next_year;
	// echo "<br>";
	
	$num_str = sprintf("%05d", $next_task_number);// Заполнитель
	echo $pref."-".$next_task_year."-".$num_str;
}
?>