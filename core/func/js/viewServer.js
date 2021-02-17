function deleteServer(serverID) {
	if ($("#deletePost").is(":disabled") == false) {
		if ($("#deleteServer").text() != "Are you sure?") {
			$("#deleteServer").text("Are you sure?");
		}else{
			$("#deleteServer").prop("disabled", true);
			$("#deleteServer").text("Deleting Server...");
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.post('/core/func/api/games/post/deleteServer.php', {
				csrf: csrf_token,
				serverID: serverID
			})
			.done(function(response) {
				if (response == "error") {
					$("#vStatus").html("<div class=\"alert alert-danger\">Could not delete this server because a network error occurred</div>")
				}else if (response == "success") {
					window.location = "/games";
				}
			})
			.fail(function() {
				$("#vStatus").html("<div class=\"alert alert-danger\">Could not delete this server because a network error occurred</div>");
			});
		}
	}
}