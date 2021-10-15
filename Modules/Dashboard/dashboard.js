$(document).ready(function () {
	"use strict";
	// Заполняем таблицу креативов
	GetHashForCreative();

	GetLog();
	setInterval(GetLog, 5000);

	// Фильтрация креативов по хешу
	function GetHashForCreative(hash_name) {
		$.ajax({
			url: '/Modules/Dashboard/get_creatives_hash.php',
			type: 'post',
			datatype: 'html',
			data: {
				hash_name: hash_name
			},
			success: function (data) {
				var LongLine = "<table class='table table-sm'>";
				var creative_arr = jQuery.parseJSON(data);
				var creative_array = Object.entries(creative_arr); // Преобразуем Объект в массив для перебора
				if (creative_array.length >= 1) {
					creative_array.forEach(function (item) {
						var cr_id = item[1]['creative_id'];
						var cr_name = item[1]['creative_name'];
						var cr_hash_list = item[1]['creative_hash_list'];
						var cr_hash_string = ''
						if (cr_hash_list != null && cr_hash_list !== "") {
							cr_hash_string = GetHashArr(cr_hash_list);
						}
						LongLine += "<tr>";
						LongLine += "<td><a href = '/index.php?module=CreativeListView&creative_id=" + cr_id + "'>" + cr_name + "</a></td>";
						LongLine += "<td>" + cr_hash_string + "</td>";
						LongLine += "</tr>";

						// Функция формирования списка хэшей
						function GetHashArr(hash_str) {
							var arr_line = "";
							var arr = hash_str.split("|");
							arr.forEach(function (item) {
								arr_line += "<span class='OneTag'>" + item + "</span>";
							});
							return arr_line;
						};
					});
					LongLine += "</table>";
					$('.TagsList').html(LongLine); //Заполняем тегами варианты
				}
				// Заполнение таблицы
				$('#CreativesList').html(LongLine);


				// Заполнение таблицы при клике на хэш
				$('.OneTag').on("click", function () {
					var hash_text = $(this).text();
					$('#CreativeFilter').html('<i class="fas fa-swatchbook"></i> Принятые креативы [' + hash_text + ']');
					GetHashForCreative(hash_text);
				});

				// Заполнение таблицы при клике очистку фильтра
				$('#all_hash').on("click", function () {
					$('#CreativeFilter').html('<i class="fas fa-swatchbook"></i> Принятые креативы ');
					GetHashForCreative();
				});
			}
		});
	}

	function GetLog() {
		$.ajax({
			url: '/Modules/Dashboard/pulse.php',
			success: function (data) {

				var LongLine = "<table class='table table-sm table-borderless console__body'>";
				var log_arr = jQuery.parseJSON(data);
				var log_array = Object.entries(log_arr); // Преобразуем Объект в массив для перебора
				if (log_array.length >= 1) {
					log_array.forEach(function (item) {
						LongLine += "<tr>";
						LongLine += "<td>" + item[1]['log_datetime'] + "</td>";
						LongLine += "<td>" + item[1]['user_name'] + " " + item[1]['user_surname'] + "</td>";
						LongLine += "<td>" + item[1]['creative_name'] + "</td>";
						LongLine += "<td>" + item[1]['log_content'] + "</td>";
						LongLine += "</tr>";
					});
				}
				LongLine += "</table>";
				$('#Pulse').html(LongLine);
			}
		});
	}

});