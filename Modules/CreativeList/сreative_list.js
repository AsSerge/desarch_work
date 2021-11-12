$(document).ready(function () {
	"use strict";

	// ОТКЛЮЧЕНО!!! Кнопка "Взять в работу" ставит признак "В работе" для креатива и создает новую папку с ID креатива в папке Creatives

	// $(document).on("click", ".TakeToWork", function () {
	// 	var CreativeID = $(this).data('creative');
	// 	$(this).attr("disabled", true);
	// 	$.ajax({
	// 		url: '/Modules/CreativeList/сreative_update.php',
	// 		type: 'post',
	// 		dataType: 'html',
	// 		data: {
	// 			creative_id: CreativeID
	// 		},
	// 		success: function () {
	// 			var ToasBodyText = "Задача взята в работу";
	// 			$('#liveToast').children(".toast-body").html("<p><i class='far fa-save'> " + ToasBodyText + "</p>");
	// 			$('#liveToast').toast('show');
	// 			location.reload(); // Перезагрузка страницы
	// 		}
	// 	});
	// });

	$(document).on("click", ".AddSourceFiles", function(){
		var CreativeID = $(this).data('source');
		$("#Cr_id").val(CreativeID);
		console.log(CreativeID);
	});


	$(document).on("click", "#BtnSendFilesToLibrary", function(){
		var CreativeID = $("#Cr_id").val();
	
		$('#DesignSendInfo').ajaxSubmit({
			url: '/Modules/CreativeList/add_source_file.php',
			type: 'post',
			data: {
				creative_id: CreativeID
			},
			success: function (data) {
				$('#DesignSendInfo')[0].reset(); // Сбрасываем поля формы
				location.reload(); // Перезагрузка страницы
			}
		});
	});

	// НАСТРОЙКА кнопки загрузки файлов в библиотеку
	$('#customFile1').on('change', function (e) {
		var files = [];
		for (var i = 0; i < $(this)[0].files.length; i++) {
			files.push($(this)[0].files[i].name);
		}
		$(this).next('.custom-file-label').html(files.join(', '));
	});
	
	// ПРИНУДИТЕЛЬНАЯ очистка полей формы
	$('#BtnFormClear').on("click", function () {
		$('#DesignSendInfo')[0].reset(); // Сбрасываем поля формы
		location.reload(); // Перезагрузка страницы
	});

});