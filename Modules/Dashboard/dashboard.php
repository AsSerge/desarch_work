<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fas fa-drafting-compass" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Dashboard</h6>
				<small><?php echo $user_name." " .$user_surname. " [".$user_role_description."]";?></small>
			</div>
</div>
<?php
// Дизайнеры
// Получаем список всех дизайнеров
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_role = 'dgr'");
$stmt->execute();
$designers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Получаем общее принятых количество креативов
function CheckDesignerCreatives($pdo, $user_id, $creative_status){
	if($creative_status != ""){
		$stmt = $pdo->prepare("SELECT * FROM сreatives WHERE user_id = ? AND creative_status = ?");
		$stmt->execute(array($user_id, $creative_status));
	}else{
		$stmt = $pdo->prepare("SELECT * FROM сreatives WHERE user_id = ?");
		$stmt->execute(array($user_id));
	}	
	return $stmt->rowCount();
}
// Получаем общее число задач по дизайнеру
function CheckDesignerTasks($pdo, $user_id){
	// $stmt = $pdo->prepare("SELECT * FROM сreatives as C LEFT JOIN users AS U ON (C.user_id = U.user_id) WHERE C.creative_status = :creative_status AND U.user_superior = :user_superior");
	$stmt = $pdo->prepare("SELECT DISTINCT task_id FROM сreatives WHERE user_id = ?"); // Уникальные значения
	$stmt->execute(array($user_id));
	return $stmt->rowCount();
}

// Задачи
// Получаем список всех задач
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE 1");
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Функция получения количества креативов в задаче
function CheckTaskCreatives($pdo, $task_id){
	$stmt = $pdo->prepare("SELECT task_id FROM сreatives WHERE task_id = ?"); // Уникальные значения
	$stmt->execute(array($task_id));
	return $stmt->rowCount();
}


