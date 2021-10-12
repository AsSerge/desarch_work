<?php
// Проверка на существоваени файла preview.jpg
require_once ($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php");
$filename = CREATIVE_FOLDER.$_POST['creative_id']."/".$_POST['preview_file'];
if(file_exists($filename)){
	echo "YES";
}else{
	echo "NO";
}
?>