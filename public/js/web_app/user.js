$( document ).ready(function() {
	if ($("#id").val()) {
		var status_bar = '<div class="x_content">\
			<div class="row">\
				<div class="col-md-3 col-sm-12">\
					Email Confirmed: ';

		if (parseInt(doc.data["tabUser"]["email_confirmed"])) {
			status_bar += '<span class="label label-success" id="email_confirmed">Yes</span>';
		}
		else {
			status_bar += '<span class="label label-danger" id="email_confirmed">No</span>';
		}

		status_bar += '</div>\
			</div>\
		</div>';

		$(status_bar).insertAfter(".floatbox-title");
	}
});