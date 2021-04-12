$(document).ready(function() {
    $('[name="name"]').on({
        keydown: function(e) {
            if (e.which === 32)
                return false;
        }
    });

    $('[name="name"]').on("change", function() {
        var module_name = $(this).val().replace(/\s/g, "");
        var slug = module_name.slugify();

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