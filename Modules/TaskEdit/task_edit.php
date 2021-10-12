<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fas fa-edit" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Редактор задачи</h6>
				<small><?php echo $user_name." " .$user_surname. " [".$user_role_description."]";?></small>
			</div>
</div>

<?php
	include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта
	// Получаем ID задачи для окна редактирования 
	$task_id = $_GET['task_id'];
	echo "<script>var c_Id = {$task_id};</script>";

	// Выбираем задачу по ID	
	$stmt = $pdo->prepare("SELECT * FROM tasks as T LEFT JOIN customers AS C ON (T.customer_id = C.customer_id) WHERE T.task_id = :task_id");
	$stmt->execute(array('task_id' => $task_id));
	$task = $stmt->fetch(PDO::FETCH_ASSOC);

	// Разбираем креативы в составе задачи
	$stmtcr = $pdo->prepare("SELECT * FROM сreatives WHERE task_id = ?");
	$stmtcr->execute(array($task_id ));	
	$creatives = $stmtcr->fetchAll(PDO::FETCH_ASSOC);

	// echo "<pre>";
	// print_r($creatives);
	// echo "</pre>";
	
	// Функция получение информации о дизайнере креатива
	function GetDesignerInfo($pdo, $user_id){
		$stmtdr = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
		$stmtdr->execute(array($user_id));	
		$designer = $stmtdr->fetch(PDO::FETCH_ASSOC);
		return $designer;
	}
?>

