$(document).ready(function() {
	$("#unbanUser").click(function() {
		if ($("#unbanUser").is(":disabled") == false) {
			$("#unbanUser").prop("disabled", true);
			$("#username").prop("disabled", true);
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			var username = $("#username").val();
			$.post('/core/func/api/admin/post/unbanUser.php', {
				csrf: csrf_token,
				username: username
			})
			.done(function(response) {
				$("#unbanUser").prop("disabled", false);
				$("#username").prop("disabled", false);
				if (response == "error") {
					$("#bStatus").html("<div class=\"alert alert-danger\">Could not unban this user because a network error occurred.</div>");
				}else if (response == "missing-info") {
					$("#bStatus").html("<div class=\"alert alert-danger\">We are missing information from you. Please check and try again.</div>");
				}else if (response == "can-not-unban-yourself") {
					$("#bStatus").html("<div class=\"alert alert-danger\">You can not unban yourself!</div>");
				}else if (response == "no-user") {
					$("#bStatus").html("<div class=\"alert alert-danger\">This user has not been found.</div>");
				}else if (response == "can-not-unban-user") {
					$("#bStatus").html("<div class=\"alert alert-danger\">You can not unban this user.</div>");
				}else if (response == "user-not-banned") {
					$("#bStatus").html("<div class=\"alert alert-danger\">You can not unban this user because this user is not banned.</div>");
				}else if (response == "success") {
					$("#bStatus").html("<div class=\"alert alert-success\">This user has been unbanned!</div>");
				}
			})
			.fail(function() {
				$("#bStatus").html("<div class=\"alert alert-danger\">Could not unban this user because a network error occurred.</div>");
			});
		}
	})
})