<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fas fa-swatchbook" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Редактор библиотеки дизайнов</h6>
				<small><?php echo $user_name." " .$user_surname. " [".$user_role_description."]";?></small>
			</div>
</div>

<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта
// Получаем ID креатива для редактирования 
$creative_id = $_GET['creative_id'];
echo "<script>var c_Id = {$creative_id};</script>\n\r";

$stmt = $pdo->prepare("SELECT * FROM designes WHERE creative_id = ?");
$stmt->execute(array($creative_id));
$designes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// echo "<pre>";
// print_r($designes);
// echo "</pre>";
?>
<!-- Библиотека -->
<div class="my-3 p-3 bg-white rounded box-shadow">
	<div class="row mt-3">
			<div class="col">
				<div class="alert alert-primary" role="alert">
				<i class="fas fa-info-circle"></i> После утверждения креатива ВСЕМИ участниками приемной комиссии и принятия решения о преобретении креатива - необходимо загрузить преобретенные изображения в библиотеку. При узке необходимо определить Preview файл (jpg или png). Затем, в каталог библиотеки загружаются все файла, составляющие преобретенный дизайн. Дизайну присваивается название направление и произвольное имя.
				</div>
			</div>
	</div>

	<style>
		.DesignList{
			/* border:  1px dotted var(--purple);
			border-radius: 5px; */
			padding: 5px;
			margin-bottom: 0.5rem;
		}
		.OneLibraryDesign{

		}
		.FileList{

		}
		.PreviewFile{

		}
	</style>

	<?php
	// Функция загрузки списка файлов, принадлежащих текущему краетиву
	function GetFilesList($design_id){

		$target_folder = DESIGN_FOLDER.$design_id;
		$files = scandir($target_folder);
		foreach ($files as $values){
			// Выводим все файлы, кроме preview.jpg
			if($values != "." AND $values != ".." AND $values != "preview.jpg"){

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

				echo "<tr>
				<td width='5%' align='center'><img src='/images/icons/{$fi_img}' width='25px'></td>
				<td width='50%'><a href = './Designes/{$design_id}/{$values}' download>".$values."</a></td>
				<td width='10%'>".get_file_size(filesize("./Designes/{$design_id}/".$values))."</td>";
				echo "<td width='15%'>";
					if($fe == "jpg"){
						echo $f_size[0]."px X".$f_size[1]."px";
					}
				echo "</td>";
				echo "<td width='15%'>".date("F d Y H:i:s", filemtime("./Designes/{$design_id}/".$values))."</td>
				</tr>";
				
			}
		}
	}

		// Грузим страницы из библиотеки
		if (count($designes) > 0){
			echo "<div class='row mt-3'>";
			echo 	"<div class='col'>";
			echo "		<h6 class='border-bottom border-gray pb-3 mb-2'><i class='far fa-images'></i> Библиотека</h6>";
			foreach($designes as $des){
				// Начало дизайна
				echo "<div class='row mb-3'>";
				echo "	<div class='col-md-2'>";
				
				echo "	<div class='DesignList PreviewFile'></div>";

				$f_size = getimagesize("./Designes/{$des['design_id']}/preview.jpg"); // Найдем размер preview.jpg

				echo "		<img src = './Designes/{$des['design_id']}/preview.jpg' width = '100%' class='oneimage' big-image='./Designes/{$des['design_id']}/preview.jpg' title = '{$f_size[0]} X $f_size[1]'>";
				
				echo "	</div>";
				echo "	<div class='col-md-10'>";
				echo "		<div class='DesignList OneLibraryDesign'><h6>{$des['design_name']} ({$des['design_creative_style']}) {$des['design_source_url']}</h6></div>";
				echo "		<div class='DesignList FileList'>";
				// echo "		<h6>Cписок файлов дизайна</h6>";
				echo "			<table class='table table-striped table-sm'><tbody>";
				echo 			GetFilesList($des['design_id']);
				echo "			</tbody></table>";
				echo "		</div>";
				echo "	</div>";
				echo "</div>";
				// Коеенец дизайна
			}
			echo 	"</div>";
			echo "</div>";
		}
	?>
	<div class="row">
		<div class="col" style="text-align: center;">
			<button class="btn btn-outline-success" type="button" data-toggle="modal" data-target="#AddDesign">Добавить дизайн в библиотеку</button>
		</div>
	</div>	

</div>



<!-- Модальное окно Добавления дизайна -->
<div class="modal fade modal" id="AddDesign" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Добавление дизайна в библиотеку</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<form id="DesignSendInfo" enctype="multipart/form-data">
					<input type="hidden" name="user_id" value="<?=$user_id?>"> 
					<div class="row">
						<div class="col-md-12">
							<label for="design_name">Введите название</label>
							<input type="text" class="form-control form-control-sm myRQ" id="design_name" aria-describedby="emailHelp" name="design_name">


							<label for="design_creative_style">Введите направление дизайна</label>
							<select class="form-control form-control-sm myRQ" id="design_creative_style" name = "design_creative_style">
								<?php
									echo "<option value=''>Выберете...</option>";
									foreach($array_creative_style as $acs){
										echo "<option value='{$acs}'>{$acs}</option>";
									}
								?>
							</select>

							<label for="design_source_url">Внешний ресурс</label>
							<select class="form-control form-control-sm myRQ" id="design_source_url" name = "design_source_url">
								<?php
									echo "<option value=''>Выберете...</option>";
									foreach($array_creative_source as $acsrc){
										echo "<option value='{$acsrc}'>{$acsrc}</option>";
									}
								?>
							</select>

							<div style = "text-align: center" class = "mt-3">
								<div class="custom-file mb-3">
									<input type="file" class="custom-file-input myRQ" id="customFile1" lang="ru" name="file[]" multiple>
									<label class="custom-file-label" for="customFile">Выбрать файлы</label>
								</div>
								<button type="reset" class="btn btn-secondary" id="BtnFormClear" data-dismiss="modal">Отмена</button>
								<button class="btn btn-primary" type="button" id="BtnSendFilesToLibrary"><i class="far fa-save"></i> Сохранить изменения</button>
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

