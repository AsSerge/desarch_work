<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fas fa-balance-scale-right" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Креатив для рассмотрения</h6>
				<small><?php echo $user_name." " .$user_surname. " [".$user_role_description."]";?></small>
			</div>
</div>
<?php
require_once ($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php");
// Получаем ID креатива
$creative_id = $_GET['creative_id'];

echo "<script>let creative_id = {$creative_id};\n\rlet user_id = {$user_id};</script>";

// Функция получения массива файлов-изображений из заданной папки
function GetImagesArr($dir, $id){
	$file = [];
	$sc_dir = $dir.$id;
	$files = scandir($sc_dir);
	foreach ($files as $values){
		// Выводим только файлы-изображения JPEG кроме preview.jpg
		if($values != "." AND $values != ".." AND $values != "preview.jpg"){
			if(exif_imagetype($sc_dir."/".$values) == IMAGETYPE_JPEG){
				$file[] = "/Creatives/".$id."/".$values;
			}
		}
	}
	return $file; 
}
// Формируем массив базовых исзобажений для задачи
$cr_files = GetImagesArr(CREATIVE_FOLDER, $creative_id);
?>

<style>
	.preview_img{
		display: flex;
		align-items: center;
		justify-content: center;
	}
	.preview_img img{
		width: 100%;
	}
	.base_img{
		display: flex;
		align-items: center;
		justify-content: center;
		justify-content: space-around;
		flex-wrap: wrap;
		background-color: var(--light);
	}
	.base_img img{
		width: 200px;
		margin: 2px;
		padding: 5px;
	}	

/* Комментарии к дизайну */	
	
	.MyComment textarea{
		font-size: 0.9rem;
	}
	.SetComment{
		cursor: pointer;
	}


</style>

<div class="my-3 p-3 bg-white rounded box-shadow">	
<div class="row">
	<div class="col-md-4 mb-2">
		<h6 class="border-bottom border-gray pb-3 mb-2"><i class="far fa-images"></i> Креатив</h6>
		<div class=' preview_img'>
			<div class='oneimage' big-image='/Creatives/<?=$creative_id?>/preview.jpg'><img src='/Creatives/<?=$creative_id?>/preview.jpg' alt = ''></div>
		</div>
	</div>
	<div class="col-md-4 mb-2">
		<h6 class="border-bottom border-gray pb-3 mb-2"><i class="far fa-images"></i> Исходник</h6>
		<div class='base_img'>
			<?php
			foreach($cr_files as $crf){
				echo "<span class='oneimage' big-image='{$crf}'><img src='{$crf}' alt = ''></span>";
			}
			?>
		</div>
	</div>
	
	
	<div class="col-md-4 mb-2">
		<h6 class="border-bottom border-gray pb-3 mb-2"><i class="far fa-images"></i> Блок голосования</h6>
		<div class="alert alert-warning" id = "FTMyRadio" role="alert">Для оценки дизайна (креатива) необходимо кликнуть на переключателе, выбрав <i class="far fa-thumbs-up"></i> (Принят),<i class="fas fa-tools"></i> (На доработку) или <i class="fas fa-balance-scale-right"></i> (На комиссию).
		<br>Если вы ходтите оставить комметнарий <span class="SetComment"><i class="far fa-comment-dots"></i></span> для дизайнера - впишите его в поле комментариев</div>
		<form action="#">
			<div class='p-2' style="text-align: center;">
				<button id="BtnOn" type="button" class="btn btn-outline-success"><i class="far fa-thumbs-up"></i> Принят</button>
				<button id="BtnOff" type="button" class="btn btn-outline-warning"><i class="fas fa-tools"></i> На доработку</button>
				<button id="BtnCheck" type="button" class="btn btn-outline-danger"><i class="fas fa-balance-scale-right"></i> На комиссию</button>
			</div>

			<style>
				.rejection{
				}
			</style>

			<div class='p-2' id='rejectionReasonBlock'>
				<label for="rejectionReason">Причина отправки на доработку</label>
					<select class="form-control form-control-sm rejection" id="rejectionReason" name = "rejectionReason">
						<option value="">Выберете...</option>
						<?php
							foreach ($rejectionReason as $reas){
									echo "<option value='{$reas}'>{$reas}</option>";
							}
						?>
					</select>
			</div>

			<div class='p-2' style="text-align: center;">
				<button id="SetComment" class="btn btn-info btn-sm" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" >Комментарий <i class="far fa-comment-dots"></i></button>
			</div>
			<div class="collapse" id="collapseExample">
				<div class="card card-body bg-light MyComment">
					<textarea class="form-control mb-2" name="v_description" id="v_description" cols="3" rows="5" placeholder='Оставьте Ваш комментарий'></textarea>
				</div>
			</div>
			<hr>
			<div class = 'pt-3' style = "text-align: center;">
				<button id="SendVote" type="button" class="btn btn-primary">ГОЛОСОВАТЬ</button>
			</div>
		</form>
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