$(document).ready(function () {
	"use strict";

	$('#CreativesList').html(GetHashForCreative());
	// $('#CreativesList').html(GetHashForCreative("Serge Tsvetkov"));


	$('.OneTag').on("click", function () {
		var hash_text = $(this).text();
		console.log('Нажал ' + hash_text);
	});


	function GetHashForCreative(creative_id) {

		$.ajax({
			url: '/Modules/DesignerList/get_creatives_hash.php',
			type: 'post',
			datatype: 'html',
			data: {
				creative_id: creative_id
			},
			success: function (data) {


				console.log(data);

				$('#CreativesList').html(data);
				// var LongLine = "";
				// var hash_arr = jQuery.parseJSON(data);
				// var hash_array = Object.entries(hash_arr); // Преобразуем Объект в массив для перебора
				// if (hash_array.length > 1) {
				// 	hash_array.forEach(function (item) {
				// 		LongLine += "<div class='OneTag OneTagSelected'>" + item[1] + "</div>";
				// 	});
				// 	$('.TagsList').html(LongLine); //Заполняем тегами варианты
				// }

			}

		});
	}

});