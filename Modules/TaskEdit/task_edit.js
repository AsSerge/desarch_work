$(document).ready(function () {
	"use strict";

	// Формирование страницы  (Функция)
	function ImgWork(TaskId) {
		// Модальное окно для работы с изображением		
		var SetFilling = ""; // Строка для заполнения
		var TaskID = TaskId; // ID Редактируемого креатива
		var Designes = [];

		// Первоначальная загрузка данных из PHP в формате JSON 

		$.ajax({
			url: '/Modules/TaskEdit/setImagesArr.php',
			type: 'GET',
			dataType: 'json',
			data: {
				TaskID: TaskID
			},
			success: function (data_recuest) {
				if (data_recuest) {
					data_recuest.forEach(element => {
						Designes.push(element);
					});
				}
				// Функция формирования блока базовых изображений
				function GetDesignList(Designes) {
					SetFilling = "";
					for (var item in Designes) {
						SetFilling += '<div><button class="EditDesign" data-toggle="modal" data-target="#EditDesign" data-whatever="' + Designes[item] + '"><img src="' + Designes[item] + '" class="img-thumbnail"></button></div>';
					}
					return SetFilling
				}

				// Проверка наличия базовых изображений
				if (Designes.length != 0) {
					$('#BaseImagesNoN').hide();
					$('#thumb-img-set').show();
					$('#thumb-img-set').html(GetDesignList(Designes)); // Получаем все базовые изображения, для выбранного задания
				} else {
					$('#BaseImagesNoN').show();
					$('#thumb-img-set').hide();
				}

				$('#EditDesign').on('shown.bs.modal', function (event) {
					var button = $(event.relatedTarget);
					var recipient = button.data('whatever');
					var modal = $(this);
					modal.find('.modal-title').text(recipient);
					modal.find('.modal-body').html('<img src="' + recipient + '" class="img-thumbnail" alt="Желание заказчика">');

					// Скачивание файла НЕ РАБОТАЕТ!!!!	
					var ImgToDownload = $(this).parent().parent().find("h5").text();
					// console.log("Загрузка файла" + ImgToDownload);
					$("#DownloadImage").attr("href", ImgToDownload);
				});
			},
			error: function (e) {
				// alert(e.message);
			}
		});

	}
	// Удаление элемента из модального окна (Функция)
	function DelImageFromDir(imgToDel) {
		var ImgToDel = imgToDel;
		$.ajax({
			url: '/Modules/TaskEdit/delOneImage.php',
			type: 'GET',
			data: {
				ImgToDel: ImgToDel
			},
			success: function () {
				ImgWork(c_Id);
			}
		});
	}

	// Базовое формирование страницы
	ImgWork(c_Id);
	$('#result').hide();
	// Трюк с сокрытием поля
	$('#FilesDN').on('click', function () {
		$('#js-file').click();
		return false;
	});

	$('#c_Id').text(c_Id); // Показываем ID редактируемой задачи

	// Кнопка удаления изображения в модальном окне 
	$('#ClearImage').on("click", function () {
		var imgToDel = $(this).parent().parent().find("h5").text();
		setTimeout(DelImageFromDir(imgToDel), 10000);
		ImgWork(c_Id);
	});

	// Редактирование кооментария к задаче
	$(document).on("click", "#EditTaskDescription", function () {
		var TaskDescription = $('#task_description').val();
		$.ajax({
			url: '/Modules/TaskEdit/task_edit_description.php',
			type: 'post',
			data: {
				TaskID: c_Id,
				TaskDescription: TaskDescription
			},
			success: function (data) {
				$('#task_description').val(data);
				var ToasBodyText = "Комментарий к задаче обновлен";
				$('#liveToast').children(".toast-body").html("<p><i class='far fa-save'> " + ToasBodyText + "</p>");
				$('#liveToast').toast('show');
			}
		});
	});

	// Загрузка файлов в задачу (Базовые изображения)
	$('#js-file').on("change", function () {
		$('#js-form').ajaxSubmit({
			url: '/Modules/TaskEdit/LoadImages.php',
			type: 'POST',
			target: '#result',
			data: {
				TaskID: c_Id
			},
			success: function () {
				$('#js-form')[0].reset();
				ImgWork(c_Id);
				$('#result').show();
				$('#result').fadeOut(2000);
			}
		});
	});
});