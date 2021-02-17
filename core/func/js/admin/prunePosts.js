$(document).ready(function() {
	$("#prunePosts").click(function() {
		if ($("#prunePosts").is(":disabled") == false) {
			$("#prunePosts").prop("disabled", true);
			$("#username").prop("disabled", true);
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			var username = $("#username").val();
			$.post('/core/func/api/admin/post/prunePosts.php', {
				csrf: csrf_token,
				username: username
			})
			.done(function(response) {
				$("#prunePosts").prop("disabled", false);
				$("#username").prop("disabled", false);
				if (response == "missing-info") {
					$("#bStatus").html("<div class=\"alert alert-danger\">We are missing information from you. Please check again.</div>");
				}else if (response == "can-not-prune-yourself") {
					$("#bStatus").html("<div class=\"alert alert-danger\">You can not prune your own posts.</div>");
				}else if (response == "no-user") {
					$("#bStatus").html("<div class=\"alert alert-danger\">The user you are trying to prune does not exist.</div>");
				}else if (response == "can-not-prune-user") {
					$("#bStatus").html("<div class=\"alert alert-danger\">You can not prune this user.</div>");
				}else if (response == "error") {
					$("#bStatus").html("<div class=\"alert alert-danger\">Could not prune posts from this user because a network error occurred.</div>");
				}else if (response == "success") {
					$("#bStatus").html("<div class=\"alert alert-success\">Posts pruned.</div>");
				}
			})
			.fail(function() {
				$("#bStatus").html("<div class=\"alert alert-danger\">Could not prune posts from this user because a network error occurred.</div>");
			});
		}
	})
})