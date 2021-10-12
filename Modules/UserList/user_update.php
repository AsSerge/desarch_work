<?php
// Соединямся с БД
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
// Обновляем информацию о пользователе (для начала пароль и роль)
if(isset($_POST['update_user'])){
	// Обработка пароля
	if (isset($_POST['user_password']) and $_POST['user_password'] != ""){
		$user_id = $_POST['user_id'];
		$user_password = md5(md5(trim($_POST['user_password'])));

		$stmt = $pdo->prepare("UPDATE users SET `user_password` = ?, `user_hash` = ? WHERE `user_id` = ?");
		$stmt->execute(array($user_password, "", $user_id));
		
	}else{
		echo "Пароль не меняется";
	}

	if(isset($_POST['user_superior']) and $_POST['user_superior'] != ""){
		$user_id = $_POST['user_id'];
		$user_superior = $_POST['user_superior'];
		$stmt = $pdo->prepare("UPDATE users SET `user_superior` = ? WHERE `user_id` = ?");
		$stmt->execute(array($user_superior, $user_id));
	}	
}


?>