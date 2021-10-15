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
		}],
		"order": [
			[0, "ASC"]
		],
		"lengthMenu": [
			[10, 25, 50, -1],
			[10, 25, 50, "Все"]
		],
		"language": {
			"url": "/datafiles/dataTables.russian.json"
		}
	});
});