"use strict";
$(document).ready(function () {
	// Подгрузка в форму данных по каждой отдельной записи. Вызывается при загрузке модального окна
	$('#AddCustomer').on('shown.bs.modal', function (event) {
		event.preventDefault();
		var button = $(event.relatedTarget);
		var action_type = button.data('whatever') // Кнопка, запускающая модальное окно
		var customer_to_edit = button.data('customer-id');

		$.ajax({
			url: '/Modules/СustomerList/customer_getinfo.php',
			type: 'POST',
			dataType: 'html',
			data: {
				customer_id: customer_to_edit
			},
			success: function (data) {
				var res = $.parseJSON(data);
				$("#customer_type").val(res.customer_type);
				$("#edit_customer").val(res.customer_id);
				$("#customer_name").val(res.customer_name);
				$("#customer_description").val(res.customer_description);
			}
		});
	});

	// Отправка формы из модального окна EditCustome по кнопке SaveCustomer (сохранение заказчика)

	$(document).on("click", "#SaveCustomer", function (event) {
		event.preventDefault();
		var customer_id = $("#edit_customer").val();
		var add_customer = $("#add_customer").val();
		var customer_name = $("#customer_name").val();
		var customer_type = $("#customer_type").val();
		var customer_description = $("#customer_description").val();

		$.ajax({
			url: '/Modules/СustomerList/customer_update.php',
			type: 'POST',
			dataType: 'html',
			data: {
				customer_id: customer_id,
				add_customer: add_customer,
				customer_name: customer_name,
				customer_type: customer_type,
				customer_description: customer_description
			},
			success: function (data) {
				console.log(data);
				location.reload(); // Перезагрузка страницы
			}
		});
	});

	// Удаление записи заказчика в таблице
	$(document).on("click", ".RemoveCustomerBtn", function (e) {
		var customerToDel = $(this).attr('data-customer-id');
		RemoveCustomer(customerToDel);
		location.reload(); // Перезагрузка страницы
	});
});

// Удаление заказчика
function RemoveCustomer(customerToDel) {
	$.ajax({
		url: '/Modules/СustomerList/removeCustomer.php',
		type: 'POST',
		data: {
			customer_id: customerToDel
		},
		dataType: 'html',
		success: function (data) {
			// console.log(data);
		}
	});
}