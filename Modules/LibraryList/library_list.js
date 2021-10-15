$(document).ready(function () {
	"use strict";
	// Настройка DataTable для списка задач
	$('#DT_DesignList').DataTable({
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
			// "url": "/datafiles/dataTables.russian.json"
		}
	});
});