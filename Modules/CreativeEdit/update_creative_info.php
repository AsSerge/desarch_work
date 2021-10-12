<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта

$creative_id = $_POST['creative_id'];
$creative_name = ($_POST['creative_name'] != "") ? $_POST['creative_name'] : "Новый креатив";
$creative_style = $_POST['creative_style'];
$creative_development_type = $_POST['creative_development_type'];
$creative_magnitude = $_POST['creative_magnitude'];
$creative_source = $_POST['creative_source'];
$creative_description = $_POST['creative_description'];

$stmt = $pdo->prepare("UPDATE сreatives SET 
				creative_name = :creative_name,
				creative_style = :creative_style,
				creative_development_type = :creative_development_type,
				creative_magnitude = :creative_magnitude,
				creative_source = :creative_source,
				creative_description = :creative_description 
				WHERE creative_id = :creative_id
");
$stmt->execute(array(
	'creative_name'=>$creative_name,
	'creative_style'=>$creative_style,
	'creative_development_type'=>$creative_development_type,
	'creative_magnitude'=>$creative_magnitude,
	'creative_source'=>$creative_source,
	'creative_description'=>$creative_description,
	'creative_id'=>$creative_id
	)
);

?>