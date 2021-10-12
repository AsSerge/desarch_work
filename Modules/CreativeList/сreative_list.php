
<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fas fa-list-ul" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Список креативов в работе</h6>
				<small><?php echo $user_name." " .$user_surname. " [".$user_role_description."]";?></small>
			</div>
</div>
<div class="my-3 p-3 bg-white rounded box-shadow">
<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта
// Получаем список заданий на разработку (Креативов) для данного дизайнера
	$stmt = $pdo->prepare("SELECT * FROM сreatives as C LEFT JOIN tasks AS T ON (C.task_id = T.task_id) WHERE C.user_id = ?");
	$stmt->execute(array($user_id));
	$creatives = $stmt->fetchAll(PDO::FETCH_ASSOC);

	// echo "<pre>";
	// print_r($creatives);
	// echo "</pre>";

	// Функция определения параметров заказчика
	function Customer($pdo, $customer_id){
		$stmt = $pdo->prepare("SELECT customer_name, customer_type FROM customers WHERE customer_id = ?");
		$stmt->execute(array($customer_id));
		$customer = $stmt->fetch(PDO::FETCH_ASSOC);
		return $customer;
	}

	// Функция определения количества дизайнов в креативе
	function GetDisignesCount($pdo, $creative_id){
		$stmt = $pdo->prepare("SELECT COUNT(*) FROM designes WHERE creative_id = ?");
		$stmt->execute(array($creative_id));
		$count = $stmt->fetchColumn();
		return $count; 
	}
?>	



<table class='table table-sm table-light-header' id='CR_CreativeList'>
<thead><tr><th>Задача</th><th>Заказчик</th><th>Крайний срок</th><th>Креатив</th><th>Статус</th><th>Действие</th></tr></thead>
<tbody>

<?php
foreach($creatives as $crt){
	echo "<tr>";
	echo "<td>".$crt['task_name']." [".$crt['task_number']."]</td>";
	echo "<td>".Customer($pdo, $crt['customer_id'])['customer_name']." [".Customer($pdo, $crt['customer_id'])['customer_type']."]</td>";
	echo "<td>".mysql_to_date($crt['task_deadline'])."</td>";
	echo "<td>".$crt['creative_name']."</td>";
	// echo "<td><a href = '/index.php?module=CreativeEdit&creative_id=".$crt['creative_id']."'>".$crt['creative_name']."</a></td>";
	echo "<td>".$crt['creative_status']."</td>";
	echo "<td>";
	
	$lable_set = ($crt['creative_status'] == 'В задаче') ? '': 'disabled';

	switch($crt['creative_status']){
		case 'В работе':
				$button_color = 'info';
				$lable_work = '';
				$lable_title = $crt['creative_status'];
				$lable_work_library = 'disabled';
			break;
		case 'В задаче':
				$button_color = 'danger';
				$lable_work = 'disabled';
				$lable_title = $crt['creative_status'];
				$lable_work_library = 'disabled';
			break;
		case 'На доработке':
				$button_color = 'warning';
				$lable_work = '';
				$lable_title = $crt['creative_status'];
				$lable_work_library = 'disabled';
			break;
		case 'На утверждении':
				$button_color = 'primary';
				$lable_work = 'disabled';
				$lable_title = $crt['creative_status'];
				$lable_work_library = 'disabled';
			break;
			case 'На рассмотрении':
				$button_color = 'primary';
				$lable_work = 'disabled';
				$lable_title = $crt['creative_status'];
				$lable_work_library = 'disabled';
			break;	
		case 'Принят':
				$button_color = 'success';
				$lable_work = '';
				$lable_title = $crt['creative_status'];
				$lable_work_library = '';
			break;
		default:
				$button_color = 'info';
				$lable_work = '';
				$lable_title = $crt['creative_status'];
				$lable_work_library = 'disabled';
	}
		
	echo "<button type='button' class='btn btn-warning btn-sm TakeToWork' data-creative = '".$crt['creative_id']."' {$lable_set}><i class='far fa-flag'></i></button>&nbsp;";
	echo "<button type='button' class='btn btn-{$button_color} btn-sm' {$lable_work} data-toggle='tooltip' data-placement='bottom' title='{$lable_title}' onclick='document.location=`/index.php?module=CreativeEdit&creative_id=".$crt['creative_id']."`'><i class='fas fa-tools'></i></button>&nbsp;";


	echo "<button type='button' class='btn btn-{$button_color} btn-sm' {$lable_work_library} data-toggle='tooltip' data-placement='bottom' title='Добавить дизайн в библиотеку' onclick='document.location=`/index.php?module=LibraryEdit&creative_id=".$crt['creative_id']."`'><i class='fas fa-photo-video'></i> ".GetDisignesCount($pdo, $crt['creative_id'])."</button>&nbsp;";

	

	echo "</td>";
	echo "</tr>";
}
?>
</tbody>
</table>


<!-- Системные сообщения (Сохранение изменений)  -->
<div class="position-fixed bottom-0 right-0 p-3" style="z-index: 5; left: 0; bottom: 0;">
	<div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000" style="background-color: #ffc107">
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


</div>