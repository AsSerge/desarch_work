<?php
// Утверждение креатива
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo

$creative_id = '1';

function GetDesignerInfo($pdo, $creative_id){
	$stmtd = $pdo->prepare("SELECT user_login FROM users AS U LEFT JOIN сreatives AS C ON (U.user_id = C.user_id) WHERE creative_id = ?");
	$stmtd->execute(array($creative_id));
	$user_mail = $stmtd->fetch(PDO::FETCH_COLUMN);
	return $user_mail;
}

echo "<pre>";
print_r(GetDesignerInfo($pdo, $creative_id));
echo "</pre>";

?>