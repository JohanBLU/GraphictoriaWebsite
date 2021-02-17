$(document).ready(function() {
	$("#lSubmit").click(function() {
		if ($("#lSubmit").is(":disabled") == false) {
			$("#lSubmit").prop("disabled", true);
			$("#lForgot").prop("disabled", true);
			$("#lUsername").prop("disabled", true);
			$("#lPassword").prop("disabled", true);
			
			var user = $("#lUsername").val(); 
			var passwd = $("#lPassword").val();
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.post('/core/func/api/auth/login.php', {
				username: user,
				passwd: passwd,
				csrf: csrf_token
			})
			.done(function(response) {
				$("#lSubmit").prop("disabled", false);
				$("#lForgot").prop("disabled", false);
				$("#lUsername").prop("disabled", false);
				$("#lPassword").prop("disabled", false);
				if (response == "error") {
					$("#lStatus").css("color", "red").html("Network error. Try again later.");
				}else if (response == "missing-info") {
					$("#lStatus").css("color", "red").html("Please fill in all fields.");
				}else if (response == "no-user") {
					$("#lStatus").css("color", "red").html("No user found with this name.");
				}else if (response == "success") {
					window.location.reload();
				}else if (response == "incorrect-password") {
					$("#lStatus").css("color", "red").html("Incorrect password specified.");
				}else if (response == "rate-limit") {
					$("#lStatus").css("color", "red").html("Please wait a bit...");
				}
			})
			.fail(function() {
				$("#lStatus").css("color", "red").html("Network error. Try again later.");
			});
		}
	});
	
	// Toggle on enter
	$("#lUsername").keyup(function(event) {
		if(event.keyCode == 13) {
			$("#lSubmit").click();
		}
	})
	
	$("#lPassword").keyup(function(event) {
		if(event.keyCode == 13) {
			$("#lSubmit").click();
		}
	})
	
	$("#lForgot").click(function() {
		if ($("#lForgot").is(":disabled") == false) {
			$("#lSubmit").prop("disabled", true);
			$("#lForgot").prop("disabled", true);
			$("#lUsername").prop("disabled", true);
			$("#lPassword").prop("disabled", true);
			var user = $("#lUsername").val(); 
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.post('/core/func/api/auth/forgot.php', {
				username: user,
				csrf: csrf_token
			})
			.done(function(response) {
				$("#lSubmit").prop("disabled", false);
				$("#lForgot").prop("disabled", false);
				$("#lUsername").prop("disabled", false);
				$("#lPassword").prop("disabled", false);
				console.log(response);
				if (response == "no-user") {
					$("#lStatus").css("color", "red").html("User not found");
				}else if (response == "rate-limit") {
					$("#lStatus").css("color", "red").html("Please wait");
				}else if (response == "success") {
					$("#lStatus").css("color", "green").html("E-Mail sent!");
				}
			})
			.fail(function() {
				$("#lStatus").css("color", "red").html("Reset password request failed.");
			});
		}
	})
});