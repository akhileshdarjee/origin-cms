$(document).ready(function() {
	if (form_data['tabSettings']['social_login'] == "Inactive") {
		$("#facebook_login").attr("disabled", true);
		$("#google_login").attr("disabled", true);
	}
});