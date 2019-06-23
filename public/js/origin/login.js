$( document ).ready(function() {
	$('form#login-form').on('change input', 'input', function() {
		$(this).removeClass("error");
		$(this).closest('.form-group').find('#alert').hide();
	});

	$("form#login-form").on("submit", function(e) {
		var login_form = $("form#login-form");
		var login_id = $(login_form).find('[name="login_id"]');
		var password = $(login_form).find('[name="password"]');
		$('.help-block').hide();
		$(login_id).removeClass("error");
		$(password).removeClass("error");

		if (!$.trim($(login_id).val())) {
			$(login_id).addClass("error");
			$(login_id).closest('.form-group').find('.help-block').show();
			e.preventDefault();
		}

		if (!$.trim($(password).val())) {
			$(password).addClass("error");
			$(password).closest('.form-group').find('.help-block').show();
			e.preventDefault();
		}

		if ($.trim($(login_id).val()) && $.trim($(password).val())) {
			$('#submit-login').button('loading');
		}
	});
});