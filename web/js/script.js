$(function() {
	
	var product_create_form = $('.product-create form');
	if (product_create_form.length) {

		var amount_table = $('#amount_table'),
			amount_table_default = amount_table.html();
		$('#product-size').change(function() {
			amount_table.html(amount_table_default);
			var checked_sizes = $('input[name="Product\\[size\\]\\[\\]"]:checked');
			$.each(checked_sizes.get().reverse(), function() {
				var size_name = $.trim($(this).parent().text());
                amount_table.find('tr:first').prepend('<th>' + size_name + '</th>');
				amount_table.find('tr:gt(0)').prepend('<td><input type="text" class="form-control" name="Product[amount][' + $(this).val() + '][]"></td>');
            });
		});
	}

});