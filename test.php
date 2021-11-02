<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта

// Определяем добавление это ИЛИ обновление для голосования
$user_id = '2';
$creative_id = '3';

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

	$subject = 'Новый креатив для рассмотрения!';
	$message = 'Добрый день. Добавлены новые креативы для рассмотрения!<br>';
	$message .= 'Адрес для просмотра креативов: http://desarch.dmtextile.ru/';
	$sender_mail = GetUserInfo($pdo, $user_id)['user_login'];
	$sender_name = GetUserInfo($pdo, $user_id)['user_name']. " ". GetUserInfo($pdo, $user_id)['user_surname'];

	// $sender_mail = 'Tsvetkov-SA@grmp.ru';
	// $sender_name = 'Administrator';


	$stmt = $pdo->prepare("SELECT user_login FROM users WHERE user_role = 'ctr'");
	$stmt->execute();	
	$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	// foreach($members as $mbr){
	// 	SendMailGRMP($mbr['user_login'], $subject, $message, $sender_mail, $sender_name);
	// }
	
	echo "<pre>";
	print_r(GetUserInfo($pdo, $user_id));
	echo "</pre>";

	echo GetUserInfo($pdo, $user_id);
	
	echo "===================================<br>";
	echo $sender_mail."<br>";
	echo $sender_name."<br>";
?>