function loadFriends(page) {
	$("#friendsContainer").load("/core/func/api/friends/get/getFriends.php?page=" + page);
}

$(document).ready(function() {
	loadFriends(0);
});

function removeFriend(userID, currentPage) {
	if ($(".rmFr").is(":disabled") == false) {
		if ($("[value="+userID+"]").text() != "Are you sure?") {
			$("[value="+userID+"]").text("Are you sure?");
		}else{
			$(".rmFr").prop("disabled", true);
			$("[value="+userID+"]").text("Deleting Friend...");
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.post('/core/func/api/friends/post/removeFriend.php', {
				csrf: csrf_token,
				userID: userID
			})
			.done(function(response) {
				if (response == "error") {
					$("#fStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
				}else{
					loadFriends(currentPage);
				}
			})
			.fail(function() {
				$("#fStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
			});
		}
	}
}