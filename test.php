<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
$user_id = "67";

		$stmt = $pdo->prepare("SELECT user_login, user_name FROM users WHERE user_id = ?");
		$stmt->execute(array($user_id));	
		$arr = $stmt->fetch(PDO::FETCH_ASSOC);
		

		echo $arr['user_login'];
		echo $arr['user_name'];
		

?>