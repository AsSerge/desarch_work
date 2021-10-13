<?php
		$user_id = 62;
		include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
		$stmt = $pdo->prepare("SELECT user_login, user_name FROM users WHERE user_id = ?");
		$stmt->execute(array($user_id));	
		$mail = $stmt->fetch(PDO::FETCH_ASSOC);

		echo $mail['user_login']." ".$mail['user_name'];
		// echo "<pre>";
		// print_r($mail);
		// echo "</pre>";

		
		echo time();
		

?>