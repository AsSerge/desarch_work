<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fas fa-photo-video" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Библиотека загруженных дизайнов</h6>
				<small><?php echo $user_name." " .$user_surname. " [".$user_role_description."]";?></small>
			</div>
</div>
<div class="my-3 p-3 bg-white rounded box-shadow">
<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта
// Получаем список загруженных дизайнов
$stmt = $pdo->prepare("SELECT D.design_id, D.design_name, D.design_source_url, D.design_creative_style, D.design_update, U.user_name, U.user_surname  FROM designes as D LEFT JOIN users AS U ON (D.user_id = U.user_id) WHERE 1");
$stmt->execute();
$designes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
	<table class='table table-sm table-light-header' id='DT_DesignList'>
	<thead><tr><th>#</th><th>Preview</th><th>Название</th><th>Стиль</th><th>Загрузка</th><th>Источник</th><th>Дата</th></tr></thead>
	<tbody>
		<?php
		foreach($designes as $ds){
			echo "<tr>";
			echo "<td>{$ds['design_id']}</td>";
			echo "<td>";
			if(file_exists('./Designes/'.$ds['design_id'].'/preview.jpg')){
				echo "<img src = './Designes/{$ds['design_id']}/preview.jpg' width='200px' class='oneimage' big-image='./Designes/{$ds['design_id']}/preview.jpg'>";
			}
			echo "</td>";
			echo "<td>";
			echo "<a href=''data-toggle='collapse' href='#collapseExample' role='button' aria-expanded='false' aria-controls='collapseExample'>{$ds['design_name']}</a>";
			echo "</td>";
			echo "<td>{$ds['design_creative_style']}</td>";
			echo "<td>{$ds['user_name']} {$ds['user_surname']}</td>";
			echo "<td>{$ds['design_source_url']}</td>";
			echo "<td>{$ds['design_update']}</td>";
			echo "</td>";
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