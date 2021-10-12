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
			$('#BtnCheck').removeClass();
			$('#BtnCheck').addClass('btn btn-outline-info');
			$('#BtnOff').addClass('btn btn-warning');
			$('#BtnOn').addClass('btn btn-outline-success');
			CheckAlertStatus(VoteVal);
			// console.log(VoteVal);
		});
		$('#BtnOn').on("click", function () {
			VoteVal = "on";
			$('#BtnOff').removeClass();
			$('#BtnOn').removeClass();
			$('#BtnCheck').removeClass();
			$('#BtnCheck').addClass('btn btn-outline-info');
			$('#BtnOff').addClass('btn btn-outline-warning');
			$('#BtnOn').addClass('btn btn-success');
			CheckAlertStatus(VoteVal);
			// console.log(VoteVal);
		});
		$('#BtnCheck').on("click", function () {
			VoteVal = "check";
			$('#BtnOff').removeClass();
			$('#BtnOn').removeClass();
			$('#BtnCheck').removeClass();
			$('#BtnOff').addClass('btn btn-outline-warning');
			$('#BtnOn').addClass('btn btn-outline-success');
			$('#BtnCheck').addClass('btn btn-info');
			CheckAlertStatus(VoteVal);
			// console.log(VoteVal);
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
				url: '/Modules/CreativeApprovalEdit/creative_approval_update.php',
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
					$(location).attr('href', '/index.php?module=CreativeApprovalList');
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
				$('#FTMyRadio').html('Вы не приняли дизайн! Необходимо выбрать причину отправки на доработку из выпадающего списка. Также, вы можете оставить комметнарий <i class="far fa-comment-dots"></i> для дизайнера и продолжить голосование, нажав кнопку "Отправить на доработку" этот креатив <i class="far fa-thumbs-down"></i>');
				$('#rejectionReasonBlock').show();
				SetButtonLable(VoteVal);
			} else if (VoteVal == "on") {
				$('#FTMyRadio').removeClass();
				$('#FTMyRadio').addClass('alert alert-success');
				$('#FTMyRadio').html('Ура! Дизайн принят! Вы можете оставить комметнарий <i class="far fa-comment-dots"></i> для дизайнера и продолжить согласование, нажав кнопку "Принять дизайн" этот дизайн <i class="far fa-thumbs-down"></i>');
				$('#rejectionReasonBlock').hide();
				SetButtonLable(VoteVal);
			} else if (VoteVal == "check") {
				$('#FTMyRadio').removeClass();
				$('#FTMyRadio').addClass('alert alert-info');
				$('#FTMyRadio').html('Вы отправляете креатив на утверждение комиссией! Вы можете оставить комметнарий <i class="far fa-comment-dots"></i> для дизайнера и отправить креатив на комиссию, нажав кнопку "Отправить на комиссию" этот креатив <i class="far fa-thumbs-down"></i>');
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
				$('#SendVote').html('Отправить на доработку');
				$('#SendVote').prop("disabled", true);
			} else if (t == "on") {
				$('#SendVote').show();
				$('#SendVote').html('Принять дизайн');
				$('#SendVote').prop("disabled", false);
			} else if (t == "check") {
				$('#SendVote').show();
				$('#SendVote').html('Отправить на комиссию');
				$('#SendVote').prop("disabled", false);
			}

		}
	});

})()