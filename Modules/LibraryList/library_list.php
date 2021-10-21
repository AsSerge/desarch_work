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

// Функция получения списка файлов, входящих в дизайн и вывода из списком
function GetFilesList($design_id){
	$target_folder = DESIGN_FOLDER.$design_id;
	$files = scandir($target_folder);
	foreach ($files as $values){
		// Выводим все файлы, кроме preview.jpg
		if($values != "." AND $values != ".." AND $values != "preview.jpg" AND $values != "thumb_preview.jpg"){
			// Получаем расширение файла для вывода иконки
			$fi = new SplFileInfo($values);
			$fe = $fi->getExtension();
			switch($fe){
				case 'ai':
					$fi_img = 'Ai.svg';
					break;
				case 'eps':
					$fi_img = 'Eps.svg';
					break;
				case 'gif':
					$fi_img = 'Gif.svg';
					break;
				case 'jpg':
					$fi_img = 'Jpg.svg';
					break;
				case 'jpeg':
					$fi_img = 'Jpg.svg';
					break;
				case 'png':
					$fi_img = 'Png.svg';
					break;
				case 'svg':
					$fi_img = 'Svg.svg';
					break;
				case 'zip':
					$fi_img = 'Zip.svg';
					break;
				case 'cdr':
					$fi_img = 'Cdr.svg';
					break;
				default:
					$fi_img = 'Fil.svg';
			}

			$f_size = getimagesize("./Designes/".$design_id."/".$values);

			echo "<tr>";
			echo "<td width='5%' align='center'><img src='/images/icons/{$fi_img}' width='20px'></td>";
			echo "<td width='50%'><a href = './Designes/{$design_id}/{$values}' download>".$values."</a></td>";
			echo "<td width='10%'>".get_file_size(filesize("./Designes/{$design_id}/".$values))."</td>";
			// echo "<td width='15%'>";
			// 	if($fe == "jpg"){
			// 		echo $f_size[0]."px X".$f_size[1]."px";
			// 	}
			// echo "</td>";
			// echo "<td width='15%'>".date("F d Y H:i:s", filemtime("./Designes/{$design_id}/".$values))."</td>";
			echo "</tr>";
		}
	}
}

?>
<style>
	.tableIntable td{
		font-size: 0.8rem;
	}
</style>
	<!-- <table class='table table-sm table-light-header' id='DT_DesignList'> -->
	<table class='table table-sm' id='DT_DesignList'>
	<thead><tr><th>#</th><th>Preview</th><th>Название</th><th>Стиль</th><th>Загрузка</th><th>Источник</th><th>Дата</th></tr></thead>
	<tbody>
		<?php
		foreach($designes as $ds){
			echo "<tr>";
			echo "<td>{$ds['design_id']}</td>";
			echo "<td>";
			if(file_exists('./Designes/'.$ds['design_id'].'/preview.jpg')){
				echo "<img src = './Designes/{$ds['design_id']}/thumb_preview.jpg' width='200px' class='oneimage' big-image='./Designes/{$ds['design_id']}/preview.jpg'>";
			}
			echo "</td>";
			echo "<td>";
			
			echo "<h6><i class='fas fa-drafting-compass'></i> {$ds['design_name']}</h6>";
			echo "<table class='table table-sm tableIntable'><tbody>";
			echo GetFilesList($ds['design_id']);
			echo "</tbody></table>";

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