<?php
// Удаление картинки из папки
require_once ($_SERVER['DOCUMENT_ROOT'].'/Layout/settings.php');
header('Content-Type: application/x-javascript; charset=utf8');
$ImgToDel = $_GET['ImgToDel'];

if($ImgToDel != ""){
	$ImgToDel = $_SERVER['DOCUMENT_ROOT'].$ImgToDel;
	unlink($ImgToDel);
}
?>