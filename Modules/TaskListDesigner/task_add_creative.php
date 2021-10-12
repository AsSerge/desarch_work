<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Настройки сайта

function GetCreativesCount($pdo, $task_id, $user_id){
	$stmt_all = $pdo->prepare("SELECT COUNT(*) FROM сreatives WHERE task_id = ?");
	$stmt_all->execute(array($task_id));
	$count['all'] = $stmt_all->fetchColumn();

	$stmt_user = $pdo->prepare("SELECT COUNT(*) FROM сreatives WHERE task_id = ? AND user_id = ?");
	$stmt_user->execute(array($task_id, $user_id));
	$count['user'] = $stmt_user->fetchColumn();

	return $count; 
}

if(isset($_POST['add_creative']) AND $_POST['add_creative'] == 'true'){

	// Добавляем новый креатив в базу
	$task_id = $_POST['task_id'];
	$user_id = $_POST['user_id'];
	if($_POST['creative_name'] != ''){
		$creative_name = $_POST['creative_name'];	
	}else{
		$creative_name = "Новый креатив";
	}	
	$creative_status = "В работе";
	$creative_start_date = date("Y-m-d"); // Устанвливаем дату создания креатива

	$stmt = $pdo->prepare("INSERT INTO сreatives SET 
	task_id = :task_id, 
	user_id = :user_id,
	creative_name = :creative_name,
	creative_status =:creative_status, 
	creative_start_date = :creative_start_date
	");

	$stmt->execute(array(
	'task_id' => $task_id, 
	'user_id' => $user_id,
	'creative_name' => $creative_name,
	'creative_status' => $creative_status, 
	'creative_start_date' => $creative_start_date
	));
	$creative_id = $pdo->lastInsertId(); // Возвращает номер последней добавленной записи: Необходим для создания новой папки креатива
	// Создаем для него каталог на диске
	if(!is_dir(CREATIVE_FOLDER.$creative_id)){ 
		mkdir(CREATIVE_FOLDER.$creative_id, 0777);
	}

	$MyCountSrc = GetCreativesCount($pdo, $task_id, $user_id);

	echo $MyCountSrc['user']."[".$MyCountSrc['all']."]";
}
?>