<div class="my-3 p-3 bg-white rounded box-shadow">

			<!-- <h6 class="border-bottom border-gray pb-2 mb-0">Редактор задачи</h6> -->

			<div class="row">
				<div class="col">
					<h6 class="border-bottom border-gray pb-3 mb-2"><i class="fas fa-clipboard-list"></i> Параметры задачи [<span id="c_Id"></span> JS]</h6>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-2">
					<div class="form-group">
						<label for="task_nubmer">Номер задачи</label>
						<input type="text" class="form-control my-1 mr-sm-2" id="task_nubmer" name="task_number" value="<?=htmlspecialchars($task['task_number'])?>" disabled>
					</div>
				</div>
				<div class="col-lg-2">
					<div class="form-group">
						<label for="task_customer">Заказчик</label>
						<input type="text" class="form-control my-1 mr-sm-2" id="task_customer" name="task_customer" value="<?=htmlspecialchars($task['customer_name'])?>" disabled>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="form-group">
						<label for="task_name">Название задачи</label>
						<input type="text" class="form-control my-1 mr-sm-2" id="task_name" name="task_name" value="<?=htmlspecialchars($task['task_name'])?>" disabled>
					</div>
				</div>

				<div class="col-lg-2">
					<div class="form-group">
						<label for="task_name">Постановка задачи</label>
						<input type="text" class="form-control my-1 mr-sm-2" id="task_setdatetime" name="task_setdatetime" value="<?=mysql_to_date($task['task_setdatetime'])?>" disabled>
					</div>
				</div>
				<div class="col-lg-2">
						<label for="task_name">Крайний срок</label>
						<input type="text" class="form-control my-1 mr-sm-2" id="task_deadline" name="task_deadline" value="<?=mysql_to_date($task['task_deadline'])?>" disabled>
				</div>
			</div>

			<hr>

			<div class="row">
				<div class="col-lg-4">
					<h6 class="border-bottom border-gray pb-3 mb-2"><i class="far fa-file-alt"></i> Краткое описание задачи</h6>
					<div class="form-group">
						<!-- <label for="exampleFormControlTextarea1">Краткое описание</label> -->
						<textarea class="form-control mb-2" id="task_description" name="task_description" rows="3"><?=htmlspecialchars($task['task_description'])?></textarea>
						<div style = "text-align: center">
							<button type="button" class="btn btn-warning" id="EditTaskDescription"><i class="far fa-save"></i> Сохранить описание</button>
						</div>
					</div>
					<div class="form-group">
						<h6 class="border-bottom border-gray pb-3 mb-2"><i class="far fa-images"></i> Базовые изображения</h6>
						<div class="alert alert-warning" role="alert" id = "BaseImagesNoN">
							Базовые изображения отсутствуют
						</div>
						<div id="thumb-img-set">
						</div>
						<div class="col" style="text-align: center;">
							<form id="js-form" method="POST" action = "../Assets/LoadImages.php" enctype="multipart/form-data">
								<input id="js-file" type="file" name="file[]" multiple style='display: none;'>
							</form>
							<button type="button" class="btn btn-primary" id="FilesDN"><i class="fas fa-file-upload"></i> Загрузить файлы</button>
							<div id="result"></div>
						</div>
					</div>

				</div>

				<!-- Модальное окно Просмотр и удаление изображений -->

				<div class="modal fade" id="EditDesign" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Заголовок модального окна</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
							</div>
						<div class="modal-footer" style="margin:auto">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
							<!-- <button type="button" class="btn btn-primary" data-dismiss="modal" id="DownloadImage"><i class="fas fa-download"></i> Скачать</button> -->

							<a href="" type="button" class="btn btn-primary" data-dismiss="modal" id="DownloadImage"><i class="fas fa-download" download></i> Скачать</a>


							<button type="button" class="btn btn-danger" data-dismiss="modal" id="ClearImage"><i class="far fa-trash-alt"></i> Удалить</button>
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
							<p><i class="far fa-save"></i> Информация обновлена!</p>
						</div>
					</div>
				</div>

				<div class="col-lg-8">
					<h6 class="border-bottom border-gray pb-3 mb-2"><i class="fas fa-list"></i> Список креативов</h6>
					<?php
					if(count($creatives) <= 0){
						echo"<div class='alert alert-warning' role='alert'>Креативы в задаче отсутствуют!</div>";
					}else{
						echo "<table class='table table-borderless table-striped table-setdisigner-task table-light-header'>";
							echo "<thead><tr><th scope='col'>#</th><th scope='col'>Название креатива</th><th scope='col'>Дизайнер</th><th scope='col'>Статус</th><th scope='col'>Действие</th></tr></thead>";
							echo "<tbody>";
							foreach($creatives as $crt){

								$des_label = ($crt['creative_status'] != "В задаче") ? 'disabled' : ''; // Определяем статус креатива
								echo "<tr>";
									echo "<td><img src='/Creatives/{$crt['creative_id']}/preview.jpg' class='rounded-circle' width='30px' height='30px'></td>";
									echo "<td>".$crt['creative_name']."</td>";
									echo "<td>".GetDesignerInfo($pdo, $crt['user_id'])['user_name']."&nbsp;".GetDesignerInfo($pdo, $crt['user_id'])['user_surname']."</td>";
									echo "<td>".$crt['creative_status']."</td>";
									echo "<td>";
										echo "<button type='button' class='btn btn-outline-danger btn-sm DelOneCreative' data-creative = {$crt['creative_id']} {$des_label}><i class='fas fa-window-close'></i></button>";
									echo "</td>";
								echo "</tr>";
							}
							echo "</tbody>";	
						echo "</table>";	
					}
					?>
					<div class="row">
						<div class="col" style="text-align: center;">
							<div class="btn-group" role="group" aria-label="Basic example">
								<!-- <button type="button" class="btn btn-secondary" id="AddNewCreative"><i class="far fa-plus-square"></i> Добавить креатив</button> -->
								<button type="button" class="btn btn-primary" onclick="window.location='/index.php?module=TaskList'"><i class="fas fa-undo"></i> В список задач</button>
								<!-- <button type="button" class="btn btn-info" disabled>Завершить задачу</button> -->
							</div>
						</div>
					</div>	
				</div>
			</div>
		</div>


		<div class="modal fade" id="AddCraetiveModal" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Добавление креатива</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form>
							<input type="hidden" id="task_id" value = "<?=$task_id?>">
							<label for="ustomer_name">Название заказчика</label>
							<input type="text" class="form-control mb-2" id="customer_name" required>
						</form>
					</div>
					<div class="modal-footer" style="margin:auto">
						<button type="button" class="btn btn-primary" data-dismiss="modal">Сохранить</button>
						<button type="reset" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
					</div>
				</div>
			</div>
		</div>
