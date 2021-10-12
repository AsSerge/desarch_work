$(document).ready(function () {
	"use strict";
	// Временно отключаем кнопку сохранения
	$('#SaveTask').attr("disabled", "disabled");


	// Настройка DataTable для списка задач
	// Скрыт столбец даты последнего изменения, но таблица сортируется по нему

	$('#DT_TaskList').DataTable({
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



	// Настройки DatePicker
	$('.datepicker').datepicker({
			weekStart: 1,
			daysOfWeekHighlighted: "6,0",
			autoclose: true,
			todayHighlight: true,
			language: 'ru'
		},
		$.fn.datepicker.dates['ru'] = {
			days: ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"],
			daysShort: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
			daysMin: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
			months: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
			monthsShort: ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"],
			today: "Today",
			clear: "Clear",
			format: "mm/dd/yyyy",
			titleFormat: "MM yyyy",
			weekStart: 1
		}
	);

	// Начало задачи
	$('#datepicker_start').datepicker("setDate", new Date());
	// Крайний срок
	$('#datepicker_end').datepicker("setDate", new Date());


	// Проверка заполненности полей формы добавления задачи: поля НОМЕР и НАЗВАНИЕ должны быть заполнены!
	var tnumber = "";
	var tname = "";

	$("#task_number").on("keyup", function () {
		tnumber = $("#task_number").val();
		if (tnumber != "" && tname != "") {
			$('#SaveTask').removeAttr('disabled');
		} else {
			$('#SaveTask').attr("disabled", "disabled");
		}
	});

	$("#task_name").on("keyup", function () {
		tname = $("#task_name").val();
		if (tname != "" && tnumber != "") {
			$('#SaveTask').removeAttr('disabled');
		} else {
			$('#SaveTask').attr("disabled", "disabled");
		}
	});


	// Первоначальное сохранение задачи	
	$(document).on("click", "#SaveTask", function (event) {
		event.preventDefault();
		var add_task = true;
		var user_id = $("#user_id").val();
		var customer_id = $("#customer_id").val();
		var task_number = $("#task_number").val();
		var task_name = $("#task_name").val();
		var datepicker_start = $("#datepicker_start").val();
		var datepicker_end = $("#datepicker_end").val();
		var task_description = $("#task_description").val();

		// console.log("Сохраняем задачу от заказчика" + datetask_description);

		$.ajax({
			url: '/Modules/TaskList/task_update.php',
			type: 'POST',
			dataType: 'html',
			data: {
				add_task: add_task,
				user_id: user_id,
				customer_id: customer_id,
				task_number: task_number,
				task_name: task_name,
				datepicker_start: datepicker_start,
				datepicker_end: datepicker_end,
				task_description: task_description
			},
			success: function (data) {
				console.log(data);
				location.reload(); // Перезагрузка страницы
			}
		});
	});
});