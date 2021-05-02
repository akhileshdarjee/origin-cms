$(document).ready(function() {
    $('[name="name"]').on({
        keydown: function(e) {
            if (e.which === 32)
                return false;
        }
    });

    $('body').on('change', '[name="name"]', function() {
        var module_name = $(this).val().replace(/\s/g, "");
        var slug = module_name.slugify();

        if (!$.trim($('body').find('[name="display_name"]').val())) {
            $('body').find('[name="display_name"]').val(module_name).trigger('change');
        }

        if (!$.trim($('body').find('[name="controller_name"]').val())) {
            $('body').find('[name="controller_name"]').val(module_name + 'Controller').trigger('change');
        }

        if (!$.trim($('body').find('[name="table_name"]').val())) {
            $('body').find('[name="table_name"]').val(slug).trigger('change');
        }

        if (!$.trim($('body').find('[name="slug"]').val())) {
            $('body').find('[name="slug"]').val(slug).trigger('change');
        }
    });
});