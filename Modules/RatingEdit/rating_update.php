<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта

// Определяем добавление это ИЛИ обновление для голосования
$user_id = $_POST['user_id'];
$creative_id = $_POST['creative_id'];
$creative_grade_pos = $_POST['creative_grade_pos'];
$creative_comment_content = $_POST['creative_comment_content'];

// Функция управления статусом креатива
function SetCreativeStatus($pdo, $creative_id){
	// Находим дату
	$creative_end_date = date("Y-m-d");
	// Получаем массив для голосования
	$stmt = $pdo->prepare("SELECT creative_grade_pos FROM сreative_grades WHERE creative_id = ?");
	$stmt->execute(array($creative_id));
	$grade_array = $stmt->fetchAll(PDO::FETCH_COLUMN);
	// Если проголосовали ВСЕ три члена комиссии - начинаем рулить статусом
	if(count($grade_array) >= 3){
		$grade = array_count_values($grade_array);
		if($grade['buy'] > $grade['on']){
			$newstatus = "Покупка";
		}else{
			$newstatus = "Принят";
		}
		$stmts = $pdo->prepare("UPDATE сreatives SET creative_end_date = :creative_end_date, creative_status = :creative_status WHERE creative_id = :creative_id");
		$stmts->execute(array(
			'creative_end_date'=> $creative_end_date,
			'creative_status' => $newstatus, 
			'creative_id' => $creative_id
		));
	}	
};


// Проверяем статус креатива (должен быть в статусе На утверждении) - только в этом статусе возможно голосование
$stmt = $pdo->prepare("SELECT creative_status FROM сreatives WHERE creative_id = ?");
$stmt->execute(array($creative_id));
$creative_status = $stmt->fetch(PDO::FETCH_COLUMN);

if($creative_status == 'На утверждении'){
	if($creative_grade_pos == "on"){
		// $stmt = $pdo->prepare("UPDATE сreative_grades SET creative_grade_pos = :creative_grade_pos WHERE creative_id = :creative_id AND user_id = :user_id");
		$stmt = $pdo->prepare("INSERT INTO сreative_grades SET creative_grade_pos = :creative_grade_pos, creative_id = :creative_id, user_id = :user_id");
		$stmt->execute(array(
			'creative_grade_pos'=>$creative_grade_pos,
			'creative_id'=>$creative_id,
			'user_id'=>$user_id
		));
		WriteLog($pdo, $creative_id, $user_id, "Креатив принят членом комиссиии без закупки");// Запись лога
		$infoTag = "Креатива принят членом комиссии без закупки";

	}elseif($creative_grade_pos == "buy"){
		// $stmt = $pdo->prepare("UPDATE сreative_grades SET creative_grade_pos = :creative_grade_pos WHERE creative_id = :creative_id AND user_id = :user_id");
		$stmt = $pdo->prepare("INSERT INTO сreative_grades SET creative_grade_pos = :creative_grade_pos, creative_id = :creative_id, user_id = :user_id");
		$stmt->execute(array(
			'creative_grade_pos'=>$creative_grade_pos,
			'creative_id'=>$creative_id,
			'user_id'=>$user_id
		));
		WriteLog($pdo, $creative_id, $user_id, "Закупка креатива разрешена членом комиссии");// Запись лога
		$infoTag = "Необходима закупка дизайна для данноого креатива";

	}

	// Запись комментариев
	if($creative_grade_pos == "on"){	
		$updated_content = ($creative_comment_content != "") ? "[Дизайны разрешено не покупать] ". $creative_comment_content : "[Дизайны разрешено не покупать]";
		$stmt = $pdo->prepare("INSERT INTO сreative_сomments SET user_id = :user_id, creative_id = :creative_id, creative_comment_focus = :creative_comment_focus,  creative_comment_content = :creative_comment_content");
		$stmt->execute(array(
			'user_id'=>$user_id,
			'creative_id'=>$creative_id,
			'creative_comment_content'=>$updated_content,
			'creative_comment_focus'=>'positive'
		));
		$infoTag .= "Принят без покупки";
	}elseif($creative_grade_pos == "buy"){
		$updated_content = ($creative_comment_content != "") ? "[Покупка дизайнов разрешена] ". $creative_comment_content : "[Покупка дизайнов разрешена]";
		$stmt = $pdo->prepare("INSERT INTO сreative_сomments SET user_id = :user_id, creative_id = :creative_id, creative_comment_focus = :creative_comment_focus,  creative_comment_content = :creative_comment_content");
		$stmt->execute(array(
			'user_id'=>$user_id,
			'creative_id'=>$creative_id,
			'creative_comment_content'=>$updated_content,
			'creative_comment_focus'=>'positive'
		));
		$infoTag .= "Необходима покупка стоковых дизайнов";
	}

	echo ">> ".$infoTag;

	// Функция управления статусом креатива
	SetCreativeStatus($pdo, $creative_id);
}
?>