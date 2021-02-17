function buyItem(itemId) {
	if ($("#buyItem").is(":disabled") == false) {
		$("#buyItem").prop("disabled", true);
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		$.post('/core/func/api/catalog/post/buyItem.php', {
			csrf: csrf_token,
			itemId: itemId
		})
		.done(function(response) {
			console.log(response);
			if (response == "error") {
				$("#iStatus").html("<div class=\"alert alert-danger\">Could not buy this item.</div>");
			}else{
				$("#iStatus").html("<div class=\"alert alert-success\">You have bought this item!</div>");
				$("#buyItem").text("Already owned");
				if (cType == "coins")
					$("#userCoins").html(response);
				if (cType == "posties")
					$("#userPosties").html(response);
			}
		})
		.fail(function() {
			$("#iStatus").html("<div class=\"alert alert-danger\">Could not buy this item because a network error occurred. Try again later.</div>");
		});
	}
}
			
function deleteItem(itemId) {
	if ($("#deleteItem").is(":disabled") == false) {
		$("#deleteItem").prop("disabled", true);
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		$.post('/core/func/api/catalog/post/deleteItem.php', {
			csrf: csrf_token,
			itemId: itemId
		})
		.done(function(response) {
			console.log(response);
			if (response == "error") {
				$("#iStatus").html("<div class=\"alert alert-danger\">Could not delete this item.</div>");
			}else if (response == "success") {
				window.location = "/catalog/";
			}else{
				$("#iStatus").html("<div class=\"alert alert-danger\">Could not delete this item because a network error occurred. Try again later.</div>");
			}
		})
		.fail(function() {
			$("#iStatus").html("<div class=\"alert alert-danger\">Could not delete this item because a network error occurred. Try again later.</div>");
		});
	}
}