function changeGroupDescription(groupId) {
	if ($("#changeDescription").is(":disabled") == false) {
		$("#changeDescription").prop("disabled", true);
		$("#descriptionValue").prop("disabled", true);
		
		var descriptionValue = $("#descriptionValue").val();
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		$.post('/core/func/api/groups/post/changeDescription.php', {
			descriptionValue: descriptionValue,
			csrf: csrf_token,
			groupId: groupId
		})
		.done(function(response) {
			$("#changeDescription").prop("disabled", false);
			$("#descriptionValue").prop("disabled", false);
			if (response == "error") {
				$("#aStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
			}else if (response == "description-too-long") {
				$("#aStatus").html("<div class=\"alert alert-danger\">Your description is too long.</div>");
			}else if (response == "success") {
				$("#aStatus").html("<div class=\"alert alert-success\">Changed Group Description!</div>");
			}
		})
		.fail(function() {
			$("#aStatus").html("<div class=\"alert alert-danger\">Network error. Try again later.</div>");
		});
	}
}