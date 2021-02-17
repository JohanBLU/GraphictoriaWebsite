$(document).ready(function() {
	$("#liftBan").click(function() {
		if ($("#liftBan").is(":disabled") == false) {
			$("#liftBan").prop("disabled", true);
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.post('/core/func/api/account/liftBan.php', {
				csrf: csrf_token
			})
			.done(function(response) {
				$("#liftBan").prop("disabled", false);
				if (response == "error") {
					$("#sStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
				}else if (response == "ban-lift-error") {
					$("#sStatus").html("<div class=\"alert alert-danger\">Your ban has not been expired yet.</div>");
				}else{
					window.location = "/";
				}
			})
			.fail(function() {
				$("#sStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
			});
		}
	})
});