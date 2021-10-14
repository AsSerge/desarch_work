<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fas fa-drafting-compass" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Список дизайнеров</h6>
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


// Креативы 
// Получаем список креативов
$stmt = $pdo->prepare("SELECT * FROM сreatives WHERE 1");
$stmt->execute();
$creatives = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Функция формирования блока хэшей
function GetHashList($hash_string){
	if($hash_string != ""){
		$hash_arry = explode("|", $hash_string);
		$hash_html = "<div class = 'TagsList'>";
		foreach($hash_arry as $hash){
			$hash_html .= "<span class = 'OneTag'>".$hash."</span>";
		}
		$hash_html .= "</div>";
	}
	return $hash_html;
}

// Дизайны
// Получаем список загруженных дизайнов
$stmt = $pdo->prepare("SELECT * FROM designes WHERE 1");
$stmt->execute();
$designes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
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
	background-color: rgb(33, 201, 201);
	cursor: pointer;
}
.sub_menu{
	float: right;
	cursor: pointer;
}
</style>



<div class="my-3 p-3 bg-white rounded box-shadow">

	<div class="row dash_board">

		<div class="col-md-4 mb-3">
			<div class="dash_item rounded box-shadow">
				<div class="dash_item__head">
				<i class="fas fa-user-graduate"></i> Дизайнеры	
				</div>
				<div class="dash_item__body">
				<table class="table table-sm">
				<!-- <tr><th>ID</th><th>Дизайнер</th><th>Принято</th><th>В работе</th><th>На доработке</th><th>На рассмотрении</th><th>На утверждении</th><th>Всего</th><th>Задачи</th></tr> -->

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


		<div class="col-md-4 mb-3">
			<div class="dash_item rounded box-shadow">
				<div class="dash_item__head">
				<span><i class="fas fa-list-ul"></i> Задачи</span><span style='float: right'></span>
				</div>
				<div class="dash_item__body">
				<table class="table table-sm">
				<?php
					foreach($tasks as $tsk){
						echo "<tr>";
						echo "<td><a href = '/index.php?module=TaskEdit&task_id={$tsk['task_id']}'>".$tsk['task_name']."</a></td>";
						echo "<td class = 'MyTd' data-toggle='tooltip' data-placement='right' title='Всего креативов'>".CheckTaskCreatives($pdo, $tsk['task_id'])."</td>";
						echo "</tr>";
					}
					?>
					</table>
				</div>
			</div>
		</div>

		<div class="col-md-4 mb-3">
			<div class="dash_item rounded box-shadow">
				<div class="dash_item__head">
				<span id="CreativeFilter"><i class="fas fa-swatchbook"></i> Креативы</span><span id = "all_hash" class='sub_menu'><i class="fas fa-list" data-toggle="tooltip" data-placement="right" title='Очистить фильтр'></i></span>	
				</div>
				<div class="dash_item__body">
					<div id="CreativesList"></div>
				</div>
			</div>
		</div>

		<div class="col-md-4 mb-3">
			<div class="dash_item rounded box-shadow">
				<div class="dash_item__head">
				<i class="fas fa-drafting-compass"></i></i> Дизайны
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



	</div>

</div>