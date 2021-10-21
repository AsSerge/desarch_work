<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="far fa-eye" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Просмотр списока креативов</h6>
				<small><?php echo $user_name." " .$user_surname. " [".$user_role_description."]";?></small>
			</div>
</div>
<div class="my-3 p-3 bg-white rounded box-shadow">

<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта
$creative_id = $_GET['creative_id'];

// Получаем список заданий на разработку (Креативов) для данного дизайнера
	// $stmt = $pdo->prepare("SELECT * FROM сreatives as C LEFT JOIN tasks AS T ON (C.task_id = T.task_id) WHERE 1");
	
	if($creative_id == ""){	
		$stmt = $pdo->prepare("SELECT * FROM сreatives as C LEFT JOIN tasks AS T ON (C.task_id = T.task_id) LEFT JOIN users AS U ON (C.user_id = U.user_id) WHERE 1");
		$stmt->execute();
		$creatives = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}else{
		$stmt = $pdo->prepare("SELECT * FROM сreatives as C LEFT JOIN tasks AS T ON (C.task_id = T.task_id) LEFT JOIN users AS U ON (C.user_id = U.user_id) WHERE creative_id = ?");
		$stmt->execute(array($creative_id));
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
?>
<table class='table table-sm table-light-header' id="DT_CreativeList">
	<thead><tr><th>#</th><th>Разработка</th><th>Название креатива</th><th>Заимстовование</th><th>Заказчик</th><th>Дизайнер</th><th>Исполнено</th><th>Статус</th><th>Загружено дизайнов</th><th>PDF</th></tr></thead>
	<tbody>
		<?php
		foreach($creatives as $cr){
			echo "<tr>";
				echo "<td>";
				echo $cr['creative_id'];
				echo "</td>";
				echo "<td>";
				if(file_exists('./Creatives/'.$cr['creative_id'].'/preview.jpg')){
					echo "<img src = './Creatives/{$cr['creative_id']}/thumb_preview.jpg?ver=".time()."' width='150px' class='oneimage' big-image='./Creatives/{$cr['creative_id']}/preview.jpg?ver=".time()."' loading='lazy'>";
				}
				echo "</td>";
				echo "<td>";
				echo $cr['creative_name'];
				echo "</td>";
				echo "<td>";
				if($cr['creative_development_type'] != ""){
					echo $cr['creative_development_type'] ." - ". $cr['creative_magnitude'];
				}else{
					echo "Собственная разработка";
				}				
				echo "</td>";
				echo "<td>";
				echo Customer($pdo, $cr['customer_id'])['customer_name'] . " (". Customer($pdo, $cr['customer_id'])['customer_type']. ")";
				echo "</td>";
				echo "<td>";
				echo $cr['user_name'] . " " .$cr['user_surname'];
				echo "</td>";
				echo "<td>";
				echo mysql_to_date($cr['creative_end_date']);
				echo "</td>";
				echo "<td>";
				echo $cr['creative_status'];
				echo "</td>";
				echo "<td>";
				echo GetDisignesCount($pdo, $cr['creative_id']);
				echo "</td>";
				// echo "<td><button type='button' class='btn btn-info btn-sm savePDF' docID = '{$cr['creative_id']}'> <i class='far fa-file-pdf'></i> PDF</button></td>";
				echo "<td><a href = '/Modules/CreativeListView/PDF_Creation.php?creative_id={$cr['creative_id']}' type='button' class='btn btn-info btn-sm savePDF' docID = '{$cr['creative_id']}'> <i class='far fa-file-pdf'></i> PDF</a></td>";
			echo "</tr>";
		}
		?>
	</tbody>
</table>

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