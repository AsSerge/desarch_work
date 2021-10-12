<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fas fa-tools" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Редактор креатива</h6>
				<small><?php echo $user_name." " .$user_surname. " [".$user_role_description."]";?></small>
			</div>
</div>
<style>
	/* Модальное окно c HASH-тегами */
	.TagsList { 
		/* border: 1px solid var(--gray);
		border-radius: 3px; */
		display: flex;
		flex-direction: row;
		flex-wrap: wrap;
		padding: 10px;
		text-decoration: none;
		color: white;
	}
	.OneTag {
		font-size: 0.7rem;
		margin: 3px;
		padding: 5px 10px;
		height: 26px;
		border-radius: 14px;
		background-color: rgb(33, 201, 201);
	}
	.TagsLable{
		/* border: 1px solid var(--gray);
		border-radius: 3px;		 */
		font-size: 1.4rem;
		color: var(--gray);
		text-align: center;
		cursor: pointer;
		padding: 10px 0;
	}
	.TagsLableColor{		
		color: rgb(33, 201, 201);
	}
	#HashTags{
		font-size: 0.9rem;
	}
</style>
<?php
	include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта
	// Получаем ID креатива для редактирования 
	$creative_id = $_GET['creative_id'];
	echo "<script>var c_Id = {$creative_id};</script>\n\r";

	// Получаем информацию для редактирования креатива
	$stmt = $pdo->prepare("SELECT * FROM сreatives as C LEFT JOIN tasks AS T ON (C.task_id = T.task_id) WHERE C.creative_id = ?");
	$stmt->execute(array($creative_id));
	$creative = $stmt->fetch(PDO::FETCH_ASSOC);

	// echo "<pre>";
	// print_r($creative);
	// echo "</pre>";

	// Функция определения параметров заказчика
	function Customer($pdo, $customer_id){
		$stmt = $pdo->prepare("SELECT customer_name, customer_type FROM customers WHERE customer_id = ?");
		$stmt->execute(array($customer_id));
		$customer = $stmt->fetch(PDO::FETCH_ASSOC);
		return $customer;
	}

	// Получаем все комментарии текущего креатива
	$stmt_сomments = $pdo->prepare("SELECT * FROM сreative_сomments AS C LEFT JOIN users AS U ON (C.user_id = U.user_id) WHERE C.creative_id = ?");
	$stmt_сomments->execute(array($creative_id));
	$сomments = $stmt_сomments->fetchAll(PDO::FETCH_ASSOC);

	// echo "<pre>";
	// print_r($сomments);
	// echo "</pre>";

	// Получаем все список всех хэшей для Креативов
	$stmt_hash = $pdo->prepare("SELECT * FROM hash_tags WHERE 1 ORDER BY hash_name"); // Сортировка, т.к. хэши обновляются
	$stmt_hash->execute();
	$hash_tags = $stmt_hash->fetchAll(PDO::FETCH_ASSOC);


	// Получаем массив использованных хашей для данного креатива
	$stmt_used_hash = $pdo->prepare("SELECT creative_hash_list FROM сreatives WHERE creative_id = ?");
	$stmt_used_hash->execute(array($creative_id));
	$used_hash_tags = $stmt_used_hash->fetch(PDO::FETCH_ASSOC);
	$used_hash_tags_array = explode("|", $used_hash_tags['creative_hash_list']);	
	
?>

