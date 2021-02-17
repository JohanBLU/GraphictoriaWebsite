$(document).ready(function() {
	$("#banUser").click(function() {
		if ($("#banUser").is(":disabled") == false) {
			$("#banUser").prop("disabled", true);
			$("#username").prop("disabled", true);
			$("#banReason").prop("disabled", true);
			$("#duration").prop("disabled", true);
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			var username = $("#username").val();
			var banReason = $("#banReason").val();
			var duration = $("#duration").val();
			$.post('/core/func/api/admin/post/banUser.php', {
				csrf: csrf_token,
				username: username,
				banReason: banReason,
				duration: duration
			})
			.done(function(response) {
				$("#banUser").prop("disabled", false);
				$("#username").prop("disabled", false);
				$("#banReason").prop("disabled", false);
				$("#duration").prop("disabled", false);
				if (response == "error") {
					$("#bStatus").html("<div class=\"alert alert-danger\">Could not ban this user because a network error occurred.</div>");
				}else if (response == "missing-info") {
					$("#bStatus").html("<div class=\"alert alert-danger\">You must fill in all fields to ban this user.</div>");
				}else if (response == "invalid-duration") {
					$("#bStatus").html("<div class=\"alert alert-danger\">Invalid duration.</div>");
				}else if (response == "can-not-ban-yourself") {
					$("#bStatus").html("<div class=\"alert alert-danger\">You can not ban yourself.</div>");
				}else if (response == "reason-too-long") {
					$("#bStatus").html("<div class=\"alert alert-danger\">The ban reason is too long.</div>");
				}else if (response == "no-user") {
					$("#bStatus").html("<div class=\"alert alert-danger\">The user has not been found.</div>");
				}else if (response == "can-not-ban-user") {
					$("#bStatus").html("<div class=\"alert alert-danger\">We could not ban this user.</div>");
				}else if (response == "user-already-banned") {
					$("#bStatus").html("<div class=\"alert alert-danger\">This user has already been banned. If you wish to unban, use the unban utility.</div>");
				}else if (response == "success") {
					$("#bStatus").html("<div class=\"alert alert-success\">This user has been banned.</div>");
				}
			})
			.fail(function() {
				$("#bStatus").html("<div class=\"alert alert-danger\">Could not ban this user because a network error occurred.</div>");
			});
		}
	})
})