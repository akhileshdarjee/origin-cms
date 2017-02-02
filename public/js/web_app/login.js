$( document ).ready(function() {
	$('form').on('change input', 'input', function() {
		$(this).removeClass("error");
		$(this).closest('.form-group').find('#alert').hide();
	});

	$("#login").submit(function( event ) {
		if ($.trim($("#login_id").val()) == "") {
			$("#login_id").addClass("error");
			$("#login_id").closest('.form-group').find('#alert').show();
			event.preventDefault();
		}

		if ($.trim($("#password").val()) == "") {
			$("#password").addClass("error");
			$("#password").closest('.form-group').find('#alert').show();
			event.preventDefault();
		}

		if ($.trim($("#login_id").val()) && $.trim($("#password").val())) {
			$('#submit-login').button('loading');
		}
	});
});