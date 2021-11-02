<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта

// Определяем добавление это ИЛИ обновление для голосования
$user_id = $_POST['user_id'];
$creative_id = $_POST['creative_id'];
$creative_grade_pos = $_POST['creative_grade_pos'];
$creative_comment_content = $_POST['creative_comment_content'];
$rejectionReason = $_POST['rejectionReason']; // Причина отклонения креатива


// Функция для получения информации о разработчике дизайна

function GetDesignerInfo($pdo, $creative_id){
	$stmtd = $pdo->prepare("SELECT user_login FROM users AS U LEFT JOIN сreatives AS C ON (U.user_id = C.user_id) WHERE creative_id = ?");
	$stmtd->execute(array($creative_id));
	$designer_mail = $stmtd->fetch(PDO::FETCH_COLUMN);
	return $designer_mail;
}

// Функция для получения информации о пользователе (отправка почты)
function GetUserInfo($pdo, $user_id){
	$stmtu = $pdo->prepare("SELECT user_login, user_name, user_surname FROM users WHERE user_id = ?");
	$stmtu->execute(array($user_id));
	$user_mail = $stmtu->fetch(PDO::FETCH_ASSOC);
	return $user_mail;
}


// Креатив или утверждается на этом этапе, или отправляется дизайнеру на доработку, или отпраляется на комиссию для принятия решения о покупке дизайна
if($creative_grade_pos == "on"){
	$creative_end_date = date("Y-m-d");
	$stmt = $pdo->prepare("UPDATE сreatives SET creative_status = :creative_status, creative_end_date = :creative_end_date WHERE creative_id = :creative_id");
	$stmt->execute(array(
		'creative_status'=>'Принят',
		'creative_end_date'=>$creative_end_date,
		'creative_id'=>$creative_id
		));
		WriteLog($pdo, $creative_id, $user_id, "Креатив принят");// Запись лога
	// Отправляем письмо дизайнеру креатива о принятии креатива
	// Отправитель: Постановщик задачи. Список получателей формируется из списка членов комиссии

	include_once($_SERVER['DOCUMENT_ROOT'].'/Assets/PHPMailer/PHPMailerFunction.php');
	
	$subject = '[Design Library] Креатив принят!';
	$message = 'Добрый день. Креатив принят!<br>';
	$message .= 'C уважением, ' . GetUserInfo($pdo, $user_id)['user_name']. " ". GetUserInfo($pdo, $user_id)['user_surname'];
	$sender_mail = GetUserInfo($pdo, $user_id)['user_login'];
	$sender_name = GetUserInfo($pdo, $user_id)['user_name']. " ". GetUserInfo($pdo, $user_id)['user_surname'];	
	
	SendMailGRMP(GetDesignerInfo($pdo, $creative_id), $subject, $message, $sender_mail, $sender_name);

}elseif($creative_grade_pos == "buy"){
	$creative_end_date = date("Y-m-d");
	$stmt = $pdo->prepare("UPDATE сreatives SET creative_status = :creative_status, creative_end_date = :creative_end_date WHERE creative_id = :creative_id");
	$stmt->execute(array(
		'creative_status'=>'Покупка',
		'creative_end_date'=>$creative_end_date,
		'creative_id'=>$creative_id
		));
		WriteLog($pdo, $creative_id, $user_id, "Разрешена покупка дизайна руководителем");// Запись лога

	// Отправляем письмо дизайнеру креатива о разрешении покупки креатива
	// Отправитель: Постановщик задачи. Список получателей формируется из списка членов комиссии

	include_once($_SERVER['DOCUMENT_ROOT'].'/Assets/PHPMailer/PHPMailerFunction.php');
	
	$subject = '[Design Library] Разрешена покупка креатива!';
	$message = 'Добрый день. Покупку креатива разрешаю!<br>';
	$message .= 'C уважением, ' . GetUserInfo($pdo, $user_id)['user_name']. " ". GetUserInfo($pdo, $user_id)['user_surname'];
	$sender_mail = GetUserInfo($pdo, $user_id)['user_login'];
	$sender_name = GetUserInfo($pdo, $user_id)['user_name']. " ". GetUserInfo($pdo, $user_id)['user_surname'];	
	
	SendMailGRMP(GetDesignerInfo($pdo, $creative_id), $subject, $message, $sender_mail, $sender_name);


}elseif($creative_grade_pos == "off"){
	$stmt = $pdo->prepare("UPDATE сreatives SET creative_status = :creative_status WHERE creative_id = :creative_id");
	$stmt->execute(array(
		'creative_status'=>'На доработке',
		'creative_id'=>$creative_id
		));
		WriteLog($pdo, $creative_id, $user_id, "Креатив отпрален на доработку");// Запись лога

	// Отправляем письмо дизайнеру креатива о необходимости доработки
	// Отправитель: Постановщик задачи. Список получателей формируется из списка членов комиссии

	include_once($_SERVER['DOCUMENT_ROOT'].'/Assets/PHPMailer/PHPMailerFunction.php');
	
	$subject = '[Design Library] Доработка креатива!<br>';
	$message = 'Добрый день. Необходима дорабока креатива!';	
	$sender_mail = GetUserInfo($pdo, $user_id)['user_login'];
	$sender_name = GetUserInfo($pdo, $user_id)['user_name']. " ". GetUserInfo($pdo, $user_id)['user_surname'];	
	
	SendMailGRMP(GetDesignerInfo($pdo, $creative_id), $subject, $message, $sender_mail, $sender_name);

}elseif($creative_grade_pos == "check"){
	$stmt = $pdo->prepare("UPDATE сreatives SET creative_status = :creative_status WHERE creative_id = :creative_id");
	$stmt->execute(array(
		'creative_status'=>'На утверждении',
		'creative_id'=>$creative_id
		));
		WriteLog($pdo, $creative_id, $user_id, "Креатив отпрвален на комиссию");// Запись лога

	// Отправляем письмо всем членам комиссии о поступлении нового креатива для рассмотрения
	// Отправитель: Постановщик задачи. Список получателей формируется из списка членов комиссии

	include_once($_SERVER['DOCUMENT_ROOT'].'/Assets/PHPMailer/PHPMailerFunction.php');
	// $mail - Адрес получателя
	// $subject - Тема сообщения
	// $message - Сообщение
	// $sender_mail - Почта отправителя
	// $sender_name - Имя отправителя

	$subject = '[Design Library] Новый креатив для рассмотрения!';
	$message = 'Добрый день. Добавлены новые креативы для рассмотрения!<br>';
	$message .= 'Адрес для просмотра креативов: http://desarch.dmtextile.ru/';
	$sender_mail = GetUserInfo($pdo, $user_id)['user_login'];
	$sender_name = GetUserInfo($pdo, $user_id)['user_name']. " ". GetUserInfo($pdo, $user_id)['user_surname'];


	$stmt = $pdo->prepare("SELECT user_login FROM users WHERE user_role = 'ctr'");
	$stmt->execute();	
	$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	foreach($members as $mbr){
		SendMailGRMP($mbr['user_login'], $subject, $message, $sender_mail, $sender_name);
	}
	WriteLog($pdo, $creative_id, $user_id, "Отправлено сообщение членам комиссии.");// Запись лога
}


