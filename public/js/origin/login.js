$(document).ready(function() {
    $('form#login-form').on('change input', 'input', function() {
        $(this).removeClass("is-invalid");
        $(this).closest('.input-group').find('.invalid-feedback').hide();
    });

    $("form#login-form").on("submit", function(e) {
        var login_form = $("body").find("form#login-form");
        var username = $(login_form).find('[name="username"]');
        var password = $(login_form).find('[name="password"]');

        $(username).removeClass("is-invalid");
        $(password).removeClass("is-invalid");
        $('body').find('.invalid-feedback').hide();

        if (!trim($(username).val())) {
            $(username).addClass("is-invalid");
            $(username).closest('.input-group').find('.invalid-feedback').show();
            e.preventDefault();
        }

        if (!trim($(password).val())) {
            $(password).addClass("is-invalid");
            $(password).closest('.input-group').find('.invalid-feedback').show();
            e.preventDefault();
        }
    });
});