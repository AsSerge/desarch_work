<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта
require_once($_SERVER['DOCUMENT_ROOT']."/Assets/fpdf/fpdf.php"); // fpdf
define('FPDF_FONTPATH', $_SERVER['DOCUMENT_ROOT'].'/Assets/fpdf/font/'); // Шрифты для fpdf
$creative_id = $_POST['creative_id'];

// Получаем информацию для формирования PDF
$stmt = $pdo->prepare("SELECT * FROM сreatives as C LEFT JOIN tasks AS T ON (C.task_id = T.task_id) LEFT JOIN users AS U ON (C.user_id = U.user_id) WHERE creative_id = ?");
$stmt->execute(array($creative_id));
$crt = $stmt->fetch(PDO::FETCH_ASSOC);

// Функция определения параметров заказчика
function Customer($pdo, $customer_id){
	$stmt = $pdo->prepare("SELECT customer_name, customer_type FROM customers WHERE customer_id = ?");
	$stmt->execute(array($customer_id));
	$customer = $stmt->fetch(PDO::FETCH_ASSOC);
	return $customer;
}

// Функция определения количества дизайнов в креативе
function GetDisignesCount($pdo, $creative_id){
	$stmt = $pdo->prepare("SELECT COUNT(*) FROM designes WHERE creative_id = ?");
	$stmt->execute(array($creative_id));
	$count = $stmt->fetchColumn();
	return $count; 
}

// Функция получения массива изображений

function GetImagesArr($dir, $creative_id){
	$file = [];
	$sc_dir = $dir.$creative_id;
	$files = scandir($sc_dir);
	foreach ($files as $values){
		// Выводим только файлы-изображения JPEG кроме preview.jpg
		if($values != "." AND $values != ".." AND $values != "preview.jpg" AND $values != "thumb_preview.jpg"){
			if(exif_imagetype($sc_dir."/".$values) == IMAGETYPE_JPEG){
				$file[] = "/Creatives/".$creative_id."/".$values;
			}
		}
	}
	return $file; 
}


$creative_date = mysql_to_date($crt['creative_end_date']);
$autor = "Дизайнер: ". $crt['user_name']. " ". $crt['user_surname']; // Автор
$creative_name = "Паспорт дизайна ". $crt['creative_name']; // Газвание креатива
$perv_image = $_SERVER['DOCUMENT_ROOT']."/Creatives/".$creative_id."/preview.jpg"; // Главный файл
$images = GetImagesArr($_SERVER['DOCUMENT_ROOT']."/Creatives/", $creative_id); // Базовые файлы
$customer = "Заказчик: " . Customer($pdo, $crt['customer_id'])['customer_name'] . " (".Customer($pdo, $crt['customer_id'])['customer_type'].")";
$link = "Источник исходных изображений: " . $crt['creative_source']; 
$category = "Категория: " . $crt['creative_style'];
$description = "Описание: " . $crt['creative_description'];


//Создаем титульную страницу
$pdf = new FPDF('L','mm','A4');
$pdf->AddPage();
$pdf->AddFont('Montserrat-Regular','','Montserrat-Regular.php');
$pdf->AddFont('Montserrat-Bold','B','Montserrat-SemiBold.php');

$pdf->SetFont('Montserrat-Bold','B',15);
$pdf->SetXY(260,10);
$pdf->Write(0,iconv('utf-8', 'windows-1251' ,$creative_date));

$pdf->SetFont('Montserrat-Bold','B',15);
$pdf->SetXY(10,10);
$pdf->Write(0,iconv('utf-8', 'windows-1251',$creative_name));

$pdf->SetFont('Montserrat-Regular','',10);
$pdf->SetXY(10,17);
$pdf->Write(0,iconv('utf-8', 'windows-1251', $autor));

$pdf->SetFont('Montserrat-Regular','',10);
$pdf->SetXY(10,135);
$pdf->Write(0,iconv('utf-8', 'windows-1251', $customer));

$pdf->SetFont('Montserrat-Regular','',10);
$pdf->SetXY(10,140);
$pdf->Write(0,iconv('utf-8', 'windows-1251', $link));

$pdf->SetFont('Montserrat-Regular','',10);
$pdf->SetXY(10,145);
$pdf->Write(0,iconv('utf-8', 'windows-1251', $category));

$pdf->SetFont('Montserrat-Regular','',10);
$pdf->SetXY(10,150);
$pdf->Write(5,iconv('utf-8', 'windows-1251', $description));



//Вставляем картинку: путь, отступ x, отступ y, ширина картинки
$pdf->Image($perv_image, 6, 20, 150 );
$line = 160;
foreach($images as $img){
	$pdf->Image($_SERVER['DOCUMENT_ROOT'].$img, $line, 25, 50 );
	$line += 55;
}

//Выводим PDF в браузер
$pdf->Output( $_SERVER['DOCUMENT_ROOT']."/report.pdf", "I" );
?>