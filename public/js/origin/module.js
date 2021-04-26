$(document).ready(function() {
    $('[name="name"]').on({
        keydown: function(e) {
            if (e.which === 32)
                return false;
        }
    });

    $('body').on('change', '[name="name"]' function() {
        var module_name = $(this).val().replace(/\s/g, "");
        var slug = module_name.slugify();

        if (!$('body').find('[name="display_name"]').val()) {
            $('body').find('[name="display_name"]').val(module_name);
        }

        if (!$('body').find('[name="controller_name"]').val()) {
            $('body').find('[name="controller_name"]').val(module_name + 'Controller');
        }

        if (!$('body').find('[name="table_name"]').val()) {
            $('body').find('[name="table_name"]').val(slug);
        }

        if (!$('body').find('[name="slug"]').val()) {
            $('body').find('[name="slug"]').val(slug);
        }

        if (!$('body').find('[name="sort_field"]').val()) {
            $('body').find('[name="sort_field"]').val('id');
        }

        if (!$('body').find('[name="icon_color"]').val()) {
            $('body').find('[name="icon_color"]').val('#ffffff');
        }
    });
});