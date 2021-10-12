<?php
header('Content-Type: application/x-javascript; charset=utf8');
require_once ($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php");
// Получаем ID креатива
$creative_id = $_GET['creative_id'];

// Функция получения массива файлов-изображений из заданной папки
function GetImagesArr($dir, $id){
	$file = [];
	$sc_dir = $dir.$id;
	$files = scandir($sc_dir);
	foreach ($files as $values){
		// Выводим только файлы-изображения JPEG кроме preview.jpg		
		if($values != "." AND $values != ".." AND $values != "preview.jpg"){
			if(exif_imagetype($sc_dir."/".$values) == IMAGETYPE_JPEG){
				$file[] = "/Creatives/".$id."/".$values;
			}	
		}
	}
	return $file; 
}
// Формируем массив базовых исзобажений для задачи
$cr_files = GetImagesArr(CREATIVE_FOLDER, $creative_id);
// Формируем JSON
echo json_encode($cr_files);
?>