$(document).ready(function() {
	$("#createGroup").click(function() {
		if ($("#createGroup").is(":disabled") == false) {
			$("#createGroup").prop("disabled", true);
			$("#groupName").prop("disabled", true);
			$("#groupDescription").prop("disabled", true);
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			var groupName = $("#groupName").val();
			var groupDescription = $("#groupDescription").val();
			$.post('/core/func/api/groups/post/createGroup.php', {
				csrf: csrf_token,
				groupName: groupName,
				groupDescription: groupDescription
			})
			.done(function(response) {
				$("#createGroup").prop("disabled", false);
				$("#groupName").prop("disabled", false);
				$("#groupDescription").prop("disabled", false);
				if (response == "error") {
					$("#gStatus").html("<div class=\"alert alert-danger\">Could not create a group because a network error has occurred.</div>");
				}else if (response == "no-name") {
					$("#gStatus").html("<div class=\"alert alert-danger\">Please specify a group name</div>");
				}else if (response == "group-name-too-short") {
					$("#gStatus").html("<div class=\"alert alert-danger\">Your group name is too short</div>");
				}else if (response == "group-name-too-long") {
					$("#gStatus").html("<div class=\"alert alert-danger\">Your group name is too long</div>");
				}else if (response == "description-too-long") {
					$("#gStatus").html("<div class=\"alert alert-danger\">Your group description is too long</div>");
				}else if (response == "no-coins") {
					$("#gStatus").html("<div class=\"alert alert-danger\">You do not have enough coins to create a group</div>");
				}else if (response == "in-too-many-groups") {
					$("#gStatus").html("<div class=\"alert alert-danger\">You are in too many groups</div>");
				}else{
					window.location = "/groups/view/"+response;
				}
			})
			.fail(function() {
				$("#gStatus").html("<div class=\"alert alert-danger\">Could not create a group because a network error has occurred.</div>");
			});
		}
	})
})