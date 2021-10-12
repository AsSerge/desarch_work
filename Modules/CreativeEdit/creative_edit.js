$(document).ready(function () {
	//Git testing

	"use strict";
	// Начальная настройка
	$('#PreviewImageNoN').show();
	$('#BaseImageNoN').show();
	$('#PreviewImages').hide();
	$('#BaseImages').hide();
	$('#HashTagsRow').hide();

	// ОБЯЗАТЕЛЬНОЕ ОТКЛЮЧЕНИЕ КЭША ДЛЯ БРАУЗЕРА!!!!
	$.ajaxSetup({
		cache: false
	});


	// HASH теги
	// Заполнение сессионного массива ХЭШ ИСПОЛЬЗОВАННЫХ тегов
	$.ajax({
		url: '/Modules/CreativeEdit/get_used_hash.php',
		type: 'post',
		datatype: 'html',
		data: {
			creative_id: c_Id
		},
		success: function (data) {
			var LongLine = "";
			var hash_arr = jQuery.parseJSON(data);
			var hash_array = Object.entries(hash_arr); // Преобразуем Объект в массив для перебора
			if (hash_array.length > 1) {
				hash_array.forEach(function (item) {
					LongLine += "<div class='OneTag OneTagSelected'>" + item[1] + "</div>";
				});
				$('.TagsList').html(LongLine); //Заполняем тегами варианты
			}
		}
	});


	// Управление блоком тегов
	$('#OpenTagsGialog').on("click", function () {
		$('.TagsLable').toggleClass('TagsLableColor');
		$('#HashTagsRow').toggle('slow');
	});
	// Сокрытие блока тегов при покидании вкладки
	$(document).on('shown.bs.tab', function () {
		$('#HashTagsRow').hide();
	});


	// Подлив в базу информации о тегах
	$('#HashTags').on("change", function () {
		var tagsString = "";
		var tags = $(this).val();
		tags.forEach(function (item) {
			tagsString += "<div class='OneTag OneTagSelected'>" + item + "</div>";
		});
		$('.TagsList').html(tagsString);
		// console.log(tags);
		$.ajax({
			url: '/Modules/CreativeEdit/update_creative_tags.php',
			type: 'post',
			datatype: 'html',
			data: {
				creative_id: c_Id,
				tags: tags
			},
			success: function (data) {
				// console.log(data);
			}
		});
	})

	// Добавление в базу новых тегов
	$('#NewHashTagBtn').on("click", function () {
		var newhashtag_name = $('#NewHashTag').val();
		$.ajax({
			url: '/Modules/CreativeEdit/update_list_tags.php',
			type: 'post',
			datatype: 'html',
			data: {
				hash_name: newhashtag_name
			},
			success: function (data) {
				// console.log(data);
				location.reload();
			}
		});
	});



	// Для УТВЕРЖДЕННОГО креатива скрываем некоторые кнопки
	$.ajax({
		url: '/Modules/CreativeEdit/check_creative_status.php',
		type: 'post',
		datatype: 'html',
		cache: false,
		data: {
			creative_id: c_Id
		},
		success: function (data) {
			if (data == "Принят") {
				console.log("Начинаем");
				$('#FilesDN').hide(); // Кнопка загрузки Изображения
				$('#BaseFilesDN').hide(); // Кнопка загрузки Изображения
				$('#ClearImage').hide(); // Кнопка удаления Изображения в popUP окне
				$('#CreativeInfoUpdate').hide(); // Кнопка сохранения информации о креативе
				$('#SendToApproval').hide(); // Кнопка отправки на утверждение
				$('#InfoGrades').hide(); // Информационная панель
				$('#SendCreativeAllInfo').find('input, select, textarea').attr('disabled', true);
			}
		}
	});

	// Установка значения коэффицианта заимстования
	var testCDT = $('#creative_development_type').val();
	if (testCDT == "Собственная разработка") {
		// console.log(">>> " + testCDT);
		$('#creative_magnitude').val("до 50%");
		$('#creative_magnitude').prop('disabled', true);
	} else {
		$('#creative_magnitude').prop('disabled', false);
	}

	// Получение preview картинки креатива
	GetPreviewImage(c_Id);
	GetBaseImage(c_Id);

	// Инициализируем локальное хранилище
	sessionStorage.setItem('PreviewImage', '');
	sessionStorage.setItem('BaseImage', '');
	// sessionStorage.setItem('CreativeDevelopmentType', '');


	// Сокрытие поля загрузки файлов preview и base (настройка кнопок)
	$('#FilesDN').on('click', function () {
		$('#PreviewFile').click();
		return false;
	});

	$('#BaseFilesDN').on('click', function () {
		$('#BaseFile').click();
		return false;
	});

	// Функция первоначальной проверки наличия файла preview.jpg
	function GetPreviewImage(CreativeId) {
		var creative_id = CreativeId;
		$.ajax({
			url: '/Modules/CreativeEdit/check_preview_file.php',
			type: 'post',
			datatype: 'html',
			cache: false,
			data: {
				creative_id: c_Id,
				preview_file: 'preview.jpg'
			},
			success: function (data) {
				var check_result = data;
				if (check_result == "YES") {
					// Обманываем кэширование
					var dummy = new Date();
					$('#PreviewImageNoN').hide();
					$('#PreviewImages').show();
					$('#PreviewImages').html("<img src = '/Creatives/" + creative_id + "/preview.jpg?ver=" + dummy.getTime() + "' width = '100%'>");
					$('.OnePreviewImage').html("<img src = '/Creatives/" + creative_id + "/preview.jpg?ver=" + dummy.getTime() + "' width = '100%'>");
					sessionStorage.setItem('PreviewImage', true); // Пишем в локальное хранилище
				} else {
					$('#PreviewImageNoN').show();
					$('#PreviewImages').hide();
					sessionStorage.setItem('PreviewImage', false); // Пишем в локальное хранилище
				}
			}
		});
	}

	// Загрузка Preview файла для дизайна
	$('#PreviewFile').on("change", function () {
		$('#PreviewFileLoad').ajaxSubmit({
			url: '/Modules/CreativeEdit/PreviewFileLoad.php',
			type: 'post',
			target: '#resultPreview',
			data: {
				creative_id: c_Id
			},
			success: function () {
				$('#PreviewFileLoad')[0].reset();
				$('#resultPreview').show();
				$('#resultPreview').fadeOut(2000);
				GetPreviewImage(c_Id);
			}
		});
	});

	// Функция проверки Base файлов в каталоге 
	function GetBaseImage(CreativeId) {
		var SetFilling = "";
		var creative_id = CreativeId;
		var Designes = [];
		$.ajax({
			url: '/Modules/CreativeEdit/setImagesArr.php',
			type: 'get',
			datatype: 'json',
			data: {
				creative_id: creative_id
			},
			success: function (data_recuest) {
				if (data_recuest) {
					var jfiles = JSON.parse(data_recuest);
					jfiles.forEach(el => Designes.push(el));
				}
				// Функция формирования блока базовых изображений
				function GetDesignList(Designes) {
					SetFilling = "";
					for (var item in Designes) {
						SetFilling += '<div><button class="EditDesign" data-toggle="modal" data-target="#EditBaseDesign" data-whatever="' + Designes[item] + '"><img src="' + Designes[item] + '" class="img-thumbnail"></button></div>';
					}
					return SetFilling
				}
				if (Designes.length != 0) {
					$('#BaseImageNoN').hide();
					$('#BaseImages').show();
					$('#BaseImages').html(GetDesignList(Designes));
					sessionStorage.setItem('BaseImage', true);
				} else {
					$('#BaseImageNoN').show();
					$('#BaseImages').hide();
					sessionStorage.setItem('BaseImage', false);
				}
				// Формирование модального окна
				$('#EditBaseDesign').on('shown.bs.modal', function (event) {
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

			}
		});
	}

	// Удаление элемента в модальном окне (Функция)
	function DelImageFromDir(imgToDel) {
		var ImgToDel = imgToDel;
		$.ajax({
			url: '/Modules/CreativeEdit/delOneImage.php',
			type: 'GET',
			data: {
				ImgToDel: ImgToDel
			},
			success: function () {
				GetBaseImage(c_Id);
			}
		});
	}

	// Кнопка удаления изображения в модальном окне 
	$('#ClearImage').on("click", function () {
		var imgToDel = $(this).parent().parent().find("h5").text();
		setTimeout(DelImageFromDir(imgToDel), 10000);
		GetBaseImage(c_Id);
	});


	// Загрузка базовых файлов
	$('#BaseFileLoad').on("change", function () {
		$('#BaseFileLoad').ajaxSubmit({
			url: '/Modules/CreativeEdit/BaseFileLoad.php',
			type: 'post',
			target: '#resultBase',
			data: {
				creative_id: c_Id
			},
			success: function () {
				$('#BaseFileLoad')[0].reset();
				$('#resultBase').show();
				$('#resultBase').fadeOut(2000);
				GetBaseImage(c_Id);
			}
		});
	});


	// Проверка состояния Переключателя "Тип Креатива" -> Изменение коэффицианта заимстовавания
	$('#creative_development_type').on("change", function () {
		var testCDT = $('#creative_development_type').val();

		sessionStorage.setItem('CreativeDevelopmentType', testCDT); // Устанавливаем тип разработки в sessionStorage

		if (testCDT == "Собственная разработка") {
			// console.log(">>> " + testCDT);
			$('#creative_magnitude').val("до 50%");
			$('#creative_magnitude').prop('disabled', true);
		} else {
			$('#creative_magnitude').prop('disabled', false);
		}
	})
	// Обновление информации в форме
	$('#CreativeInfoUpdate').on("click", function () {
		// Проверка: все необходимые поля формы должны быть заполнены
		var ErrForm = [];
		var creative_name = $('#creative_name').val();
		var creative_style = $('#creative_style').val();
		var creative_development_type = $('#creative_development_type').val();
		var creative_magnitude = $('#creative_magnitude').val();
		var creative_source = $('#creative_source').val();
		var creative_description = $('#creative_description').val();

		ErrForm[0] = creative_name == "" ? 0 : 1;
		ErrForm[1] = creative_style == "" ? 0 : 1;
		ErrForm[2] = creative_development_type == "" ? 0 : 1;
		ErrForm[3] = creative_magnitude == "" ? 0 : 1;
		ErrForm[4] = creative_source == "" ? 0 : 1;

		// Подсчет суммы элементов массива
		var setNumberFileds = ErrForm.reduce(function (a, b) {
			return a + b
		}, 0);

		if (setNumberFileds < 5) {
			var ToasBodyText = "Все поля должны быть заполнены!";
			$('#liveToast').children(".toast-body").html("<p><i class='far fa-save'> " + ToasBodyText + "</p>");
			$('#liveToast').toast('show');

		} else {
			$.ajax({
				url: '/Modules/CreativeEdit/update_creative_info.php',
				type: 'POST',
				datatype: 'html',
				data: {
					creative_id: c_Id,
					creative_name: creative_name,
					creative_style: creative_style,
					creative_development_type: creative_development_type,
					creative_magnitude: creative_magnitude,
					creative_source: creative_source,
					creative_description: creative_description
				},
				success: function (data) {
					// console.log(data);

					var ToasBodyText = "Информация о креативе обновлена";
					$('#liveToast').children(".toast-body").html("<p><i class='far fa-save'> " + ToasBodyText + "</p>");
					$('#liveToast').toast('show');

					// location.reload();
					// $('#myTab a[href="#profile"]').tab('show');
				}
			});
		}

	});

	// Проверка состояния для активации кнопки отправки
	$('#myTab a[href="#grades"]').on('click', function (event) {
		event.preventDefault();
		// Получаем тип разработки - для собственной разработки источники вдохновения НЕ нужны для отправки на проверку

		var check_creative_development_type = "";
		var PreviewImage = sessionStorage.getItem('PreviewImage');
		var BaseImage = sessionStorage.getItem('BaseImage');

		// Проверяем состояние поля creative_development_type в базе
		$.ajax({
			url: '/Modules/CreativeEdit/check_creative_development_type.php',
			type: 'post',
			datatype: 'html',
			data: {
				creative_id: c_Id
			},
			success: function (data) {
				sessionStorage.setItem('CreativeDevelopmentType', data);
			}
		});

		check_creative_development_type = sessionStorage.getItem('CreativeDevelopmentType');

		if (check_creative_development_type == "Собственная разработка" && PreviewImage == 'true' && BaseImage == 'false') {
			$('#SendToApproval').prop('disabled', false);
		} else if (check_creative_development_type == "Компиляция" && PreviewImage == 'true' && BaseImage == 'true') {
			$('#SendToApproval').prop('disabled', false);
		} else {
			$('#SendToApproval').prop('disabled', true); // Отключаем кнопку
		}
	});


	// Кнопка отправки креатива на утверждение
	$('#SendToApproval').on("click", function () {
		$.ajax({
			url: '/Modules/CreativeEdit/creative_approval.php',
			type: 'post',
			data: {
				creative_id: c_Id
			},
			success: function (data) {
				console.log("Отправили на утверждение! " + data);
				location.href = '/index.php?module=CreativeList';
			}
		});
	});


	// ХЭШИ

	// $('#HashWork').on('shown.bs.modal', function (event) {
	// 	var button = $(event.relatedTarget);
	// 	console.log('Загрузились');
	// });

	// $('#HashWorkBtn').on("click", function () {
	// 	// console.log('Нажали' + c_Id);
	// 	$.ajax({
	// 		url: '/Modules/CreativeEdit/get_used_hash.php',
	// 		type: 'post',
	// 		datatype: 'html',
	// 		data: {
	// 			creative_id: c_Id
	// 		},
	// 		success: function (data) {
	// 			var hash_arr = jQuery.parseJSON(data);
	// 			if (hash_arr.length > 0) {
	// 				hash_arr.forEach(function (item) {

	// 					// console.log(sessionStorage.getItem(item));

	// 				});
	// 			}
	// 		}
	// 	});
	// });
});