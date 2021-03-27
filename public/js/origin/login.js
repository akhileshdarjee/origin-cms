$( document ).ready(function() {
    $('form#login-form').on('change input', 'input', function() {
        $(this).removeClass("error");
        $(this).closest('.form-group').find('.help-block').hide();
    });

    $("form#login-form").on("submit", function(e) {
        var login_form = $("form#login-form");
        var username = $(login_form).find('[name="username"]');
        var password = $(login_form).find('[name="password"]');
        $('body').find('.help-block').hide();
        $(username).removeClass("error");
        $(password).removeClass("error");

        if (!$.trim($(username).val())) {
            $(username).addClass("error");
            $(username).closest('.form-group').find('.help-block').show();
            e.preventDefault();
        }

        if (!$.trim($(password).val())) {
            $(password).addClass("error");
            $(password).closest('.form-group').find('.help-block').show();
            e.preventDefault();
        }

        if ($.trim($(username).val()) && $.trim($(password).val())) {
            $('body').find('#submit-login').button('loading');
        }
    });
});