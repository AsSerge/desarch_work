$(document).ready(function () {
	"use strict";

	$('#BtnSendFilesToLibrary').attr("disabled", true); // Запрещена отправка по умолчанию

	// Настройка кнопки загрузки файлов в библиотеку
	$('#customFile1').on('change', function (e) {
		var files = [];
		for (var i = 0; i < $(this)[0].files.length; i++) {
			files.push($(this)[0].files[i].name);
		}
		$(this).next('.custom-file-label').html(files.join(', '));
	});


	// Проверка заполненности всех полей формы
	function checkAllFields() {
		var k = 0;
		$('.myRQ').each(function () {
			if ($(this).val() != '') {
				k++
			}
		});
		return k
	}

	// Включение / отключение кнопки отправки #BtnSendFilesToLibrary
	$('.myRQ').on("change", function () {
		var h = checkAllFields();
		console.log(h);
		if (h < 4) {
			$('#BtnSendFilesToLibrary').attr("disabled", true);
		} else {
			$('#BtnSendFilesToLibrary').attr("disabled", false);
		}
	});

	// ПРИНУДИТЕЛЬНАЯ очистка полей формы
	$('#BtnFormClear').on("click", function () {
		$('#DesignSendInfo')[0].reset(); // Сбрасываем поля формы
		location.reload(); // Перезагрузка страницы
	});

	// ОТПРАВКА ИНФОРМАЦИИ
	$('#BtnSendFilesToLibrary').on("click", function () {
		$('#DesignSendInfo').ajaxSubmit({
			url: '/Modules/LibraryEdit/library_updateinfo.php',
			type: 'post',
			data: {
				creative_id: c_Id
			},
			success: function (data) {
				console.log(data);
				$('#DesignSendInfo')[0].reset(); // Сбрасываем поля формы
				location.reload(); // Перезагрузка страницы
			}
		});
	});



	// Сокрытие поля загрузки файлов preview и base (настройка кнопок)
	$('#FilesDN').on('click', function () {
		$('#PreviewFile').click();
		return false;
	});

	// Загрузка Preview файла для дизайна
	$('#PreviewFile').on("change", function () {
		$('#PreviewFileLoad').ajaxSubmit({
			url: '/Modules/LibraryEdit/PreviewFileLoad.php',
			type: 'post',
			// target: '#resultPreview',
			data: {
				creative_id: c_Id
			},
			success: function () {
				$('#PreviewFileLoad')[0].reset();
			}
		});
	});
});