$( document ).ready(function() {
	toggle_report_type();

	$("#type").on("change", function() {
		toggle_report_type();
	});

	function toggle_report_type() {
		if ($("#type").val() == "Query") {
			$("#query_section").show();

			if (!$("#columns").val()) {
				$("#columns").addClass('error');
				$("#columns").closest('.form-group').addClass('has-error');
			}

			$("#columns").data("mandatory", "yes");
			$("#columns").attr("data-mandatory", "yes");
		}
		else {
			$("#query_section").hide();
			$("#query").val('');
			$("#columns").removeClass('error');
			$("#columns").closest('.form-group').removeClass('has-error');
			$("#columns").data("mandatory", "no");
			$("#columns").attr("data-mandatory", "no");
		}
	}
});