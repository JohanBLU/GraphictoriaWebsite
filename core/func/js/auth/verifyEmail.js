$(document).ready(function() {
	$("#verifyEmailCode").click(function() {
		if ($("#verifyEmailCode").is(":disabled") == false) {
			$("#verifyEmailCode").prop("disabled", true);
			$("#emailCode").prop("disabled", true);
			
			var emailCode = $("#emailCode").val();
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.post('/core/func/api/auth/verifyEmail.php', {
				emailCode: emailCode,
				csrf: csrf_token
			})
			.done(function(response) {
				$("#verifyEmailCode").prop("disabled", false);
				$("#emailCode").prop("disabled", false);
				if (response == "error") {
					$("#vStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
				}else if (response == "incorrect-code") {
					$("#vStatus").html("<div class=\"alert alert-danger\">You have entered an incorrect verification code.</div>");
				}else if (response == "success") {
					window.location = "/";
				}
			})
			.fail(function() {
				$("#vStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
			});
		}
	})
	
	$("#confirmChange").click(function() {
		if ($("#confirmChange").is(":disabled") == false) {
			$("#confirmChange").prop("disabled", true);
			$("#currentPassword").prop("disabled", true);
			$("#newEmail").prop("disabled", true);
			
			var newEmail = $("#newEmail").val();
			var currentPassword = $("#currentPassword").val();
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.post('/core/func/api/auth/changeEmailVerify.php', {
				currentPassword: currentPassword,
				newEmail: newEmail,
				csrf: csrf_token
			})
			.done(function(response) {
				$("#confirmChange").prop("disabled", false);
				$("#currentPassword").prop("disabled", false);
				$("#newEmail").prop("disabled", false);
				console.log(response);
				if (response == "error") $(".gModalErrorContainer").html('<div class="alert alert-danger gModalError">Could not change your email because a network error occurred.</div>');
				if (response == "missing-info") $(".gModalErrorContainer").html('<div class="alert alert-danger gModalError">You are missing information. Please check and try again.</div>');
				if (response == "inc-password") $(".gModalErrorContainer").html('<div class="alert alert-danger gModalError">Your current password is incorrect.</div>');
				if (response == "inc-email") $(".gModalErrorContainer").html('<div class="alert alert-danger gModalError">You have entered an invalid or illegal email address.</div>');
				if (response == "email-in-use") $(".gModalErrorContainer").html('<div class="alert alert-danger gModalError">That email address is already in use by another user. Use another one.</div>');
				if (response == "unknown-email") $(".gModalErrorContainer").html('<div class="alert alert-danger gModalError">You are using an unknown email service. Please use a more known one such as hotmail or gmail.</div>');
				if (response == "rate-limit") $(".gModalErrorContainer").html('<div class="alert alert-danger gModalError">You have recently changed your email address. Please wait at least 60 minutes before trying again.</div>');
				if (response == "success") window.location.reload();
			})
			.fail(function() {
				$(".gModalErrorContainer").html('<div class="alert alert-danger gModalError">Could not change your email because a network error occurred.</div>');
			});
		}
	})
	
	// Toggle on enter
	$("#emailCode").keyup(function(event) {
		if(event.keyCode == 13) {
			$("#verifyEmailCode").click();
		}
	})
});