$(document).ready(function() {
    $('[name="display_type"]').on("change", function() {
        var type = $(this).val();

        if (type == "cozy") {
            $('body').addClass('sidebar-collapse');
        }
        else if (type == "comfortable") {
            $('body').removeClass('sidebar-collapse');
        }
    });

    $('[name="theme"]').on("change", function() {
        var selected_theme = $(this).val();

        $('body').removeClass('skin-blue skin-yellow skin-green skin-purple skin-red skin-black');
        $('body').removeClass('skin-blue-light skin-yellow-light skin-green-light skin-purple-light skin-red-light skin-black-light');
        $('body').addClass(selected_theme);
    });
});