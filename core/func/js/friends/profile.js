function deleteFriendProfile(userID) {
	if ($(".friendBtn").is(":disabled") == false) {
		if ($(".friendBtn").text() != "Are you sure?") {
			$(".friendBtn").text("Are you sure?");
		}else{
			$(".friendBtn").prop("disabled", true);
			$(".friendBtn").text("Deleting Friend...");
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.post('/core/func/api/friends/post/removeFriend.php', {
				csrf: csrf_token,
				userID: userID
			})
			.done(function(response) {
				if (response == "error") {
					$("#pStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
				}else{
					$(".friendBtn").remove();
				}
			})
			.fail(function() {
				$("#pStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
			});
		}
	}
}

function sendRequest(userID) {
	$(".friendBtn").prop("disabled", true);
	$(".friendBtn").text("Sending...");
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.post('/core/func/api/friends/post/sendRequest.php', {
		csrf: csrf_token,
		userID: userID
	})
	.done(function(response) {
		console.log(response);
		if (response == "error") {
			$("#pStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
		}else if (response == "rate-limit") {
			$("#pStatus").html("<div class=\"alert alert-danger\">Please wait before sending another friend request.</div>");
		}else{
			$(".friendBtn").remove();
		}
	})
	.fail(function() {
		$("#pStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
	});
}