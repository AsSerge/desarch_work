(function () {
	$(document).ready(function () {
		"use strict";

		var task_id; // ID редавтируемой задачи

		// Получаем ID задачи
		$('.AddNewCreative').on('click', function () {
			task_id = $(this).attr('data-task');
		});

		$('#AddCreative').on('shown.bs.modal', function (event) {
			// Действия при загрузке формы
		});

		// Сохраняем новый креатив в задачу
		$(document).on("click", "#AddCreativeBtn", function (event) {
			event.preventDefault();
			var user_id = $("#user_id").val();
			var creative_name = $('#creative_name').val();

			$.ajax({
				url: '/Modules/TaskListDesigner/task_add_creative.php',
				type: 'post',
				dataType: 'html',
				data: {
					add_creative: 'true',
					creative_name: creative_name,
					user_id: user_id,
					task_id: task_id
				},
				success: function (data) {
					console.log(data);
					$('#AddCreativeFrm')[0].reset(); // Сбрасываем поля формы
					$('#liveToast').toast('show');
					$('#MyCountSrc' + task_id).html(data);
					location.reload(); // Перезагрузка страницы
				}
			});

		});

	});
})();