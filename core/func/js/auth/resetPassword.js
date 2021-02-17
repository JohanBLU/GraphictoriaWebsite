function resetPassword(key, userID) {
	if ($("#changePassword").is(":disabled") == false) {
		$("#changePassword").prop("changePassword", true);
		$("#password1").prop("disabled", true);
		$("#password2").prop("disabled", true);
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		var password1 = $("#password1").val();
		var password2 = $("#password2").val();
		$.post('/core/func/api/auth/resetPassword.php', {
			password1: password1,
			password2: password2,
			key: key,
			userID, userID,
			csrf: csrf_token
		})
		.done(function(response) {
			$("#changePassword").prop("changePassword", false);
			$("#password1").prop("disabled", false);
			$("#password2").prop("disabled", false);
			if (response == "error") {
				$("#rStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
			}else if (response == "invalid-key") {
				$("#rStatus").html("<div class=\"alert alert-danger\">The key specified is invalid.</div>");
			}else if (response == "key-expired") {
				$("#rStatus").html("<div class=\"alert alert-danger\">Your key has expired. Please request a new password reset.</div>");
			}else if (response == "password-mismatch") {
				$("#rStatus").html("<div class=\"alert alert-danger\">The passwords you have specified do not match.</div>");
			}else if (response == "password-too-long") {
				$("#rStatus").html("<div class=\"alert alert-danger\">Your new password is too long.</div>");
			}else if (response == "password-too-short") {
				$("#rStatus").html("<div class=\"alert alert-danger\">Your new password is too short.</div>");
			}else if (response == "success") {
				window.location = "/";
			}
		})
		.fail(function() {
			$("#rStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
		});
	}
}