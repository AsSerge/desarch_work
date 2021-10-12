<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fas fa-poll" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Дизайны для голосования</h6>
				<small><?php echo $user_name." " .$user_surname. " [".$user_role_description."]";?></small>
			</div>
</div>
<div class="my-3 p-3 bg-white rounded box-shadow">

<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта
// Получаем Креативов, готовый к утверждению
	$stmt = $pdo->prepare("SELECT * FROM сreatives as C LEFT JOIN users AS U ON (C.user_id = U.user_id) WHERE C.creative_status = ?");
	$stmt->execute(array("На утверждении"));

	$creatives = $stmt->fetchAll(PDO::FETCH_ASSOC);

	// Функция определения параметров задачи и заказчика по ID задачи
	function Customer($pdo, $task_id){
		$stmt = $pdo->prepare("SELECT * FROM tasks as T LEFT JOIN customers AS C ON (T.customer_id = C.customer_id) WHERE T.task_id = ?");
		$stmt->execute(array($task_id));
		$customer = $stmt->fetch(PDO::FETCH_ASSOC);
		return $customer;
	}
	// Функция провекри позиции ()
	function GetGradesDataCount($pdo, $creative_id, $user_id){
		$stmt = $pdo->prepare("SELECT * FROM сreative_grades WHERE creative_id = :creative_id AND user_id = :user_id");
		$stmt->execute(array(
			'creative_id'=>$creative_id,
			'user_id'=>$user_id
		));
		return $stmt->rowCount();
	}

	// Функция проверки количества положительных оценок за креатив
	function GetGradesOnCount($pdo, $creative_id){
		$stmt = $pdo->prepare("SELECT * FROM сreative_grades WHERE creative_id = :creative_id AND creative_grade_pos = :creative_grade_pos");
		$stmt->execute(array(
			'creative_id'=>$creative_id,
			'creative_grade_pos'=>'on'
		));
		return $stmt->rowCount(); // Количество положительных оценок 
	}

// echo "<pre>";
// print_r($creatives);
// echo "</pre>";


?>
<style>
	.MyCardDesk{
		display: flex;
		justify-content: flex-start;
		flex-wrap: wrap;
		font-size: 0.7rem;
	}
	.MyCardDesk > div{
		width: 15rem;
	}
	#ComissionGrades{
		position: relative;
		width: 100%;
		height: 3px;
		background-color: var(--purple);
		margin: 5px 0;
		display: flex;
	}
	#ComissionGrades > div{
		background-color: var(--yellow);
		width: 25%;
		height: 3px;
	}
	.card-text{
		margin: 0.4rem 0;
	}
</style>

	<div class="MyCardDesk">
	<?php
	// Настройка отображения карточек !!!! НАстроить потом
	$w='3';
	switch($w){
		case '1':
			$color_scheme = 'text-white bg-warning';
			$vote_btn = 'disabled';
			break;
		case '2':
			$color_scheme = '';
			$vote_btn = '';
			break;
		case '2':
			$color_scheme = '';
			$vote_btn = '';
			break;
		default:
			$color_scheme = 'bg-light';
			$vote_btn = '';
	}

	// Настройка ленточки проголосовавших


	$myKeyCount=0; // Ставим счетчик дизайнов
	foreach($creatives as $cr){
		
		$creative_development_type = ($cr['creative_development_type'] == "") ? 'Собственная разработка': 'Компиляция'; // ТИп разработки
		// Проверяем - голосовал ли проверяющий за этот креатив, если нет - отображаем его
		$myKey = GetGradesDataCount($pdo, $cr['creative_id'], $user_id);
		if($myKey != 1){
			$myKeyCNT = GetGradesOnCount($pdo, $cr['creative_id']);
			echo "<div class='card m-2 {$color_scheme}'>";
			echo "	<a href = '/index.php?module=RatingEdit&creative_id={$cr['creative_id']}'><img class='card-img-top' src='/Creatives/{$cr['creative_id']}/preview.jpg' alt=''></a>";
			echo "	<div class='card-body'>";
			echo "		<p class='card-text'><strong>Дизайн: </strong>[{$cr['creative_id']}] {$cr['creative_name']}</p>";
			echo "		<p class='card-text'><strong>Тип: </strong>{$creative_development_type}</p>";
			echo "		<p class='card-text'><strong>Статус: </strong>{$cr['creative_status']}</p>";
			echo "		<p class='card-text'><strong>Дизайнер: </strong>{$cr['user_surname']} {$cr['user_name']}</p>";
			echo "		<p class='card-text'><strong>Заказчик: </strong>".Customer($pdo, $cr['task_id'])['customer_name']."</p>";
			echo "		<p class='card-text'><strong>Канал: </strong>".Customer($pdo, $cr['task_id'])['customer_type']."</p>";		
			echo "	</div>";
			echo "<div id='ComissionGrades'>";
				for($i=0;$i<=$myKeyCNT;$i++){
					echo "<div></div>";
				}
			echo"</div>";
			echo "	<div class='card-footer text-center'>";
			echo "		<button type='button' onclick='window.location.href=`/index.php?module=RatingEdit&creative_id={$cr['creative_id']}`' class='btn btn-primary btn-sm' {$vote_btn}><i class='fas fa-balance-scale-right'></i> Оценка</button>";
			echo "	</div>";
			echo "</div>";
			$myKeyCount++;
		}
	}
	?>
	</div>
	<?php
	if($myKeyCount == 0){echo "<div class='alert alert-success' role='alert'>В настоящее время нет доступных дизайнов для голосования.</div>";}
	?>
</div>

<?php
	// echo "<pre>";
	// print_r($creatives);
	// echo "</pre>";
?>