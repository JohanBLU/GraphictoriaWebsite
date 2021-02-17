$(document).ready(function() {
	$("#rewardPostie").click(function() {
		if ($("#rewardPostie").is(":disabled") == false) {
			$("#rewardPostie").prop("disabled", true);
			$("#username").prop("disabled", true);
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			var username = $("#username").val();
			$.post('/core/func/api/admin/post/rewardPostie.php', {
				csrf: csrf_token,
				username: username
			})
			.done(function(response) {
				console.log(response)
				$("#rewardPostie").prop("disabled", false);
				$("#username").prop("disabled", false);
				if (response == "error") {
					$("#bStatus").html("<div class=\"alert alert-danger\">Could reward this user because a network error occurred.</div>");
				}else if (response == "missing-info") {
					$("#bStatus").html("<div class=\"alert alert-danger\">We are missing information from you. Please check and try again.</div>");
				}else if (response == "can-not-reward-yourself") {
					$("#bStatus").html("<div class=\"alert alert-danger\">You can not reward yourself!</div>");
				}else if (response == "no-user") {
					$("#bStatus").html("<div class=\"alert alert-danger\">This user has not been found.</div>");
				}else if (response == "can-not-reward-user") {
					$("#bStatus").html("<div class=\"alert alert-danger\">This user has already been rewarded recently. Try again later</div>");
				}else if (response == "success") {
					$("#bStatus").html("<div class=\"alert alert-success\">This user has recieved 10 posties!</div>");
				}
			})
			.fail(function() {
				$("#bStatus").html("<div class=\"alert alert-danger\">Could reward this user because a network error occurred.</div>");
			});
		}
	})
})