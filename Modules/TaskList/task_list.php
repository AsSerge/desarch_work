<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fas fa-tasks" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Список задач</h6>
				<small><?php echo $user_name." " .$user_surname. " [".$user_role_description."]";?></small>
			</div>
</div>

<style>
	.MyAtt span{
		font-weight: 600;
		color: var(--red);
		cursor: pointer;
	}

	.MyAtt span:AFTER{
		content: "*";
		color: var(--red);
	}
</style>
<div class="my-3 p-3 bg-white rounded box-shadow">

<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта
// В зависимости от уровня доступа - выводим список
if($user_role == 'adm'){
	$stmt = $pdo->prepare("SELECT * FROM tasks as T LEFT JOIN customers AS C ON (T.customer_id = C.customer_id) WHERE 1");
	$stmt->execute();
}else{
	$stmt = $pdo->prepare("SELECT * FROM tasks as T LEFT JOIN customers AS C ON (T.customer_id = C.customer_id) WHERE T.user_id = ?");
	$stmt->execute(array($user_id));
}
// Выбираем все задачи для выбранного постановщика и определяем тип задачи по ID заказчика
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Индивидуальные настроки по типам ролей

// Фугкция для определения количества креативов разного типа в задаче
function GetCreativeNumbers($pdo, $task_id, $select_number){
	switch ($select_number){
		case "All":
			$stmt = $pdo->prepare("SELECT * FROM сreatives WHERE task_id = ?");
			break;
		case "Принят":
			$stmt = $pdo->prepare("SELECT * FROM сreatives WHERE task_id = ? AND creative_status = 'Принят'");
			break;
		case "В работе":
			$stmt = $pdo->prepare("SELECT * FROM сreatives WHERE task_id = ? AND creative_status = 'В работе'");
			break;
		case "На утверждении":
			$stmt = $pdo->prepare("SELECT * FROM сreatives WHERE task_id = ? AND creative_status = 'На утверждении'");
			break;
		case "На рассмотрении":
			$stmt = $pdo->prepare("SELECT * FROM сreatives WHERE task_id = ? AND creative_status = 'На рассмотрении'");
			break;
		case "На доработке":
			$stmt = $pdo->prepare("SELECT * FROM сreatives WHERE task_id = ? AND creative_status = 'На доработке'");
			break;
		case "Покупка":
			$stmt = $pdo->prepare("SELECT * FROM сreatives WHERE task_id = ? AND creative_status = 'Покупка'");
			break;
	}
	$stmt->execute(array($task_id));
	return $stmt->rowCount();
}


if(count($tasks)==0){
	echo "<div class='alert alert-warning' role='alert'>Список задач пуст!</div>";
}else{
	echo "<table class='table table-sm table-light-header' id='DT_TaskList'>";

	echo "<thead><tr><th>#</th><th>#</th><th>Номер</th><th>Заказчик</th><th>Тип</th><th>Название задачи</th><th>Крайний срок</th><th>Креативов всего</th><th>Принято</th><th>Покупка</th><th>В работе</th><th>На комиссии</th><th>На рассмотрении</th><th>На доработке</th></tr></thead>";

	echo "<tbody>";
	forEach($tasks as $task){
		echo "<tr>";
		echo "<td>".$task['task_update']."</td>";
		echo "<td>".$task['task_id']."</td>";
		echo "<td>".$task['task_number']."</td>";
		echo "<td>".$task['customer_name']."</td>";
		echo "<td>".$task['customer_type']."</td>";
		echo "<td><a href = '/index.php?module=TaskEdit&task_id=".$task['task_id']."'><i class='fas fa-edit'></i>".$task['task_name']."</a></td>";
		echo "<td>".mysql_to_date($task['task_deadline'])."</td>";
		echo "<td>".GetCreativeNumbers($pdo, $task['task_id'], "All")."</td>";
		echo "<td>".GetCreativeNumbers($pdo, $task['task_id'], "Принят")."</td>";
		echo "<td>".GetCreativeNumbers($pdo, $task['task_id'], "Покупка")."</td>";
		echo "<td>".GetCreativeNumbers($pdo, $task['task_id'], "В работе")."</td>";
		echo "<td>".GetCreativeNumbers($pdo, $task['task_id'], "На утверждении")."</td>";
			if(GetCreativeNumbers($pdo, $task['task_id'], "На рассмотрении") > 0) {
				$attentionA = 'class="MyAtt"';
			}
		echo "<td {$attentionA}><span>".GetCreativeNumbers($pdo, $task['task_id'], "На рассмотрении")."</span></td>";
			if(GetCreativeNumbers($pdo, $task['task_id'], "На доработке") > 0) {
				$attentionB = 'class="MyAtt"';
			}
		echo "<td {$attentionB}><span>".GetCreativeNumbers($pdo, $task['task_id'], "На доработке")."</span></td>";

		// echo "<td>".$task['task_status']."</td>";
		// echo "<td><a href = '/index.php?module=TaskEdit&task_id=".$task['task_id']."' class='btn btn-outline-primary btn-sm' type='button'><i class='fas fa-edit'></i></a></td>";
		echo "</tr>";
	}
	echo "</tbody>";
	echo "</table>";
}
?>

	<div class="row">
		<div class="col" style="text-align: center;">
			<button class="btn btn-outline-success" type="button" data-toggle="modal" data-target="#AddTask">Добавить задачу</button>
		</div>
	</div>	
