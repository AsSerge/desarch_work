<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта

// Определяем добавление это ИЛИ обновление для голосования
$user_id = $_POST['user_id'];
$creative_id = $_POST['creative_id'];
$creative_grade_pos = $_POST['creative_grade_pos'];
$creative_comment_content = $_POST['creative_comment_content'];
$rejectionReason = $_POST['rejectionReason']; // Причина отклонения креатива


// Функция провекри позиции ()
function GetGradesDataCount($pdo, $creative_id, $user_id){
	$stmt = $pdo->prepare("SELECT * FROM сreative_grades WHERE creative_id = :creative_id AND user_id = :user_id");
	$stmt->execute(array(
		'creative_id'=>$creative_id,
		'user_id'=>$user_id
	));
	return $stmt->rowCount();
}

// Функция проверки количества положительных оценок за креатив. При принятии 3х положительных оценок - креатив - принят: Закупка разрешена
function GetGradesOnCount($pdo, $creative_id){
	$stmt = $pdo->prepare("SELECT * FROM сreative_grades WHERE creative_id = :creative_id AND creative_grade_pos = :creative_grade_pos");
	$stmt->execute(array(
		'creative_id'=>$creative_id,
		'creative_grade_pos'=>'on'
	));
	$GradesOn = $stmt->rowCount(); // Количество положительных оценок
	if($GradesOn == 3){

		$today = date('Y-m-d'); // Текущее число
		$stmt = $pdo->prepare("UPDATE сreatives SET creative_status = :a, creative_end_date = :b WHERE creative_id =:c");
		$stmt->execute(array(
			'a'=>'Принят',
			'b'=>$today,
			'c'=>$creative_id
		));
	}
}

if(GetGradesDataCount($pdo, $creative_id, $user_id) > 0){
	// Если такая запись существовала - апдейтим ее
	if($creative_grade_pos == "on"){
		// Если креатив утверждается членом комиссии
		$stmt = $pdo->prepare("UPDATE сreative_grades SET creative_grade_pos = :creative_grade_pos WHERE creative_id = :creative_id AND user_id = :user_id");
		$stmt->execute(array(
			'creative_grade_pos'=>$creative_grade_pos,
			'creative_id'=>$creative_id,
			'user_id'=>$user_id
		));
		$infoTag = "Обновили";

		// Проверяем количество положительных оценок за креатив. Если оно равно 4 - креативу присваевается статус "Принят"
		GetGradesOnCount($pdo, $creative_id);
	}else{
		// Если креатив Отклоняется членом комиссииии - ВСЕ результаты голосования по нему обнуляются и креативу присваиваеися статус "На доработке"
		$stmt = $pdo->prepare("DELETE FROM сreative_grades WHERE creative_id = ?");
		$stmt->execute(array($creative_id));
		$infoTag = "Удалили!";

		$stmt = $pdo->prepare("UPDATE сreatives SET creative_status = :a WHERE creative_id =:b");
		$stmt->execute(array(
			'a'=>'На доработке',
			'b'=>$creative_id
		));
	}

}else{
	// Если нет - создаем
	if($creative_grade_pos == "on"){
		$stmt = $pdo->prepare("INSERT INTO сreative_grades SET user_id = :user_id, creative_id = :creative_id, creative_grade_pos = :creative_grade_pos");
		$stmt->execute(array(
			'user_id'=>$user_id,
			'creative_id'=>$creative_id,
			'creative_grade_pos'=>$creative_grade_pos
		));
		$infoTag = "Добавили";
		// Проверяем количество положительных оценок за креатив. Если оно равно 4 - креативу присваевается статус "Принят"
		GetGradesOnCount($pdo, $creative_id);
	}else{
		// Если креатив Отклоняется членом комиссииии - ВСЕ результаты голосования по нему обнуляются и креативу присваиваеися статус "На доработке"
		$stmt = $pdo->prepare("DELETE FROM сreative_grades WHERE creative_id = ?");
		$stmt->execute(array($creative_id));
		$infoTag = "Удалили все предыдущие записи!";

		$stmt = $pdo->prepare("UPDATE сreatives SET creative_status = :a WHERE creative_id =:b");
		$stmt->execute(array(
			'a'=>'На доработке',
			'b'=>$creative_id
		));
	}

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

echo ">> ".$infoTag;

?>