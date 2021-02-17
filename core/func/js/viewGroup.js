function getMembers(groupId, page) {
	$("#memberField").load("/core/func/api/groups/get/getMembers.php?gid=" + groupId + "&page=" + page);
}

function leaveDelete(groupId) {
	if ($("#leaveDelete").is(":disabled") == false) {
		if ($("#leaveDelete").text() != "Are you sure?") {
			$("#leaveDelete").text("Are you sure?");
		}else{
			$("#leaveDelete").prop("disabled", true);
			$("#leaveDelete").text("Leaving and deleting your group...");
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.post('/core/func/api/groups/post/leaveDelete.php', {
				csrf: csrf_token,
				groupId: groupId
			})
			.done(function(response) {
				if (response == "error") {
					$("#gStatus").html("<div class=\"alert alert-danger\">Could not leave your group because a network error occurred.</div>");
				}else{
					window.location = "/groups";
				}
			})
			.fail(function() {
				$("#gStatus").html("<div class=\"alert alert-danger\">Could not leave your group because a network error occurred.</div>");
			});
		}
	}
}

function leaveGroup(groupId) {
	if ($("#leaveGroup").is(":disabled") == false) {
		if ($("#leaveGroup").text() != "Are you sure?") {
			$("#leaveGroup").text("Are you sure?");
		}else{
			$("#leaveGroup").prop("disabled", true);
			$("#leaveGroup").text("Leaving group...");
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.post('/core/func/api/groups/post/leaveGroup.php', {
				csrf: csrf_token,
				groupId: groupId
			})
			.done(function(response) {
				if (response == "error") {
					$("#gStatus").html("<div class=\"alert alert-danger\">Could not leave your group because a network error occurred.</div>");
				}else{
					$("#leaveGroup").remove();
					getMembers(groupId, 0);
				}
			})
			.fail(function() {
				$("#gStatus").html("<div class=\"alert alert-danger\">Could not leave your group because a network error occurred.</div>");
			});
		}
	}
}

function joinGroup(groupId) {
	if ($("#joinGroup").is(":disabled") == false) {
		$("#joinGroup").prop("disabled", true);
		$("#joinGroup").text("Joining group...");
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		$.post('/core/func/api/groups/post/joinGroup.php', {
			csrf: csrf_token,
			groupId: groupId
		})
		.done(function(response) {
			if (response == "error") {
				$("#gStatus").html("<div class=\"alert alert-danger\">Could not join this group because a network error occurred.</div>");
			}else if (response == "in-too-many-groups") {
				$("#gStatus").html("<div class=\"alert alert-danger\">You are in too many groups.</div>");
			}else{
				$("#joinGroup").remove();
				getMembers(groupId, 0);
			}
		})
		.fail(function() {
			$("#gStatus").html("<div class=\"alert alert-danger\">Could not join this group because a network error occurred.</div>");
		});
	}
}