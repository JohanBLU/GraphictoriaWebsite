$(document).ready(function() {
	$("#updateAbout").click(function() {
		if ($("#updateAbout").is(":disabled") == false) {
			$("#updateAbout").prop("disabled", true);
			$("#aboutValue").prop("disabled", true);
			
			var aboutContent = $("#aboutValue").val();
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.post('/core/func/api/settings/post/updateAbout.php', {
				aboutContent: aboutContent,
				csrf: csrf_token
			})
			.done(function(response) {
				$("#updateAbout").prop("disabled", false);
				$("#aboutValue").prop("disabled", false);
				if (response == "filtered") {
					$("#sStatus").html("<div class=\"alert alert-success\">Your about text contains bad words! Please remove them</div>");
					$("#aboutValue").val("");
				}else{
					$("#sStatus").html("<div class=\"alert alert-success\">About text changed!</div>");
				}
			})
			.fail(function() {
				$("#sStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
			});
		}
	})
	
	$("#enableRegular").click(function() {
		if ($("#enableRegular").is(":disabled") == false) {
			$("#enableRegular").prop("disabled", true);
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.post('/core/func/api/settings/post/updateTheme.php', {
				theme: 0,
				csrf: csrf_token
			})
			.done(function(response) {
				$("#enableRegular").prop("disabled", false);
				$("#aboutValue").prop("disabled", false);
				if (response == "success") {
					window.location.reload();
				}else{
					$("#sStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
				}
			})
			.fail(function() {
				$("#sStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
			});
		}
	})
	
	$("#enableDark").click(function() {
		if ($("#enableRegular").is(":disabled") == false) {
			$("#enableDark").prop("disabled", true);
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.post('/core/func/api/settings/post/updateTheme.php', {
				theme: 1,
				csrf: csrf_token
			})
			.done(function(response) {
				$("#enableDark").prop("disabled", false);
				$("#aboutValue").prop("disabled", false);
				if (response == "success") {
					window.location.reload();
				}else{
					$("#sStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
				}
			})
			.fail(function() {
				$("#sStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
			});
		}
	})
	
	$("#changePassword").click(function() {
		if ($("#changePassword").is(":disabled") == false) {
			$("#changePassword").prop("disabled", true);
			$("#nPassword1").prop("disabled", true);
			$("#nPassword2").prop("disabled", true);
			$("#curPassword").prop("disabled", true);
			
			var npass1 = $("#nPassword1").val();
			var npass2 = $("#nPassword2").val();
			var currentPassword = $("#curPassword").val();
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.post('/core/func/api/settings/post/changePassword.php', {
				newPassword1: npass1,
				newPassword2: npass2,
				currentPassword: currentPassword,
				csrf: csrf_token
			})
			.done(function(response) {
				$("#changePassword").prop("disabled", false);
				$("#nPassword1").prop("disabled", false);
				$("#nPassword2").prop("disabled", false);
				$("#curPassword").prop("disabled", false);
				
				if (response == "error") {
					$("#cPassStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
				}else if (response == "missing-info") {
					$("#cPassStatus").html("<div class=\"alert alert-danger\">Please fill in everything</div>");
				}else if (response == "confirm-failed") {
					$("#cPassStatus").html("<div class=\"alert alert-danger\">Password confirmation has failed</div>");
				}else if (response == "password-too-short") {
					$("#cPassStatus").html("<div class=\"alert alert-danger\">Your new password is too short</div>");
				}else if (response == "password-too-long") {
					$("#cPassStatus").html("<div class=\"alert alert-danger\">Your new password is too long</div>");
				}else if (response == "wrong-password") {
					$("#cPassStatus").html("<div class=\"alert alert-danger\">Your current password is incorrect</div>");
				}else if (response == "success") {
					window.location = "/";
				}
			})
			.fail(function() {
				$("#cPassStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
			});
		}
	})
	
	$("#changeEmail").click(function() {
		if ($("#changeEmail").is(":disabled") == false) {
			$("#changeEmail").prop("disabled", true);
			$("#email").prop("disabled", true);
			$("#password").prop("disabled", true);
			
			var newEmail = $("#email").val();
			var currentPassword = $("#password").val();
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.post('/core/func/api/settings/post/changeEmail.php', {
				newEmail: newEmail,
				currentPassword: currentPassword,
				csrf: csrf_token
			})
			.done(function(response) {
				$("#changeEmail").prop("disabled", false);
				$("#email").prop("disabled", false);
				$("#password").prop("disabled", false);
				if (response == "error") {
					$("#cEmailStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
				}else if (response == "missing-info") {
					$("#cEmailStatus").html("<div class=\"alert alert-danger\">Please fill in everything</div>");
				}else if (response == "success") {
					window.location = "/";
				}else if (response == "wrong-password") {
					$("#cEmailStatus").html("<div class=\"alert alert-danger\">Your current password is incorrect</div>");
				}else if (response == "rate-limit") {
					$("#cEmailStatus").html("<div class=\"alert alert-danger\">You have recently verified your current email, please wait at least 5 minutes before doing this</div>");
				}else if (response == "unknown-email") {
					$("#cEmailStatus").html("<div class=\"alert alert-danger\">You are using an unknown email provider. Use a more known one such as hotmail or gmail</div>");
				}else if (response == "email-in-use") {
					$("#cEmailStatus").html("<div class=\"alert alert-danger\">That email is already in use by another user.</div>");
				}
			})
			.fail(function() {
				$("#cEmailStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
			});
		}
	})
	
	$("#enableTwo").click(function() {
		if ($("#enableTwo").is(":disabled") == false) {
			$("#enableTwo").prop("disabled", true);
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.post('/core/func/api/settings/post/enableTwo.php', {
				csrf: csrf_token
			})
			.done(function(response) {
				$("#updateAbout").prop("disabled", false);
				$("#aboutValue").prop("disabled", false);
				if (response == "success") {
					$("#twocontainer").load("/core/func/api/settings/get/twoStep.php");
				}else{
					$("#sStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
				}
			})
			.fail(function() {
				$("#sStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
			});
		}
	})
});

function enableTwoFinal() {
	if ($("#enableTwoFinal").is(":disabled") == false) {
		$("#enableTwoFinal").prop("disabled", true);
		$("#finalCode").prop("disabled", true);
		var finalCode = $("#finalCode").val();
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		$.post('/core/func/api/settings/post/enableTwoFinal.php', {
			finalCode: finalCode,
			csrf: csrf_token
		})
		.done(function(response) {
			$("#enableTwoFinal").prop("disabled", false);
			$("#finalCode").prop("disabled", false);
			if (response == "success") {
				$("#sStatus").html("<div class=\"alert alert-success\">Two Step Authentication has been enabled! You will be asked to use it the next time you login.</div>");
				$("#twocontainer").load("/core/func/api/settings/get/twoStep.php");
			}else if(response == "missing-info") {
				$("#sStatus").html("<div class=\"alert alert-danger\">Please enter your authentication code.</div>");
			}else if(response == "wrong-code") {
				$("#sStatus").html("<div class=\"alert alert-danger\">Your authentication code is incorrect. Did you use the Authy or Google authentication mobile app?</div>");
			}else{
				$("#sStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
			}
		})
		.fail(function() {
			$("#sStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
		});
	}
}

function disableFactor() {
	if ($("#disableTwo").is(":disabled") == false) {
		$("#disableTwo").prop("disabled", true);
		var finalCode = $("#finalCode").val();
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		$.post('/core/func/api/settings/post/disableTwo.php', {
			csrf: csrf_token
		})
		.done(function(response) {
			$("#disableTwo").prop("disabled", false);
			if (response == "success") {
				$("#twocontainer").load("/core/func/api/settings/get/twoStep.php");
			}else if (response == "staff-block") {
				$("#sStatus").html("<div class=\"alert alert-danger\">Staff members may not disable this feature.</div>");
			}else{
				$("#sStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>")
			}
		})
		.fail(function() {
			$("#sStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
		});
	}
}