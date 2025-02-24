$(function() {

	
	//Общее
	$('.fancybox').fancybox();

	$("#client-phone, [name='ClientsSearch\\[phone\\]'], [name='Primerka\\[client_phone\\]']").mask("+7 (999) 999-99-99");

	$.datetimepicker.setLocale('ru');
	$('#primerka-date_field').datetimepicker({
		format: 'd.m.Y H:i',
		lang: 'ru',
		allowTimes: ['11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00']
	});

	$('.start-form input').change(function() {
		$(this).closest('form').submit();
	});
	
	var minicart = $('#minicart'),
		total_amount_holder = minicart.find('#total_amount'),
		total_price_holder = minicart.find('#total_price');
	$('a.cart_link').click(function() {
		minicart.toggle();
		return false;
	});

	var primerka_client_id = $('#primerka-client_id');
	if (primerka_client_id.length) {
		$('body').on('click', '#save-phone', function() {
			var select2 = $('#primerka-client_id').data('select2'),
				term = select2.dropdown.$search.val();
			select2.close();
			$('#primerka-client_phone').val(term.substr(1));
			$('#primerka-client_fio').focus();
		});

		$('body').on('click', '#save-name', function() {
			var select2 = $('#primerka-client_id').data('select2'),
				term = select2.dropdown.$search.val();
			select2.close();
			$('#primerka-client_fio').val(term);
			$('#primerka-client_phone').focus();
		});
	}

	// Добавление и редактирование товаров
	var product_create_form = $('.product-form form');
	if (product_create_form.length) {

		var amount_table = $('#amount_table');
			//amount_table_default = amount_table.html();

		var amount_table_default = '<tbody><tr><th>Наличие</th></tr><tr><td>Зал</td></tr><tr><td>Склад</td></tr><tr><td>Ждём</td></tr></tbody>';

		$('#product-sizes').change(function() {
			//amount_table.html(amount_table_default);
			var all_sizes = $('input[name="Product\\[sizes\\]\\[\\]"]')
				checked_sizes = all_sizes.filter(':checked');
			$.each(all_sizes, function() {
				var item = $(this),
					size_name = $.trim(item.parent().text()),
					existed_th = amount_table.find('th:contains("'+size_name+'")');
				if (item.prop('checked')) {
					if (!existed_th.length) {
						var index = checked_sizes.index(item);
						amount_table.find('tr:first').find('th:eq('+index+')').before('<th>' + size_name + '</th>');
						amount_table.find('tr:gt(0)').find('td:eq('+index+')').before('<td><input type="number" class="form-control" value="0" name="Product[amount][' + item.val() + '][]"></td>');
					} else {
						var index = existed_th.index() + 1;
						amount_table.find('th:nth-child('+index+'), td:nth-child('+index+')').show();
					}
				} else {
					if (existed_th.length) {
						var index = existed_th.index() + 1;
						amount_table.find('th:nth-child('+index+'), td:nth-child('+index+')').hide();
					}
				}
            });
		});

		product_create_form.find('select').change(function() {
			var item = $(this),
				new_input = $('#' + item.attr('id').replace('_id', '_new'));
			if (item.val()) {
				new_input.attr('disabled', 'disabled');
			} else {
				new_input.removeAttr('disabled');
			}
		});
	}


	// Список товаров
	var product_table = $('.product-index table.table');
	if (product_table.length) {

		var amount_inputs = product_table.find('tr.order_tr input:enabled');
		amount_inputs.change(function() {
			var item = $(this),
				tr = item.closest('tr'),
				inputs = tr.find('input'),
				amount = 0,
				amount_holder = tr.find('th.total_item_amount'),
				total_amount = 0,
				total_price = 0;
			$.each(inputs, function() {
				amount += parseInt($(this).val());
			});
			amount_holder.text(amount);

			amount_inputs.each(function() {
				var amount_input_item = $(this),
					amount_input_val = parseInt(amount_input_item.val());
				total_amount += amount_input_val;
				total_price += parseInt(amount_input_item.data('price')) * amount_input_val;
			});
			total_amount_holder.text(total_amount);
			total_price_holder.text(total_price.toLocaleString());
		});

		$('.product-index #filter_block select').change(function() {
			$(this).closest('form').submit();
		});
	}

	// Корзина
	var minicart = $('#minicart'),
		products_form = $('#products_form');

	var sh_print = $('input[name="sh_print\\[\\]"]');
	$('#print_codes_btn').click(function() {
		var codes = [];
		$.each(sh_print, function() {
			var item = $(this);
			if (item.is(':checked')) {
				var amount = item.closest('tr').prev().find('.amount_inp').eq(item.parent().index() - 1).find('input').val();
				if (amount) {
					codes.push(item.data('barcode') + '(' + amount + ')');
				} else {
					codes.push(item.data('barcode'));
				}
			}
		});
		window.open('/products/print-codes/?codes=' + codes.join('-'), 'Печать штрих-кодов');
		return false;
	});

	if (minicart.length) {
		//Создание заказа или перемещения
		minicart.find('a.order_create, a.whmove_create, a.hwmove_create').click(function() {
			var item = $(this),
				await_date = minicart.find('input[name="await_date"]').val(),
			    order_items = [];
			$.each(products_form.find('input:enabled'), function() {
				var input = $(this);
				if (parseInt(input.val()) > 0) {
					order_items.push({
						'product_id': parseInt(input.data('product')),
						'size_id': parseInt(input.data('size')),
						'price': parseFloat(input.data('price')) * parseInt(input.val()),
						'amount': parseInt(input.val())
					});
				}
			});
			$.post(item.attr('href'), {'order_items': order_items, 'await_date': await_date}, function(data) {
				location.reload();
			});
			return false;
		});
	}

	//Заказы
	$('form.order_update_form select, form.whmove_update_form select, form.hwmoves_update_form select').change(function() {
		var item = $(this),
			arrived = item.closest('tr').find('input');
		if (item.val() == 2) {
			arrived.removeAttr('disabled');
		} else {
			arrived.attr('disabled', 'disabled');
		}
	});

	//Чек
	var receipt_create = $('.receipt-create');
	if (receipt_create.length) {
		$('.receipt-create').on('change', 'input[type="checkbox"]', function(event) {
			$(this).closest('form').submit();
		});

		$('#receipt-payment_type').change(function() {
			if ($(this).val() == 2) {
				$('.cash_total_block').show();
			} else {
				$('.cash_total_block').hide();
			}
		});

		$('a.add_item_by_barcode').click(function() {
			var item = $(this);
			location.href = item.attr('href') + item.prev().val();
			return false;
		});

		$('a.change_manager').click(function() {
			var item = $(this);
			location.href = item.attr('href') + '&manager_id=' + item.prev().val();
			return false;
		});

		$('.receipt-create input[name="created_at"]').change(function() {
			location.href = '/receipts/change-time?id=' + $(this).data('id') + '&time=' + $(this).val();
		});
	}

	//Финансовые операции
	var operation_repeated = $('#operation-repeated'),
		repeating_settings = $('.repeating_settings');
	if (operation_repeated.is(':checked')) {
		repeating_settings.show();
	}
	operation_repeated.change(function() {
		if ($(this).is(':checked')) {
			repeating_settings.show();
		} else {
			repeating_settings.hide();
		}
	});

	$('.fastCheck a').click(function() {
		var item = $(this),
			chkbxs = item.closest('.checkbox_list').find('input:checkbox'),
			action = item.attr('rel'),
			chk_arr = [];
		chkbxs.prop('checked', false);
		switch (action) {
			case 'chk_all':
				chkbxs.prop('checked', true);
				break
			case 'unchk_all':
				chkbxs.prop('checked', false);
				break
			case 'winter':
				chk_arr = [1,2,12];
				break
			case 'spring':
				chk_arr = [3,4,5];
				break
			case 'summer':
				chk_arr = [6,7,8];
				break
			case 'autumn':
				chk_arr = [9,10,11];
				break
			case 'work':
				chk_arr = [1,2,3,4,5];
				break
			case 'weekend':
				chk_arr = [6,7];
				break     
			case 'even':
				chkbxs.each(function(i) {if((i+1) % 2 == 0) $(this).prop('checked', true);})
				break
			case 'odd':
				chkbxs.each(function(i) {if((i+1) % 2 == 1) $(this).prop('checked', true);})
				break
		}

		if (chk_arr.length) {
			chkbxs.each(function(i) {
				if(chk_arr.indexOf(i+1) != -1) $(this).prop('checked', true);
			});
		}

		return false;
	});

	$('.operation_cat_filter_apply').click(function() {
		var select = $(this).closest('div').find('select');
		location.href = '/operations/index?OperationSearch[cat_id]=' + select.val();
		return false;
	});

});