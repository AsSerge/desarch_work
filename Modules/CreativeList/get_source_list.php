<?php
// Утверждение креатива
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта
$creative_id = $_POST['creative_id'];

// Функция чтения файлов в папке и вывод d JSON формате
function GetFileSourceNames($creative_id){
	$files = scandir(CREATIVE_SOURCE_FOLDER.$creative_id);	
	$file_names = array();
	foreach ($files as $value){
		if($value != "." AND $value != ".."){
			$file_names[] = $value;
		}
	}
	return $file_names;
}
$result = GetFileSourceNames($creative_id);
echo json_encode($result);
?>