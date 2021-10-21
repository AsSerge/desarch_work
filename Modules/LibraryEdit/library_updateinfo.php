<?php
// Скрипт загрузки исходников в библиотеку (!!!!!!Выполняется при подключеии дизайнов к креативу!!!!!!)
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Настройки сайта

// $design_id = $_POST['design_id']; // Грузим ID дизайна для случая его обновления
$creative_id = $_POST['creative_id'];
$user_id = $_POST['user_id'];
$design_name = $_POST['design_name'];
$design_source_url = $_POST['design_source_url'];
$design_creative_style = $_POST['design_creative_style'];
$tags = $_POST['design_hash_list'];

// Формируем строку HASH тегов
if(is_array($tags) AND count($tags) !=0){
	foreach($tags as $tag){
		$design_hash_list .= $tag . "|";
	}
	$design_hash_list = substr($design_hash_list, 0,-1);
}else{
	$design_hash_list = "";
}

// Функция вставки информации о НОВОМ дизайне в базу
function InsertDesignInfo($pdo, $creative_id, $user_id, $design_name, $design_source_url, $design_creative_style, $design_hash_list){
	$stmt = $pdo->prepare("INSERT INTO designes SET creative_id = :creative_id, user_id = :user_id, design_name = :design_name, design_source_url = :design_source_url, design_creative_style = :design_creative_style, design_hash_list = :design_hash_list");
	$stmt->execute(array(
		'creative_id'=>$creative_id,
		'user_id'=>$user_id,
		'design_name'=>$design_name,
		'design_source_url'=>$design_source_url,
		'design_creative_style'=>$design_creative_style,
		'design_hash_list' => $design_hash_list
	));

	return $pdo->lastInsertId(); // Возвращает номер последней добавленной записи: Необходим для создания новой папки дизайна
}
// Функция обновления информации в базе для существующего дизайна (Креатив не меняется, т.к. дизайн прикреплен к креативу)
function UpdateDesignInfo($pdo, $design_id, $design_name, $design_source_url, $design_creative_style, $design_hash_list){
	$stmt = $pdo->prepare("UPDATE designes SET user_id = :user_id, design_name = :design_name, design_source_url = :design_source_url, design_creative_style = :design_creative_style, design_hash_list = :design_hash_list WHERE design_id = :design_id");
	$stmt->execute(array(
		'user_id'=>$user_id,
		'design_name'=>$design_name,
		'design_source_url'=>$design_source_url,
		'design_creative_style'=>$design_creative_style,
		'design_hash_list' => $design_hash_list,
		'design_id'=>$design_id 
	));
	return $design_id;
}


if(isset($_POST['design_id'])){
	echo "Обновление";
	// Обновление
	// Загружаем информацию в базу
	$designFolderPath = UpdateDesignInfo($pdo, $design_id, $design_name, $design_source_url, $design_creative_style, $design_hash_list);
}else{
	echo "Создание";
	// Создание	
	// Загружаем информацию в базу
	$designFolderPath = InsertDesignInfo($pdo, $creative_id, $user_id, $design_name, $design_source_url, $design_creative_style, $design_hash_list);
	// Создаем папку, если ее не существует для загрузки файлов
	if (!file_exists(DESIGN_FOLDER.$designFolderPath)) {
		mkdir(DESIGN_FOLDER.$designFolderPath, 0777, true);
	}
}

// Подключаем модуль загрузки файлов
include_once($_SERVER['DOCUMENT_ROOT']."/Modules/LibraryEdit/FilesLoad.php"); // Модуль загрузки файлов

// echo $design_hash_list;
?>