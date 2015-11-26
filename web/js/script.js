$(function() {
	
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

});