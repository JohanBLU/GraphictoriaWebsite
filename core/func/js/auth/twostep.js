$(document).ready(function() {
	$("#finishAuth").click(function() {
		if ($("#finishAuth").is(":disabled") == false) {
			$("#finishAuth").prop("disabled", true);
			$("#factorCode").prop("disabled", true);
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			var twoStepCode = $("#factorCode").val();
			$.post('/core/func/api/auth/twostep.php', {
				factorCode: twoStepCode,
				csrf: csrf_token
			})
			.done(function(response) {
				$("#finishAuth").prop("disabled", false);
				$("#factorCode").prop("disabled", false);
				if (response == "success") {
					window.location = "/";
				}else if (response == "wrong-code") {
					$("#tStatus").html("<div class=\"alert alert-danger\">You have entered an incorrect code.</div>");
				}else if (response == "missing-info") {
					$("#tStatus").html("<div class=\"alert alert-danger\">Please enter a code.</div>");
				}else{
					$("#tStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
				}
			})
			.fail(function() {
				$("#tStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
			});
		}
	})
	
	// Toggle on enter
	$("#factorCode").keyup(function(event) {
		if(event.keyCode == 13) {
			$("#finishAuth").click();
		}
	})
});