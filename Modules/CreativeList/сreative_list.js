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

	$(document).on("click", ".AddSourceFiles", function(e){
		e.preventDefault();
		var CreativeID = $(this).data('source');
		$("#Cr_id").val(CreativeID);

		$.ajax({
			url: '/Modules/CreativeList/get_source_list.php',
			type: 'post',
			datatype: 'html',
			data: {
				creative_id: CreativeID
			},
			success: function (data) {
				var LongLine = "";
				var source_arr = jQuery.parseJSON(data);
				source_arr.forEach( function (entry) {
					LongLine += "<div class='SourceItem'>" + GetFileIcon(entry) + "<a href ='/Creatives_SRC/" + CreativeID + "/" + entry + "' download>" + entry + "</a></div>";
				});
				$('#SourceList').html(LongLine);
			}
		});		
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

// Функция вывода пиктограммы типа файла в зависимости от расширения
function GetFileIcon(fileName){
	var fileExt = fileName.split('.').pop(); //Отделяем расширение
	switch(fileExt){
		case 'ai':
			fileImg = 'Ai.svg';
			break;
		case 'eps':
			fileImg = 'Eps.svg';
			break;
		case 'gif':
			fileImg = 'Gif.svg';
			break;
		case 'jpg':
			fileImg = 'Jpg.svg';
			break;
		case 'jpeg':
			fileImg = 'Jpg.svg';
			break;
		case 'png':
			fileImg = 'Png.svg';
			break;
		case 'svg':
			fileImg = 'Svg.svg';
			break;
		case 'zip':
			fileImg = 'Zip.svg';
			break;
		case 'cdr':
			fileImg = 'Cdr.svg';
			break;
		default:
			fileImgg = 'Fil.svg';
	}	
	return '<span><img src="/images/icons/'+ fileImg +'" width="20px"></span>' ;
}