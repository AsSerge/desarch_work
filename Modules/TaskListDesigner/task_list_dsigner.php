<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fas fa-tasks" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Список задач в работе</h6>
				<small><?php echo $user_name." " .$user_surname. " [".$user_role_description."]";?></small>
			</div>
</div>
<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта
// Для администратора выводим все задачи. ДЛя лизайнера выводим ТОЛЬКО задачи, поставленные его руководиетелем (user_superior)
if($user_role == 'adm'){
	$stmt = $pdo->prepare("SELECT * FROM tasks as T LEFT JOIN customers AS C ON (T.customer_id = C.customer_id) WHERE 1");
	$stmt->execute();
}else{
	$stmt = $pdo->prepare("SELECT * FROM tasks as T LEFT JOIN customers AS C ON (T.customer_id = C.customer_id) WHERE T.user_id = ?");
	$stmt->execute(array($user_superior)); // Задачи от руководителя
}
// Выбираем все задачи для дизайнера
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Функция определения общего количества креативов для задачи и для конкретного дизайнера
function GetCreativesCount($pdo, $task_id, $user_id){
	$stmt_all = $pdo->prepare("SELECT COUNT(*) FROM сreatives WHERE task_id = ?");
	$stmt_all->execute(array($task_id));
	$count['all'] = $stmt_all->fetchColumn();

	$stmt_user = $pdo->prepare("SELECT COUNT(*) FROM сreatives WHERE task_id = ? AND user_id = ?");
	$stmt_user->execute(array($task_id, $user_id));
	$count['user'] = $stmt_user->fetchColumn();

	return $count; 
}
?>

<div class="my-3 p-3 bg-white rounded box-shadow">
<table class="table table-sm table-light-header">
	<thead><tr><th>#</th><th>Дата</th><th>Заказчик/канал</th><th>Задача</th><th>Креативов</th><th>Действие</th></tr></thead>
	<tbody>
		<?php
		foreach($tasks as $task){
			echo "<tr>";
			echo "<td>{$task['task_id']}</td>";
			echo "<td>".mysql_to_date($task['task_setdatetime'])."</td>";
			echo "<td>{$task['customer_name']} [{$task['customer_type']}]</td>";
			echo "<td>{$task['task_name']}</td>";
			echo "<td><span id='MyCountSrc{$task['task_id']}'><a href = '/index.php?module=CreativeList'>Мои креативы: ".GetCreativesCount($pdo, $task['task_id'], $user_id)['user']."</a> / Всего в задаче: ".GetCreativesCount($pdo, $task['task_id'], $user_id)['all']."</span></td>";
			echo "<td>";
			echo "<a href = '/Modules/CreativeEdit/сreative_edit.php' data-toggle='modal' data-target='#AddCreative' data-task='{$task['task_id']}' class = 'AddNewCreative'><i class='far fa-plus-square'></i> Добавить креатив</a>";
			echo "</td>";
			echo "</tr>";
		}
		?>
	</tbody>
</table>

</div>

<!-- Модальное окно Добавления задачи -->
<div class="modal fade modal" id="AddCreative" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Добавление креатива в задачу</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="AddCreativeFrm">
					<input type="hidden" name="user_id" id="user_id" value="<?=$user_id?>">
					<div class="row mb-2">
						<div class="col">
							<div class="form-group">
								<input type="text" class="form-control my-1 mr-sm-2" id="creative_name" placeholder="Название креатива" name="task_number">
								<small id="emailHelp" class="form-text text-muted">Название нового креатива <span class = 'text-danger' data-toggle="tooltip" data-placement="right" title="По умолчанию: Новый креатив">[обязательное поле]</span></small>
							</div>
						</div>
					</div>
					<div class="mt-3" style="text-align: center">
						<button type="reset" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
						<button type="submit" class="btn btn-danger" data-dismiss="modal" id="AddCreativeBtn">Добавить</button>
					</div>
				</form>
			</div>
		</div>
	</div>	
</div>

<!-- Системные сообщения (Сохранение изменений)  -->
<div class="position-fixed bottom-0 right-0 p-3" style="z-index: 5; left: 0; bottom: 0;">
	<div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="1000" style="background-color: #ffc107">
		<div class="toast-header">
			<strong class="mr-auto">Системное сообщение</strong>
			<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="toast-body">
			<p><i class="far fa-save"></i> Креатив добавлен в систему!</p>
		</div>
	</div>
</div>


