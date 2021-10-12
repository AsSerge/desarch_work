(function () {

	$(document).ready(function () {
		"use strict";
		// Устанвливаем дефолтное заначение OFF
		var VoteVal = "";
		// Скрываем окно выбора причины отклонения дизайна
		$('#rejectionReasonBlock').hide();
		// Формируем кнопку отправки голоса
		SetButtonLable(VoteVal);
		// Получаем значение кнопки для голосования

		// Кнопки принятия решения
		$('#BtnOff').on("click", function () {
			VoteVal = "off";
			$('#BtnOff').removeClass();
			$('#BtnOn').removeClass();
			$('#BtnOff').addClass('btn btn-danger');
			$('#BtnOn').addClass('btn btn-outline-success');
			CheckAlertStatus(VoteVal);
			console.log(VoteVal);
		});
		$('#BtnOn').on("click", function () {
			VoteVal = "on";
			$('#BtnOff').removeClass();
			$('#BtnOn').removeClass();
			$('#BtnOff').addClass('btn btn-outline-danger');
			$('#BtnOn').addClass('btn btn-success');
			CheckAlertStatus(VoteVal);
			console.log(VoteVal);
		});

		// Селектор выбора причины отклонения
		$('#rejectionReason').on("change", function () {
			var selButton = $('#rejectionReason').val();
			if (selButton != "") {
				$('#SendVote').prop("disabled", false);
			}
		});

		// Кнопка записи информации
		$('#SendVote').on("click", function () {
			var v_description = $('#v_description').val();
			var rejectionReason = $('#rejectionReason').val();
			$.ajax({
				url: '/Modules/RatingEdit/rating_update.php',
				datatype: 'html',
				type: 'post',
				data: {
					user_id: user_id,
					creative_id: creative_id,
					creative_grade_pos: VoteVal,
					creative_comment_content: v_description,
					rejectionReason: rejectionReason
				},
				success: function (data) {
					// console.log(data);
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
			if (VoteVal == "off") {
				$('#FTMyRadio').removeClass();
				$('#FTMyRadio').addClass('alert alert-danger');
				$('#FTMyRadio').html('Вы не приняли дизайн. Покупка запрещена. Необходимо выбрать причину отказа из выпадающего списка. Также, вы можете оставить комметнарий <i class="far fa-comment-dots"></i> для дизайнера и продолжить голосование, нажав кнопку "Доработать дизайн" <i class="far fa-thumbs-down"></i>');
				$('#rejectionReasonBlock').show();
				SetButtonLable(VoteVal);
			} else if (VoteVal == "on") {
				$('#FTMyRadio').removeClass();
				$('#FTMyRadio').addClass('alert alert-success');
				$('#FTMyRadio').html('Дизайн принят - покупка разрешена. Вы можете оставить комметнарий <i class="far fa-comment-dots"></i> для дизайнера и продолжить голосование, нажав кнопку "Покупка разрешена"<i class="far fa-thumbs-down"></i>');
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
			} else if (t == "off") {
				$('#SendVote').show();
				$('#SendVote').html('Доработать дизайн');
				$('#SendVote').prop("disabled", true);
			} else if (t == "on") {
				$('#SendVote').show();
				$('#SendVote').html('Покупка разрешена');
				$('#SendVote').prop("disabled", false);
			}
		}
	});

})()