// Дизайны
// Получаем список загруженных дизайнов
$stmt = $pdo->prepare("SELECT * FROM designes WHERE 1");
$stmt->execute();
$designes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Заказчики
// Получаем список заказчиков с количеством задач
$stmt = $pdo->prepare("SELECT DISTINCT customer_id FROM tasks WHERE 1");
$stmt->execute();
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Функция получение информации о заказчике
function GetCustomerInfo($pdo, $customer_id){
	$stmt = $pdo->prepare("SELECT customer_name, customer_type FROM customers WHERE customer_id = ?");
	$stmt->execute(array($customer_id));
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Функция получение количества задач, разрабатываемых для Каждого заказчика
function GetTasksCount($pdo, $customer_id){
	$stmt = $pdo->prepare("SELECT customer_id FROM tasks WHERE customer_id = ?");
	$stmt->execute(array($customer_id));	
	return $stmt->rowCount();
}

// Комиссия
// Получаем список членов комиссии
$stmt = $pdo->prepare("SELECT user_login, user_name,user_surname FROM users WHERE user_role = 'ctr'");
$stmt->execute();
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<style>

/* Настройка полосы прокрутки для блоков */
.dash_item__body::-webkit-scrollbar-button {
background-image:url('');
background-repeat:no-repeat;
width:5px;
height:0px
}

.dash_item__body::-webkit-scrollbar-track {
background-color:#ecedee
}

.dash_item__body::-webkit-scrollbar-thumb {
-webkit-border-radius: 0px;
border-radius: 0px;
background-color:#6dc0c8;
}

.dash_item__body::-webkit-scrollbar-thumb:hover{
background-color:#56999f;
}

.dash_item__body::-webkit-resizer{
background-image:url('');
background-repeat:no-repeat;
width:4px;
height:0px
}

.dash_item__body::-webkit-scrollbar{
width: 4px;
}


.dash_item{
	border: 1px solid var(--info);
	font-size: 0.8rem;
}
.dash_item__head{
	background-color: var(--info);
	color: white;
	padding: 0.5rem 0.8rem;
}
.dash_item__body{
	padding:0.5rem;
	height: 200px;
	overflow: auto;
}
.MyTd{
	cursor: pointer;
	text-align: center;
	box-sizing: padding-box;
	}
.MyTd:HOVER{	
	color: white;
	background-color: var(--info);
}

/* Блок с HASH-тегами */
.TagsList { 
	display: flex;
	flex-direction: row;
	flex-wrap: wrap;
	/* padding: 8px; */
	text-decoration: none;
	color: white;
}
.OneTag {
	font-size: 0.7rem;
	margin-right: 2px;
	padding: 0 10px;
	height: 1.2rem;
	border-radius: 0.6rem;
	color: white;
	background-color: var(--info);
	cursor: pointer;
}
.sub_menu{
	float: right;
	cursor: pointer;
}

.console__body{
	background-color: var(--dark);	
}
.console__body td{
	font-size: 0.6rem;
	color: var(--light);
}
</style>

<div class="my-3 p-3 bg-white rounded box-shadow">

	<div class="row dash_board">

		<div class="col-md-12 col-lg-6 mb-3">
			<div class="dash_item rounded box-shadow">
				<div class="dash_item__head">
				<i class="fas fa-user-graduate"></i> Дизайнеры	
				</div>
				<div class="dash_item__body">
				<table class="table table-sm">
				<tr><th>ID</th><th>Дизайнер</th><th>Принято</th><th>В работе</th><th>На доработке</th><th>На рассмотрении</th><th>На утверждении</th><th>Всего</th><th>Задачи</th></tr>
				<?php
					foreach($designers as $dgr){
						
						$taskCount = CheckDesignerTasks($pdo, $dgr['user_id']); // Число креативов
						echo "<tr>";
						echo "<td>{$dgr['user_id']}</td>";
						echo "<td>{$dgr['user_name']} {$dgr['user_surname']}</td>";
						echo "<td class = 'MyTd' data-toggle='tooltip' data-placement='right' title='Принято креативов'>".CheckDesignerCreatives($pdo, $dgr['user_id'], 'Принят')."</td>";
						echo "<td class = 'MyTd' data-toggle='tooltip' data-placement='right' title='В работе'>".CheckDesignerCreatives($pdo, $dgr['user_id'], 'В работе')."</td>";
						echo "<td class = 'MyTd' data-toggle='tooltip' data-placement='right' title='На доработке'>".CheckDesignerCreatives($pdo, $dgr['user_id'], 'На доработке')."</td>";
						echo "<td class = 'MyTd' data-toggle='tooltip' data-placement='right' title='На рассмотрении'>".CheckDesignerCreatives($pdo, $dgr['user_id'], 'На рассмотрении')."</td>";
						echo "<td class = 'MyTd' data-toggle='tooltip' data-placement='right' title='На утверждении'>".CheckDesignerCreatives($pdo, $dgr['user_id'], 'На утверждении')."</td>";
						echo "<td class = 'MyTd' data-toggle='tooltip' data-placement='right' title='Всего креативов'>".CheckDesignerCreatives($pdo, $dgr['user_id'], '')."</td>";
						echo "<td class = 'MyTd' data-toggle='tooltip' data-placement='right' title='Всего задач'>{$taskCount}</td>";
						echo "</tr>";
					}
					?>
					</table>
				</div>	
			</div>
		</div>

		<div class="col-md-12 col-lg-6 mb-3">
			<div class="dash_item rounded box-shadow">
				<div class="dash_item__head">
				<span id="CreativeFilter"><i class="fas fa-swatchbook"></i> Принятые креативы</span><span id = "all_hash" class='sub_menu'><i class="fas fa-list" data-toggle="tooltip" data-placement="right" title='Очистить фильтр'></i></span>	
				</div>
				<div class="dash_item__body">
					<div id="CreativesList"></div>
				</div>
			</div>
		</div>

		<div class="col-md-6 col-lg-3 mb-3">
			<div class="dash_item rounded box-shadow">
				<div class="dash_item__head">
				<span><i class="fas fa-list-ul"></i> Задачи</span><span style='float: right'></span>
				</div>
				<div class="dash_item__body">
				<table class="table table-sm">
				<?php
					foreach($tasks as $tsk){
						echo "<tr>";
						echo "<td>{$tsk['task_number']}</td>";
						echo "<td><a href = '/index.php?module=TaskEdit&task_id={$tsk['task_id']}'>".$tsk['task_name']."</a></td>";
						echo "<td class = 'MyTd' data-toggle='tooltip' data-placement='right' title='Всего креативов'>".CheckTaskCreatives($pdo, $tsk['task_id'])."</td>";
						echo "</tr>";
					}
					?>
					</table>
				</div>
			</div>
		</div>

		<div class="col-md-6 col-lg-3 mb-3">
			<div class="dash_item rounded box-shadow">
				<div class="dash_item__head">
				<i class="fas fa-drafting-compass"></i> Загруженные дизайны
				</div>
				<div class="dash_item__body">
				<table class="table table-sm">
				<?php
					foreach($designes as $des){
						echo "<tr>";
						echo "<td>".$des['design_name']."</td>";
						echo "<td>".$des['design_creative_style']."</td>";
						echo "</tr>";
					}
					?>
					</table>
				</div>
			</div>
		</div>

		<div class="col-md-6 col-lg-3 mb-3">
			<div class="dash_item rounded box-shadow">
				<div class="dash_item__head">
				<i class="fas fa-users-cog"></i> Заказчики
				</div>
				<div class="dash_item__body">
				<table class="table table-sm">
				<?php
					foreach($customers as $cst){
						echo "<tr>";
						echo "<td>".GetCustomerInfo($pdo, $cst['customer_id'])['customer_name']."</td>";
						echo "<td>".GetCustomerInfo($pdo, $cst['customer_id'])['customer_type']."</td>";
						echo "<td class = 'MyTd' data-toggle='tooltip' data-placement='right' title='Всего задач'>".GetTasksCount($pdo, $cst['customer_id'])."</td>";
						echo "</tr>";
					}
					?>
					</table>
				</div>
			</div>
		</div>

		<div class="col-md-6 col-lg-3 mb-3">
			<div class="dash_item rounded box-shadow">
				<div class="dash_item__head">
				<i class="fas fa-balance-scale-right"></i> Комиссия
				</div>
				<div class="dash_item__body">
				<table class="table table-sm">
				<?php
					foreach($members as $mbr){
						echo "<tr>";
						echo "<td>".$mbr['user_name']."&nbsp;".$mbr['user_surname']."</td>";
						echo "<td><a href = 'mailto:".$mbr['user_login']."'>".$mbr['user_login']."</a></td>";
						echo "</tr>";
					}
					?>
					</table>
				</div>
			</div>
		</div>

		<div class="col-md-12 col-lg-6 mb-3">
			<div class="dash_item rounded box-shadow">
				<div class="dash_item__head">
				<i class="fas fa-wave-square"></i> Пульс отдела
				</div>
				<div class="dash_item__body console__body">
					<div id="Pulse"></div>
				</div>
			</div>
		</div>




	</div>

</div>