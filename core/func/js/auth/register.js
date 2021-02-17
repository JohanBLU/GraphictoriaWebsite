$(document).ready(function() {
	$("#rSubmit").click(function() {
		if ($("#rSubmit").is(":disabled") == false) {
			$("#rUsername").prop("disabled", true);
			$("#rEmail").prop("disabled", true);
			$("#rPassword1").prop("disabled", true);
			$("#rPassword2").prop("disabled", true);
			$("#rSubmit").prop("disabled", true);
			
			var formData = new FormData();
			formData.append('username', $("#rUsername").val());
			formData.append('email', $("#rEmail").val());
			formData.append('passwd1', $("#rPassword1").val());
			formData.append('passwd2', $("#rPassword2").val());
			formData.append('captcha', $("#g-recaptcha-response").val());
			formData.append('csrf', $('meta[name="csrf-token"]').attr('content'));
			
			$.ajax({
				type: "POST",
				url : "/core/func/api/auth/register.php",
				data : formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data) {
					$("#rUsername").prop("disabled", false);
					$("#rEmail").prop("disabled", false);
					$("#rPassword1").prop("disabled", false);
					$("#rPassword2").prop("disabled", false);
					$("#rSubmit").prop("disabled", false);
					if (data != "success") {
						grecaptcha.reset();
					}
					if (data == "error") {
						$("#rStatus").html("<div class=\"alert alert-danger\">Unexpected network error. Try again later.</div>");
					}else if (data == "invalid-username") {
						$("#rStatus").html("<div class=\"alert alert-danger\">Your username is invalid. Please try something else.</div>");
					}else if (data == "illegal-username") {
						$("#rStatus").html("<div class=\"alert alert-danger\">Your username contains illegal characters.</div>");
					}else if (data == "username-too-long") {
						$("#rStatus").html("<div class=\"alert alert-danger\">Your username is too long.</div>");
					}else if (data == "username-too-short") {
						$("#rStatus").html("<div class=\"alert alert-danger\">Your username is too short.</div>");
					}else if (data == "no-username") {
						$("#rStatus").html("<div class=\"alert alert-danger\">Please enter a username.</div>");
					}else if (data == "no-email") {
						$("#rStatus").html("<div class=\"alert alert-danger\">Please enter an E-Mail.</div>");
					}else if (data == "no-email") {
						$("#rStatus").html("<div class=\"alert alert-danger\">Please enter an E-Mail.</div>");
					}else if (data == "email-too-long") {
						$("#rStatus").html("<div class=\"alert alert-danger\">Your E-Mail is too long.</div>");
					}else if (data == "illegal-email") {
						$("#rStatus").html("<div class=\"alert alert-danger\">Invalid E-Mail has been specified.</div>");
					}else if (data == "no-password") {
						$("#rStatus").html("<div class=\"alert alert-danger\">Please fill in both password fields.</div>");
					}else if (data == "passwords-mismatch") {
						$("#rStatus").html("<div class=\"alert alert-danger\">Your password and password confirmation do not match.</div>");
					}else if (data == "password-too-short") {
						$("#rStatus").html("<div class=\"alert alert-danger\">Your password is too short.</div>");
					}else if (data == "password-too-long") {
						$("#rStatus").html("<div class=\"alert alert-danger\">Your password is too long.</div>");
					}else if (data == "email-already-used") {
						$("#rStatus").html("<div class=\"alert alert-danger\">The E-Mail you have entered is already being used.</div>");
					}else if (data == "username-already-used") {
						$("#rStatus").html("<div class=\"alert alert-danger\">The username you have entered is already being used.</div>");
					}else if (data == "success") {
						$("#rStatus").html("<div class=\"alert alert-success\">Your account has been created!</div>");
					}else if (data == "incorrect-captcha") {
						$("#rStatus").html("<div class=\"alert alert-danger\">Either you did not do the captcha or the captcha is incorrect.</div>");
					}else if (data == "missing-info") {
						$("#rStatus").html("<div class=\"alert alert-danger\">Please finish the registration form.</div>");
					}else if (data == "rate-limit") {
						$("#rStatus").html("<div class=\"alert alert-danger\">You have recently made an account. Please wait before making a new one.</div>");
					}else if (data == "unknown-email") {
						$("#rStatus").html("<div class=\"alert alert-warning\">The email you have entered is unknown. Please use known email providers such as <a href=\"http://gmail.com\">gmail</a> or <a href=\"http://outlook.com\">outlook</a></div>");
					}
				},
				error: function() {
					$("#rStatus").html("<div class=\"alert alert-danger\">Unexpected network error. Try again later.</div>");
				}
			});
		}
	})
});