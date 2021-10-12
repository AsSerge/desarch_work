<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта

// Определяем добавление это ИЛИ обновление для голосования
$user_id = $_POST['user_id'];
$creative_id = $_POST['creative_id'];
$creative_grade_pos = $_POST['creative_grade_pos'];
$creative_comment_content = $_POST['creative_comment_content'];
$rejectionReason = $_POST['rejectionReason']; // Причина отклонения креатива

// Креатив или утверждается на этом этапе, или отправляется дизайнеру на доработку, или отпраляется на комиссию для принятия решения о покупке дизайна

if($creative_grade_pos == "on"){
	$creative_end_date = date("Y-m-d");
	$stmt = $pdo->prepare("UPDATE сreatives SET creative_status = :creative_status, creative_end_date = :creative_end_date WHERE creative_id = :creative_id");
	$stmt->execute(array(
		'creative_status'=>'Принят',
		'creative_end_date'=>$creative_end_date,
		'creative_id'=>$creative_id
		));

}elseif($creative_grade_pos == "off"){
	$stmt = $pdo->prepare("UPDATE сreatives SET creative_status = :creative_status WHERE creative_id = :creative_id");
	$stmt->execute(array(
		'creative_status'=>'На доработке',
		'creative_id'=>$creative_id
		));

}elseif($creative_grade_pos == "check"){
	$stmt = $pdo->prepare("UPDATE сreatives SET creative_status = :creative_status WHERE creative_id = :creative_id");
	$stmt->execute(array(
		'creative_status'=>'На утверждении',
		'creative_id'=>$creative_id
		));

}
// Запись комментариев
if($_POST['rejectionReason'] != "" AND $_POST['creative_comment_content'] != ""){
	
	$stmt = $pdo->prepare("INSERT INTO сreative_сomments SET user_id = :user_id, creative_id = :creative_id, creative_comment_focus = :creative_comment_focus,  creative_comment_content = :creative_comment_content");
	$updated_content = "[".$rejectionReason."] ".$creative_comment_content; // Пишем в комментарий причину и суть комментария
	$stmt->execute(array(
		'user_id'=>$user_id,
		'creative_id'=>$creative_id,
		'creative_comment_content'=>$updated_content,
		'creative_comment_focus'=>'negative'
	));
	
	$infoTag .= " Записали комментарий";

}elseif($_POST['rejectionReason'] == "" AND $_POST['creative_comment_content'] != ""){

	$stmt = $pdo->prepare("INSERT INTO сreative_сomments SET user_id = :user_id, creative_id = :creative_id, creative_comment_focus = :creative_comment_focus,  creative_comment_content = :creative_comment_content");	
	$stmt->execute(array(
		'user_id'=>$user_id,
		'creative_id'=>$creative_id,
		'creative_comment_content'=>$creative_comment_content,
		'creative_comment_focus'=>'positive'
	));

	$infoTag .= " Нет комментария";
}

elseif($_POST['rejectionReason'] == "" AND $_POST['creative_comment_content'] == ""){
	// Автокомментарий при принятии дизайна постановщиком задачи
	$stmt = $pdo->prepare("INSERT INTO сreative_сomments SET user_id = :user_id, creative_id = :creative_id, creative_comment_focus = :creative_comment_focus,  creative_comment_content = :creative_comment_content");	
	$stmt->execute(array(
		'user_id'=>$user_id,
		'creative_id'=>$creative_id,
		'creative_comment_content'=>'Дизайн принят постановщиком задачи.',
		'creative_comment_focus'=>'positive'
	));

	$infoTag .= " Автокомментарий";
}


echo ">> ".$infoTag;

?>