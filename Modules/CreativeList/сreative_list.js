$(document).ready(function () {
	"use strict";

	// Кнопка "Взять в работу" ставит признак "В работе" для креатива и создает новую папку с ID креатива в папке Creatives

	$(document).on("click", ".TakeToWork", function () {
		var CreativeID = $(this).data('creative');
		$(this).attr("disabled", true);
		$.ajax({
			url: '/Modules/CreativeList/сreative_update.php',
			type: 'post',
			dataType: 'html',
			data: {
				creative_id: CreativeID
			},
			success: function () {
				var ToasBodyText = "Задача взята в работу";
				$('#liveToast').children(".toast-body").html("<p><i class='far fa-save'> " + ToasBodyText + "</p>");
				$('#liveToast').toast('show');
				location.reload(); // Перезагрузка страницы
			}
		});
	});


});