<div class="my-3 p-3 bg-white rounded box-shadow">
	<div class="row">
		<div class="col-md-3">
			<!-- Карточка задачи -->
			<div class="task_card shadow p-3 mb-5 rounded">
					<div class="task_card_title lh-100 rounded">
						<h6 class = 'p-2 text-white lh-100'><i class="fas fa-tasks"></i> Задача [<?=$creative['task_number']?>]</h6>
					</div>
					<div class="task_card_body m-2 pb-2" style = 'font-size: 0.8rem'>
						<table class='table table-sm table-light-header'>
							<tr><td class='span_bolder'>ID:</td><td><?=$creative['task_id']?></td></tr>
							<tr><td class='span_bolder'>Название:</td><td><?=$creative['task_name']?></td></tr>
							<tr><td class='span_bolder'>Заказчик:</td><td><?=Customer($pdo, $creative['customer_id'])['customer_name']?><t/></tr>
							<tr><td class='span_bolder'>Канал:</td><td><?=Customer($pdo, $creative['customer_id'])['customer_type']?></td></tr>
							<tr><td class='span_bolder'>Дата постановки:</td><td><?=mysql_to_date($creative['task_setdatetime'])?></td></tr>
							<tr><td class='span_bolder'>Крайний срок:</td><td><?=mysql_to_date($creative['task_deadline'])?></td></tr>
							<tr><td colspan="2"><span class='span_bolder'>Описание задачи: </span><?=$creative['task_description']?></td></tr>
						</table>

						<div>
							<?php
							// Выводим картинки, если есть
							$task_folder=TASK_FOLDER.$creative['task_id'];
							// Функция получения массива файлов-изображений из заданной папки
							function GetImagesArr($dir, $id){
								$file = [];
								$sc_dir = $dir.$id;
								$files = scandir($sc_dir);
								foreach ($files as $values){
									// Выводим только файлы-изображения JPEG
									if($values != "." AND $values != ".."){
										if(exif_imagetype($sc_dir."/".$values) == IMAGETYPE_JPEG ){
											$file[] = "/Tasks/".$id."/".$values;
										}	
									}
								}
								return $file; 
							}
							// Формируем массив базовых исзобажений для задачи
							$cr_files = GetImagesArr(TASK_FOLDER, $creative['task_id']);
							if(count($cr_files) > 0){
								echo "<div class = 'SmallImagesTaskCard'>";
								foreach($cr_files as $img){
									echo "<div class='oneimage' big-image='{$img}'><img src='{$img}' alt = ''></div>";
								}
								echo "</div>";
							}
							?>

						</div>
					</div>
				</div>
			</div>
		
			<!-- Описание креатива-->
		<div class="col-md-9">
			<!-- Меню описание креатива -->
			<ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
			<!-- <ul class="nav nav-pils" id="myTab" role="tablist"> -->
				<li class="nav-item" role="presentation">
					<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Креатив / Источник</a>
				</li>
				<li class="nav-item" role="presentation">
					<a class="nav-link" id="description-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Описание креатива</a>
				</li>
				<li class="nav-item" role="presentation">
					<a class="nav-link" id="grades-tab" data-toggle="tab" href="#grades" role="tab" aria-controls="grades" aria-selected="false">Оценки и отзывы</a>
				</li>
			</ul>

			<div class="tab-content" id="myTabContent">
				<!-- Креатив / источник -->
				<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
					<div class="row mt-3">
						<div class="col-6">
								<div class="form-group">
									<h6 class="border-bottom border-gray pb-3 mb-2"><i class="far fa-images"></i> Креатив</h6>

									<div class="alert alert-warning" role="alert" id = "PreviewImageNoN">
										Изображения отсутствуют
									</div>	
									<div class="ImageSet mb-3" id="PreviewImages"></div>
									<div class="custom-file mb-2">
										<div class="col" style="text-align: center;">
											<form id="PreviewFileLoad" enctype="multipart/form-data">
												<!-- <input id="PreviewFile" type="file" name="file[]" multiple style='display: none;'> -->
												<input id="PreviewFile" type="file" name="file" style='display: none;'>
												<button type="button" class="btn btn-primary btn-sm" id="FilesDN"><i class="fas fa-file-upload"></i> Загрузить Preview</button>
											</form>
										</div>

									</div>
									<div id="resultPreview"></div>


								<!-- Теги для креатива -->
								<div class="col-sm-12 mb-2">
										<div class="row mb-2">
											<div class="col-md-1">
												<div class="TagsLable"><i class="fas fa-tags align-baseline" id="OpenTagsGialog"></i></div>
											</div>
											<div class="col-md-11">
												<div class="TagsList"></div>
											</div>
										</div>
										<div class="row" id="HashTagsRow">
												<div class="col-md-12">
													<select class="custom-select" size="5" id="HashTags" multiple>
													<?php
														foreach ($hash_tags as $h){
															$f = (in_array($h['hash_name'], $used_hash_tags_array)) ? 'selected' : ''; 
															echo "<option value='{$h['hash_name']}' {$f}>{$h['hash_name']}</option>";
														}
														?>
													</select>
												</div>
												<div class="col-md-9 mt-3">
													<input class="form-control form-control-sm" type="text" id="NewHashTag" placeholder="Новый HASH-тег">
												</div>
												<div class="col-md-3 mt-3">	
													<button type="button" class="btn btn-info btn-sm w-100" id="NewHashTagBtn"><i class="fas fa-tag"></i> Добавить</button>
												</div>
										</div>
									</div>
								</div>
							</div>
							
							<div class="col-6">

								<div class="form-group">
									<h6 class="border-bottom border-gray pb-3 mb-2"><i class="far fa-images"></i> Источник</h6>

									<div class="alert alert-warning" role="alert" id = "BaseImageNoN">
										Изображения отсутствуют
									</div>	
									<div class="ImageSet mb-3" id="BaseImages"></div>
									<div class="custom-file mb-2">
										<div class="col" style="text-align: center;">
											<form id="BaseFileLoad" enctype="multipart/form-data">
												<input id="BaseFile" type="file" name="file[]" multiple style='display: none;'>
												<button type="button" class="btn btn-primary btn-sm" id="BaseFilesDN"><i class="fas fa-file-upload"></i> Загрузить Base</button>
											</form>
										</div>

									</div>
									<div id="resultBase"></div>
								</div>
							</div>	
		
					</div>
				</div>

				<!-- Описание креатива -->
				<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
					<div class="row mt-3">
						<div class="col-md-4">
							<div class="OnePreviewImage mt-5"></div>
						</div>	
						<div class="col-md-8">
							<form id="SendCreativeAllInfo">
								<div class="form-group">
								<h6 class="border-bottom border-gray pb-3 mb-2"><i class="far fa-images"></i> Описание креатива</h6>
									<div class="col-sm-12 mb-2">
										<div class="alert alert-secondary" role="alert">
											Дата начала работы: <strong><?=mysql_to_date($creative['creative_start_date'])?></strong>
										</div>
									</div>
									<div class="col-sm-12 mb-2">
										<label for="creative_name">Название креатива [<?=$creative_id?>]</label>
										<input type="text" class="form-control" id="creative_name" name="creative_name" value="<?=$creative['creative_name']?>">
									</div>
									<div class="col-sm-12 mb-2">
											<label for="creative_style">Стиль креатива</label>
											<select class="custom-select" id="creative_style" name="creative_style">
												
												<?php
												if($creative['creative_style'] == ""){
													echo "<option value=''>Выберете...</option>";
												}
												foreach($array_creative_style as $c_style){
													$sel_lable = ($c_style == $creative['creative_style'])? 'selected':'';
													echo"<option value='{$c_style}' {$sel_lable}>{$c_style}</option>";
												}
												?>
											</select>
									</div>
									<div class="col-sm-12 mb-2">
										<div class="row">
											<div class="col-sm-6 mb-2">
												<label for="creative_development_type">Тип креатива</label>
												<select class="custom-select" id="creative_development_type" >
													<?php
													if($creative['creative_development_type'] == ""){
														echo "<option value=''>Выберете...</option>";
													}
													foreach($array_creative_development_type as $c_type){
														$sel_lable = ($c_type == $creative['creative_development_type'])? 'selected':'';
														echo"<option value='{$c_type}' {$sel_lable}>{$c_type}</option>";
													}
													?>
												</select>
											</div>
											<div class="col-sm-6 mb-2">
											<label for="creative_magnitude">Заимствование</label>
												<select class="custom-select" id="creative_magnitude" >

													<?php
													if($creative['creative_magnitude'] == ""){
														echo "<option value=''>Выберете...</option>";
													}
													foreach($array_creative_magnitude as $c_mag){
														$sel_lable = ($c_mag == $creative['creative_magnitude'])? 'selected':'';
														echo"<option value='{$c_mag}' {$sel_lable}>{$c_mag}</option>";
													}
													?>
												</select>
											</div>
										</div>
									</div>
									<div class="col-sm-12 mb-2">
										<label for="creative_source">Источник вдохновения</label>
											<select class="custom-select" id="creative_source" >
												<?php
												if($creative['creative_source'] == ""){
													echo "<option value=''>Выберете...</option>";
												}
												foreach($array_creative_source as $c_source){
													$sel_lable = ($c_source == $creative['creative_source'])? 'selected':'';
													echo"<option value='{$c_source}' {$sel_lable}>{$c_source}</option>";
												}
												?>
											</select>
									</div>
									<div class="col-sm-12 mb-2">
										<label for="creative_description">Описание креатива</label>
										<textarea class="form-control mb-2" name="creative_description" id="creative_description" cols="3" rows="3"><?=$creative['creative_description']?></textarea>
									</div>
								</div>
								<hr>
								<div class="col" style="text-align: center;">
									<button type="button" class="btn btn-primary" id="CreativeInfoUpdate"><i class="far fa-save"></i> Сохранить описание</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- Оценки и отзывы -->
				<div class="tab-pane fade" id="grades" role="tabpanel" aria-labelledby="grades-tab">
					<div class="row mt-3" id="InfoGrades">
						<div class="col-md-4 text-center mb-3">
							<button type="button" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Отправка на утверждение руководителю" id="SendToApproval"><i class="far fa-share-square"></i> Отправить креатив на рассмотрение</button>
						</div>
						<div class="col-md-8">
							<div class="alert alert-primary" role="alert">
							<i class="fas fa-info-circle"></i> После разработки или доработки креатива отправьте его на рассмотрение Вашему руководителю, нажав кнопку "Отправить креатив на рассмотрени". На время рассморения, доступ к редактированию креатива будет преостановлен. Для креатива собственной разработки достаточно загрузки Preview-afqkf. Для компилированного креатива необходимо хотя бы одно базовое изображение. 
							</div>
						</div>
					</div>
					<style>
						.BlockComments{
							display: flex;
							justify-content: left;
							flex-wrap: wrap;
						}
						.OneComment{
							max-width: 400px;
							min-width: 300px;
							background-color: var(--light);
							margin: 0.5rem;
						}

						.positiveComment{
							background-color: #ffface;
						}

						.negativeComment{
							background-color: #ffabab;
						}


						.CommentSignature{
							font-size: 0.9rem;
							font-weight: 500;
						}
						.CommentSignature:BEFORE{
						}
					</style>


					<div class="row mt-3">
						<div class="col-md-12">
						<h6 class="border-bottom border-gray pb-3 mb-2"><i class="far fa-images"></i> Оценки и отзывы</h6>
								<?php
								if(count($сomments) > 0){
									echo "<div class='BlockComments'>";	
									foreach($сomments as $cmt){

										$str_data = mysql_to_date(explode(" ", $cmt['creative_comment_update'])[0]);
										$str_time = explode(" ", $cmt['creative_comment_update'])[1];
										$commentcolor = ($cmt['creative_comment_focus'] == 'positive') ? 'positiveComment' : 'negativeComment';
										echo "<div class='OneComment {$commentcolor} shadow p-3 mb-3 rounded'>";
										echo "<i class='far fa-envelope-open'></i>&nbsp;<b>Сообщение</b><hr>";
										echo "{$cmt['creative_comment_content']}<hr><span class='CommentSignature'>{$cmt['user_name']}&nbsp;{$cmt['user_surname']}&nbsp;|&nbsp;{$str_data}&nbsp;{$str_time}</span>";
										echo "</div>";
									}
									echo "</div>";
								}else{
									echo"<div class='alert alert-success' role='alert'>В настоящее время нет оценок и отзывов по данному креативу.</div>";
								}
								?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>


<!-- Модальное окно Просмотр и удаление Base изображений -->
<div class="modal fade" id="EditBaseDesign" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Заголовок модального окна</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
				</div>
			<div class="modal-footer" style="margin:auto">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Закрыть</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal" id="ClearImage"><i class="far fa-trash-alt"></i> Удалить</button>
			</div>
			</div>
		</div>
	</div>


<!-- Системные сообщения (Сохранение изменений)  -->
<div class="position-fixed bottom-0 right-0 p-3" style="z-index: 5; left: 0; bottom: 0;">
	<div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="1000" style="background-color: #ffc107">
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

