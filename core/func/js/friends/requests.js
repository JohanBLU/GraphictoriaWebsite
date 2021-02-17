function loadRequests(page) {
	$("#friendsContainer").load("/core/func/api/friends/get/getRequests.php?page=" + page);
}

$(document).ready(function() {
	loadRequests(0);
});

function ignoreRequest(userID, currentPage) {
	if ($(".btn-xs").is(":disabled") == false) {
		if ($("[value="+userID+"ignore]").text() != "Are you sure?") {
			$("[value="+userID+"ignore]").text("Are you sure?");
		}else{
			$(".btn-xs").prop("disabled", true);
			$("[value="+userID+"ignore]").text("Deleting Friend Request...");
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.post('/core/func/api/friends/post/ignoreRequest.php', {
				csrf: csrf_token,
				userID: userID
			})
			.done(function(response) {
				if (response == "error") {
					$("#fStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
				}else{
					loadRequests(currentPage);
				}
			})
			.fail(function() {
				$("#fStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
			});
		}
	}
}

function acceptRequest(userID, currentPage) {
	if ($(".btn-xs").is(":disabled") == false) {
		$(".btn-xs").prop("disabled", true);
		$("[value="+userID+"]").text("Accepting friend request...");
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		$.post('/core/func/api/friends/post/acceptRequest.php', {
			csrf: csrf_token,
			userID: userID
		})
		.done(function(response) {
			if (response == "error") {
				$("#fStatus").html("<div class=\"alert alert-danger\">Could not add friend.</div>");
			}else{
				loadRequests(currentPage);
			}
		})
		.fail(function() {
			$("#fStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
		});
	}
}