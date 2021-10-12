(function () {
	$(document).ready(function () {
		"use strict";
		// Первоначальная загрузка контента		
		$('#HelpDeskContent').load('/Modules/HelpDesk/hd_main.php');

		$('.OneDot').on("click", function (event) {
			event.preventDefault();
			var source_link = $(this).data();
			$('#HelpDeskContent').html();
			$('#HelpDeskContent').load('/Modules/HelpDesk/' + source_link.source);
		});
	});
})();