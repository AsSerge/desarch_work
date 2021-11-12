
<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fas fa-list-ul" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Список моих креативов</h6>
				<small><?php echo $user_name." " .$user_surname. " [".$user_role_description."]";?></small>
			</div>
</div>
<div class="my-3 p-3 bg-white rounded box-shadow">
<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта

$task_id = $_GET['task_id'];

// Получаем список заданий на разработку (Креативов) для данного дизайнера

	if($task_id != ""){
		$stmt = $pdo->prepare("SELECT * FROM сreatives as C LEFT JOIN tasks AS T ON (C.task_id = T.task_id) WHERE C.user_id = ? AND T.task_id = ?");
		$stmt->execute(array($user_id, $task_id));
		$creatives = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else{
		$stmt = $pdo->prepare("SELECT * FROM сreatives as C LEFT JOIN tasks AS T ON (C.task_id = T.task_id) WHERE C.user_id = ?");
		$stmt->execute(array($user_id));
		$creatives = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


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

	// Функция определения количества файлов-разработок креатива в папке Creatives_SRC
	function GetFileSourceCount($creative_id){
		$files = scandir(CREATIVE_SOURCE_FOLDER.$creative_id);
		$file_count = 0;
		foreach ($files as $value){
			if($value != "." AND $value != ".."){
				$file_count++;
			}
		}
		return $file_count;
	}
?>

<style>
	.small_image{
		display: inline-block;
		/* border: 1px solid #000; */
		width: 50px;
	}
</style>
<table class='table table-sm table-light-header' id='CR_CreativeList'>
<thead><tr><th>Задача</th><th>Заказчик</th><th>Крайний срок</th><th>Креатив</th><th>Статус</th><th>Действие</th></tr></thead>
<tbody>

<?php
foreach($creatives as $crt){
	echo "<tr>";
	echo "<td>".$crt['task_name']." [".$crt['task_number']."]</td>";
	echo "<td>".Customer($pdo, $crt['customer_id'])['customer_name']." [".Customer($pdo, $crt['customer_id'])['customer_type']."]</td>";
	echo "<td>".mysql_to_date($crt['task_deadline'])."</td>";
	echo "<td>";

	if(file_exists($_SERVER['DOCUMENT_ROOT']."/Creatives/{$crt['creative_id']}/thumb_preview.jpg")){
		echo "<span class='small_image'><img src='/Creatives/{$crt['creative_id']}/thumb_preview.jpg' class='rounded-circle oneimage' width='30px' height='30px' big-image='./Creatives/{$crt['creative_id']}/preview.jpg?ver=".time()."' loading='lazy'></span>";
	}else{
		echo "<span class='small_image'></span>";
	}	
	echo $crt['creative_name'];
	echo "</td>";
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
		case 'Покупка':
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
		
	// echo "<button type='button' class='btn btn-warning btn-sm TakeToWork' data-creative = '".$crt['creative_id']."' {$lable_set}><i class='far fa-flag'></i></button>&nbsp;";
	
	echo "<button type='button' class='btn btn-{$button_color} btn-sm' {$lable_work} data-toggle='tooltip' data-placement='bottom' title='Редактор креатива' onclick='document.location=`/index.php?module=CreativeEdit&creative_id=".$crt['creative_id']."`'><i class='fas fa-tools'></i></button>&nbsp;";

	echo "<button type='button' class='btn btn-{$button_color} btn-sm' {$lable_work_library} data-toggle='tooltip' data-placement='bottom' title='Добавить дизайн в библиотеку' onclick='document.location=`/index.php?module=LibraryEdit&creative_id=".$crt['creative_id']."`'><i class='fas fa-photo-video'></i> ".GetDisignesCount($pdo, $crt['creative_id'])."</button>&nbsp;";

	// echo "<button type='button' class='btn btn-{$button_color} btn-sm AddSource' data-source = '".$crt['creative_id']."' {$lable_work_library} data-toggle='tooltip' data-placement='bottom' title='Добавить исходник креатива'><i class='fas fa-paint-brush'></i></button>&nbsp;";


	echo "<button type='button' class='btn btn-{$button_color} btn-sm AddSourceFiles' data-source = '".$crt['creative_id']."' {$lable_work_library} data-toggle='modal' data-target='#AddSourceFiles'><i class='fas fa-paint-brush'></i> ".GetFileSourceCount($crt['creative_id'])."</button>&nbsp;";
	echo "</td>";
	echo "</tr>";
}
?>
</tbody>
</table>

<!-- Модальное окно Добавления дизайна -->
<div class="modal fade modal" id="AddSourceFiles" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Добавление исходника к креативу</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<form id="DesignSendInfo" enctype="multipart/form-data">
					<input type="hidden" id="Cr_id">
					<div class="row">
						<div class="col-md-12">
							<div style = "text-align: center" class = "mt-3">
								<div class="custom-file mb-3">
									<input type="file" class="custom-file-input myRQ" id="customFile1" lang="ru" name="file[]" multiple>
									<label class="custom-file-label" for="customFile">Выбрать файлы</label>
								</div>
								<button type="reset" class="btn btn-secondary" id="BtnFormClear" data-dismiss="modal">Отмена</button>
								<button class="btn btn-primary" type="button" id="BtnSendFilesToLibrary"><i class="far fa-save"></i> Загрузить исходник</button>
							</div>
						</div>
					</div>
				</form>

			</div>
		</div>
	</div>
</div>



<!-- Отображение картинок в полный экран -->
<div id="popup" class="popup">
		<div class="popup__body">
			<div class="popup__content">
				<div class="popup__dnload"></div>
				<div class="popup__close"></div>
				<div class="popup__image"></div>
			</div>
		</div>
</div>
</div>
