<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fas fa-users-cog" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Список заказчиков</h6>
				<small><?php echo $user_name." " .$user_surname. " [".$user_role_description."]";?></small>
			</div>
</div>

<div class="my-3 p-3 bg-white rounded box-shadow">

	<?php
	// Выбираем все записи
	$stmt = $pdo->prepare("SELECT * FROM `customers` WHERE 1");
	$stmt->execute();
	$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

	function CheckCustomer($pdo, $customer_id){
		$stmt = $pdo->prepare("SELECT * FROM tasks WHERE customer_id = ?");
		$stmt->execute(array($customer_id));
		return $stmt->rowCount();
	}

	// Индивидуальные настроки по типам ролей
	if(count($customers)==0){
		echo "<div class='alert alert-warning' role='alert'>Список заказчиков пуст! Пожалуйста добавьте заказчика!</div>";
	}else{
		echo "<table class='table'>";
		if($user_role == "adm"){
			echo "<thead><tr><th>ID</th><th>Изменен</th><th>Название</th><th>Тип</th><th>Описание</th><th>Действие</th></tr></thead>";
		}else{
			echo "<thead><tr><th>ID</th><th>Изменен</th><th>Название</th><th>Тип</th><th>Описание</th></tr></thead>";
		}
		echo "<tbody>";
		forEach($customers as $cust){	
			// Разрешено удалять ТОЛЬКО пустых заказчиков
			$check_customer = (CheckCustomer($pdo, $cust['customer_id']) > 0) ? 'disabled' : '';
			echo "<tr>";
			echo "<td>".$cust['customer_id']."</td>";
			echo "<td>".$cust['customer_update']."</td>";
			echo "<td>".$cust['customer_name']."</td>";
			echo "<td>".$cust['customer_type']."</td>";
			echo "<td>".$cust['customer_description']."</td>";
			if ($user_role == "adm"){
				echo "<td>";
				echo "<div class='btn-group' role='group' aria-label='Basic example'>";
					echo"<button type='button' class='btn btn-info btn-sm EditCustomerBtn' data-toggle='modal' data-target='#AddCustomer' data-whatever = 'edit-customer' data-customer-id = ".$cust['customer_id']."><i class='far fa-edit'></i> Редактировать</button>";
					echo"<button type='button' class='btn btn-danger btn-sm RemoveCustomerBtn' data-customer-id = ".$cust['customer_id']." {$check_customer}><i class='far fa-trash-alt'></i> Удалить</button>";
				echo "</div>";
				echo"</td>";
			}
			echo "</tr>";
		}
		echo "</tbody>";
		echo "</table>";
	}
	?>
	
	<div class="row">
		<div class="col" style="text-align: center;">
			<button class="btn btn-outline-success" type="button" data-toggle="modal" data-target="#AddCustomer" data-whatever="new-customer">Добавить заказчика</button>
		</div>
	</div>

	<div class="get_info">

	</div>


<!-- Модальное окно Добавление заказчика -->
<div class="modal fade modal" id="AddCustomer" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Добавление заказчика</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
				<form>
						<input type="hidden" id="add_customer" value = "add_customer">
						<input type="hidden" id="edit_customer" value = "">
						<label for="ustomer_name">Название заказчика</label>
						<input type="text" class="form-control mb-2" id="customer_name" required>

						<label for="customer_type">Тип заказчика</label>
						<select class="custom-select" id="customer_type" name="customer_type" required>
							<?php
							include($_SERVER['DOCUMENT_ROOT'].'/Layout/settings.php'); // Подключаем настроечный файл
							$CustomerTypes=json_decode($customer_types); // Получаем массив типов заказчиков
							foreach($CustomerTypes as $ct){
								echo("<option value='{$ct}'>{$ct}</option>");
							}
							?>
						</select>

						<label for="customer_description">Описание заказчика</label>
						<textarea class="form-control mb-2" id="customer_description" name="customer_description" rows="3"></textarea>
						<small id="task_description_help" class="form-text text-muted">Оставьте краткое описание заказчика</small>

						<div class="mt-3" style="text-align: center">
							<button type="reset" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
							<button type="submit" class="btn btn-danger" data-dismiss="modal" id="SaveCustomer">Сохранить</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>