// Запись комментариев ПОСТАНОВЩИК ЗАДАЧИ
if($creative_grade_pos == "on"){	
	$updated_content = ($creative_comment_content != "") ? "[Креатив принят постановщиком задачи] ". $creative_comment_content : "[Креатив принят постановщиком задачи]";
	$stmt = $pdo->prepare("INSERT INTO сreative_сomments SET user_id = :user_id, creative_id = :creative_id, creative_comment_focus = :creative_comment_focus,  creative_comment_content = :creative_comment_content");
	$stmt->execute(array(
		'user_id'=>$user_id,
		'creative_id'=>$creative_id,
		'creative_comment_content'=>$updated_content,
		'creative_comment_focus'=>'positive'
	));
	$infoTag .= "Принят";
}elseif($creative_grade_pos == "off"){
	$updated_content = ($creative_comment_content != "") ? "[".$rejectionReason."] ". $creative_comment_content : "[".$rejectionReason."]";
	$stmt = $pdo->prepare("INSERT INTO сreative_сomments SET user_id = :user_id, creative_id = :creative_id, creative_comment_focus = :creative_comment_focus,  creative_comment_content = :creative_comment_content");
	$stmt->execute(array(
		'user_id'=>$user_id,
		'creative_id'=>$creative_id,
		'creative_comment_content'=>$updated_content,
		'creative_comment_focus'=>'negative'
	));
	$infoTag .= "Отправлен на доработку";
}elseif($creative_grade_pos == "check"){
	$updated_content = ($creative_comment_content != "") ? "[Креатив отправлен на согласование] ". $creative_comment_content : "[Креатив отправлен на согласование]";
	$stmt = $pdo->prepare("INSERT INTO сreative_сomments SET user_id = :user_id, creative_id = :creative_id, creative_comment_focus = :creative_comment_focus,  creative_comment_content = :creative_comment_content");
	$stmt->execute(array(
		'user_id'=>$user_id,
		'creative_id'=>$creative_id,
		'creative_comment_content'=>$updated_content,
		'creative_comment_focus'=>'negative'
	));
	$infoTag .= "Отправлен на комиссию";
}elseif($creative_grade_pos == "buy"){
	$updated_content = ($creative_comment_content != "") ? "[Разрешена покупка креатива] ". $creative_comment_content : "[Разрешена покупка креатива]";
	$stmt = $pdo->prepare("INSERT INTO сreative_сomments SET user_id = :user_id, creative_id = :creative_id, creative_comment_focus = :creative_comment_focus,  creative_comment_content = :creative_comment_content");
	$stmt->execute(array(
		'user_id'=>$user_id,
		'creative_id'=>$creative_id,
		'creative_comment_content'=>$updated_content,
		'creative_comment_focus'=>'positive'
	));
	$infoTag .= "Отправлен на комиссию";
}



echo ">> ".$infoTag;

?>