<?php
// Живые логи
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта


$stmt = $pdo->prepare("SELECT B.log_datetime, U.user_name, U.user_surname, C.creative_name, B.log_content
		FROM base_logs as B 
		LEFT JOIN users AS U ON (B.user_id = U.user_id) 
		LEFT JOIN сreatives AS C ON (B.creative_id = C.creative_id) ORDER BY B.log_datetime DESC");
$stmt->execute();
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC); 
echo json_encode($logs);
?>