<?php
// Соединямся с БД
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
// Обновляем информацию о пользователе (для начала пароль и роль)
if(isset($_POST['update_user'])){
	// Обработка пароля
	if (isset($_POST['user_password']) and $_POST['user_password'] != ""){
		$user_id = $_POST['user_id'];
		// $user_password = md5(md5(trim($_POST['user_password'])));
		$user_password = password_hash(trim($_POST['user_password']), PASSWORD_BCRYPT);

		$stmt = $pdo->prepare("UPDATE users SET `user_password` = ?, `user_hash` = ? WHERE `user_id` = ?");
		$stmt->execute(array($user_password, "", $user_id));

		// Отправляем новый пароль по почте	
		// Отправитель: Администратор системы. Список получателей формируется из списка дизайнеров

		include_once($_SERVER['DOCUMENT_ROOT'].'/Assets/PHPMailer/PHPMailerFunction.php');
		// $mail - Адрес получателя
		// $subject - Тема сообщения
		// $message - Сообщение
		// $sender_mail - Почта отправителя
		// $sender_name - Имя отправителя

		$stmt = $pdo->prepare("SELECT user_login, user_name FROM users WHERE user_id = ?");
		$stmt->execute(array($user_id));	
		$arr = $stmt->fetch(PDO::FETCH_ASSOC);

		$m_mail = $arr['user_login'];
		$m_name = $arr['user_name'];

		$subject = 'Ваш новый пароль';
		$message = "Добрый день {$m_name}. Ваш новый пароль: ".$_POST['user_password'];
		$sender_mail = 'Tsvetkov-SA@grmp.ru';
		$sender_name = 'Администратор';

		SendMailGRMP($m_mail, $subject, $message, $sender_mail, $sender_name);
		}
		
	}else{
		echo "Пароль не меняется";
	}

	if(isset($_POST['user_superior']) and $_POST['user_superior'] != ""){
		$user_id = $_POST['user_id'];
		$user_superior = $_POST['user_superior'];
		$stmt = $pdo->prepare("UPDATE users SET `user_superior` = ? WHERE `user_id` = ?");
		$stmt->execute(array($user_superior, $user_id));
	}
?>