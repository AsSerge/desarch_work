(function () {

	$(document).ready(function () {
		"use strict";
		// Устанвливаем дефолтное заначение OFF
		var VoteVal = "";
		// Формируем кнопку отправки голоса
		SetButtonLable(VoteVal);
		// Получаем значение кнопки для голосования

		// Кнопки принятия решения
		$('#BtnBuy').on("click", function () {
			VoteVal = "buy";
			$('#BtnBuy').removeClass();
			$('#BtnOn').removeClass();
			$('#BtnBuy').addClass('btn btn-success');
			$('#BtnOn').addClass('btn btn-outline-primary');
			CheckAlertStatus(VoteVal);
			// console.log(VoteVal);
		});
		$('#BtnOn').on("click", function () {
			VoteVal = "on";
			$('#BtnBuy').removeClass();
			$('#BtnOn').removeClass();
			$('#BtnBuy').addClass('btn btn-outline-success');
			$('#BtnOn').addClass('btn btn-primary');
			CheckAlertStatus(VoteVal);
			// console.log(VoteVal);
		});

		// Кнопка записи информации
		$('#SendVote').on("click", function () {
			var v_description = $('#v_description').val();
			$.ajax({
				url: '/Modules/RatingEdit/rating_update.php',
				datatype: 'html',
				type: 'post',
				data: {
					user_id: user_id,
					creative_id: creative_id,
					creative_grade_pos: VoteVal,
					creative_comment_content: v_description
				},
				success: function (data) {
					console.log(data);
					// Идем домой
					$(location).attr('href', '/');
				}
			});
		});

		// Кнопка, подменяющая клик на другую кнопку
		$('.SetComment').on('click', function () {
			$('#SetComment').click();
			return false;
		});

		// Функция настройеи панели Alert для кнопок принятия дизайна
		function CheckAlertStatus(VoteVal) {
			if (VoteVal == "buy") {
				$('#FTMyRadio').removeClass();
				$('#FTMyRadio').addClass('alert alert-success');
				$('#FTMyRadio').html('Разрешена покупка стоковых дизайнов для данного креатива. Вы можете оставить комметнарий <i class="far fa-comment-dots"></i> для дизайнера и продолжить голосование, нажав кнопку "Разрешить покупку и продолжить"</i>');
				$('#rejectionReasonBlock').show();
				SetButtonLable(VoteVal);
			} else if (VoteVal == "on") {
				$('#FTMyRadio').removeClass();
				$('#FTMyRadio').addClass('alert alert-info');
				$('#FTMyRadio').html('Дизайн принят БЕЗ закупкки на стоке т.к. покупка не целесообразна. Вы можете оставить комметнарий <i class="far fa-comment-dots"></i> для дизайнера и продолжить голосование, нажав кнопку "Принять без покупки и продолжить"</i>');
				$('#rejectionReasonBlock').hide();
				SetButtonLable(VoteVal);
			}
		}


		// Функция формировния кнопки голосования
		function SetButtonLable(t) {
			if (t == "") {
				$('#SendVote').hide();
				$('#SendVote').prop("disabled", true);
				$('#SendVote').html('ГОЛОСОВАТЬ');
			} else if (t == "buy") {
				$('#SendVote').show();
				$('#SendVote').html('Разрешить покупку и продолжить');
				$('#SendVote').prop("disabled", false);
			} else if (t == "on") {
				$('#SendVote').show();
				$('#SendVote').html('Принять без покупки и продолжить');
				$('#SendVote').prop("disabled", false);
			}
		}
	});

})()