$(function() {

	//Общее
	var minicart = $('#minicart'),
		total_amount_holder = minicart.find('#total_amount'),
		total_price_holder = minicart.find('#total_price');
	$('a.cart_link').click(function() {
		minicart.toggle();
		return false;
	});

	// Добавление и редактирование товаров
	var product_create_form = $('.product-form form');
	if (product_create_form.length) {

		var amount_table = $('#amount_table'),
			amount_table_default = amount_table.html();

		$('#product-sizes').change(function() {
			amount_table.html(amount_table_default);
			var checked_sizes = $('input[name="Product\\[sizes\\]\\[\\]"]:checked');
			$.each(checked_sizes.get().reverse(), function() {
				var size_name = $.trim($(this).parent().text());
                amount_table.find('tr:first').prepend('<th>' + size_name + '</th>');
				amount_table.find('tr:gt(0)').prepend('<td><input type="number" class="form-control" value="0" name="Product[amount][' + $(this).val() + '][]"></td>');
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

		var amount_inputs = product_table.find('tr.order_tr input');
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
			total_price_holder.text(total_price);
		});
	}

});