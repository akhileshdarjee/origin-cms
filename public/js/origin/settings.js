$(document).ready(function() {
    $('body').on('change', '[name="theme"]', function() {
        applyTheme($.trim($(this).val()));
    });

    $('form#change-password-form').on('change input', 'input', function() {
        $(this).removeClass("is-invalid");
        $(this).closest('.form-group').find('.invalid-feedback').hide();
    });

    $("form#change-password-form").on("submit", function(e) {
        var password_form = $("body").find("form#change-password-form");
        var current_password = $(password_form).find('[name="current_password"]');
        var new_password = $(password_form).find('[name="new_password"]');
        var new_password_confirmation = $(password_form).find('[name="new_password_confirmation"]');

        $(current_password).removeClass("is-invalid");
        $(new_password).removeClass("is-invalid");
        $(new_password_confirmation).removeClass("is-invalid");
        $(password_form).find('.invalid-feedback').hide();

        if (!$.trim($(current_password).val())) {
            $(current_password).addClass("is-invalid");
            $(current_password).closest('.form-group').find('.invalid-feedback').show();
            e.preventDefault();
        }

        if (!$.trim($(new_password).val())) {
            $(new_password).addClass("is-invalid");
            $(new_password).closest('.form-group').find('.invalid-feedback').show();
            e.preventDefault();
        }

        if (!$.trim($(new_password_confirmation).val())) {
            $(new_password_confirmation).addClass("is-invalid");
            $(new_password_confirmation).closest('.form-group').find('.invalid-feedback').show();
            e.preventDefault();
        }
    });
});