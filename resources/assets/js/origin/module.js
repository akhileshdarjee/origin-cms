$(document).ready(function() {
	$('[name="name"]').on("change", function() {
		var module_name = $(this).val();
		var slug = module_name.replace(/ /g, '_').toLowerCase();

		if (!$('[name="display_name"]').val()) {
			$('[name="display_name"]').val(module_name);
		}

		if (!$('[name="controller_name"]').val()) {
			$('[name="controller_name"]').val(module_name + 'Controller');
		}

		if (!$('[name="table_name"]').val()) {
			$('[name="table_name"]').val(slug);
		}

		if (!$('[name="slug"]').val()) {
			$('[name="slug"]').val(slug);
		}

		if (!$('[name="sort_field"]').val()) {
			$('[name="sort_field"]').val('id');
		}

		if (!$('[name="icon_color"]').val()) {
			$('[name="icon_color"]').val('#ffffff');
		}
	});
});