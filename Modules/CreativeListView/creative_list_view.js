$(document).ready(function () {
	"use strict";
	// Настройка DataTable для списка задач
	$('#DT_CreativeList').DataTable({
		"paging": true,
		"ordering": true,
		"info": false,
		"stateSave": false,
		"columnDefs": [{
			"targets": [0],
			"visible": false,
			"searchable": false
		}, {
			"targets": [1],
			"visible": true,
			"searchable": false,
			"orderable": false
		}],
		"order": [
			[0, "ASC"]
		],
		"lengthMenu": [
			[10, 25, 50, -1],
			[10, 25, 50, "Все"]
		],
		"language": {
			"lengthMenu": "Показывать _MENU_ записей на странице",
			"zeroRecords": "Извините - ничего не найдено",
			"info": "Показано _PAGE_ страниц из _PAGES_",
			"infoEmpty": "Нет подходящих записей",
			"infoFiltered": "(Отфильтровано из _MAX_ записей)",
			"sSearch": "Искать: ",
			"oPaginate": {
				"sFirst": "Первая",
				"sLast": "Последняя",
				"sNext": "Следующая",
				"sPrevious": "Предыдущая"
			}
		}
	});

	$('.savePDF').on("click", function () {
		var creative_id = $(this).attr("docID");
		console.log(creative_id);
	});
	// Получение списка исходников для загрузки
	$('.CreativeSource').on("click", function(e){
		e.preventDefault();
		var creative_id = $(this).attr("docID");
		$.ajax({
			url: '/Modules/CreativeListView/get_source_list.php',
			type: 'post',
			datatype: 'html',
			data: {
				creative_id: creative_id
			},
			success: function (data) {
				var LongLine = "";
				var source_arr = jQuery.parseJSON(data);
				source_arr.forEach( function (entry) {
					LongLine += "<div class='SourceItem'>" + GetFileIcon(entry) + "<a href ='/Creatives_SRC/" + creative_id + "/" + entry + "' download>" + entry + "</a></div>";
				});
				$('#SourceList').html(LongLine);
			}
		});
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