</div>

<!-- Модальное окно Добавления задачи -->
<div class="modal fade modal" id="AddTask" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Добавление задачи</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
				<form>
						<input type="hidden" id="add_task" value = "add_task">
						<input type="hidden" id="user_id" value = "<?=$user_id?>">
						<div class="row mb-2">
							<div class="col">
								<select class="custom-select" id="customer_id" name="customer_id" required>
									<?php
										// Получаем список существующих заказчиков
										$stmt = $pdo->prepare("SELECT * FROM `customers` WHERE 1");
										$stmt->execute();
										$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
										foreach($customers as $customer){
											echo("<option value='{$customer['customer_id']}'>{$customer['customer_name']}</option>");
										}
									?>
								</select>
								<small id="emailHelp" class="form-text text-muted">Выберете заказчика из списка или <strong><a href ='/index.php?module=CustomerList' tatget='_self'>добавьте нового</a></strong></small>
							</div>	
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<input type="text" class="form-control my-1 mr-sm-2" id="task_number" placeholder="Номер задачи" name="task_number">
									<small id="emailHelp" class="form-text text-muted">Введите номер задачи <span class = 'text-danger' data-toggle="tooltip" data-placement="bottom" title="Заполнять обязательно!">[обязательное поле]</span></small>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col">
								<div class="form-group">
									<input type="text" class="form-control my-1 mr-sm-2" id="task_name" placeholder="Название задачи" name="task_name">
									<small id="emailHelp" class="form-text text-muted">Введите название задачи <span class = 'text-danger' data-toggle="tooltip" data-placement="bottom" title="Заполнять обязательно!">[обязательное поле]</span></small>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col xs-6">
								<div class="form-group">
									<input data-date-format="dd.mm.yyyy" type="text" class="datepicker form-control" id="datepicker_start" name="datepicker_start">
									<small id="emailHelp" class="form-text text-muted">Дата постановки задачи</small>
								</div>
							</div>
							<div class="col xs-6">
								<div class="form-group">
									<input data-date-format="dd.mm.yyyy" type="text" class="datepicker form-control" id="datepicker_end" name="datepicker_end">
									<small id="emailHelp" class="form-text text-muted">Крайний срок исполнения</small>
								</div>
							</div>
						</div>

						<!-- <div class="row">
							<div class="col">
								<div class="form-group">
									<input class = "form-control" type="number" min="1" max="50" value = "1" id="creativeCount">
									<small id="emailHelp" class="form-text text-muted">Количество креативов в задаче <span class = 'text-danger' data-toggle="tooltip" data-placement="bottom" title="Не меньше 1 креатива!">[обязательное поле]</span></small>
								</div>
							</div>
						</div> -->

						<label for="task_description">Описание задачи</label>
						<textarea class="form-control mb-2" id="task_description" name="task_description" rows="3"></textarea>
						<small id="task_description_help" class="form-text text-muted">Оставьте краткое описание задачи</small>

						<div class="mt-3" style="text-align: center">
							<button type="reset" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
							<button type="submit" class="btn btn-danger" data-dismiss="modal" id="SaveTask">Сохранить</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
