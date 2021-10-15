<?php
// Утверждение креатива
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo


// $stmt = $pdo->prepare("SELECT D.design_id, D.design_name, D.design_source_url, D.design_creative_style, D.design_update, U.user_name, U.user_surname  FROM designes as D LEFT JOIN users AS U ON (D.user_id = U.user_id) WHERE 1");
$stmt = $pdo->prepare("SELECT B.log_datetime, U.user_name, U.user_surname, C.creative_name, B.log_content
		FROM base_logs as B 
		LEFT JOIN users AS U ON (B.user_id = U.user_id) 
		LEFT JOIN сreatives AS C ON (B.creative_id = C.creative_id)");
$stmt->execute();
$designes = $stmt->fetchAll(PDO::FETCH_ASSOC); 

echo "<pre>";
print_r($designes);
echo "</pre>";
// echo json_encode($creative_status);
?>