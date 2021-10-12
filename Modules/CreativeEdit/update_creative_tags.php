<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта

$creative_id = $_POST['creative_id'];
$tags = $_POST['tags'];
if(is_array($tags) AND count($tags) !=0){
	foreach($tags as $tag){
		$creative_hash_list .= $tag . "|";
	}
	$creative_hash_list = substr($creative_hash_list, 0,-1);
}else{
	$creative_hash_list = "";
}

$stmt = $pdo->prepare("UPDATE сreatives SET creative_hash_list = :creative_hash_list WHERE creative_id = :creative_id"); 
$stmt->execute(array(
	'creative_hash_list'=>$creative_hash_list,
	'creative_id'=>$creative_id
)	
);
echo $creative_hash_list;
?>