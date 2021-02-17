var page = 0;
function loadMessages(filterId) {
	$("#messages").html('<br><br><br><div class="center"><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i></div>');
	$("#messages").load("/core/func/api/messages/getMessages.php?filter=" + filterId);
	$("html, body").animate({ scrollTop: 0 }, "slow");
	page = 0;
}

function loadMore(page, filterId) {
	$(".loadMore").remove();
	$.get("/core/func/api/messages/getMessages.php?filter=" + filterId + "&page=" + page, function(data) {
		$("#messages").append(data);
	});
}

function loadMessage(id) {
	$("#messages").html('<br><br><br><div class="center"><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i></div>');
	$("#messages").load("/core/func/api/messages/showMessage.php?id=" + id);
	$("html, body").animate({ scrollTop: 0 }, "slow");
	page = 0;
}

function sendMessage(username) {
	$("#messages").html('<br><br><br><div class="center"><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i></div>');
	$("#messages").load("/core/func/api/messages/newMessage.php?username=" + username);
}

function sendMessagePost(userID) {
	if ($("#sendMessage").is(":disabled") == false) {
		$("#sendMessage").prop("disabled", true);
		$("#messageContent").prop("disabled", true);
		$("#messageTitle").prop("disabled", true);
		
		var messageTitle = $("#messageTitle").val();
		var messageContent = $("#messageContent").val();
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		$.post('/core/func/api/messages/post/newMessage.php', {
			messageTitle: messageTitle,
			messageContent: messageContent,
			csrf: csrf_token,
			userID: userID
		})
		.done(function(response) {
			$("#sendMessage").prop("disabled", false);
			$("#messageContent").prop("disabled", false);
			$("#messageTitle").prop("disabled", false);
			if (response == "error") {
				$("#pStatus").css("color", "red").html("Network error. Try again later.");
			}else if (response == "title-too-short") {
				$("#pStatus").css("color", "red").html("Your title is too short.");
			}else if (response == "title-too-long") {
				$("#pStatus").css("color", "red").html("Your title is too long.");
			}else if (response == "content-too-short") {
				$("#pStatus").css("color", "red").html("Your message is too short.");
			}else if (response == "content-too-long") {
				$("#pStatus").css("color", "red").html("Your message is too long.");
			}else if (response == "no-user") {
				$("#pStatus").css("color", "red").html("The user you are sending a message to does not exist.");
			}else if (response == "no-banned") {
				$("#pStatus").css("color", "red").html("The user you are sending a message to has been banned.");
			}else if (response == "no-banned") {
				$("#pStatus").css("color", "red").html("The user you are sending a message to has been banned.");
			}else if (response == "success") {
				loadMessages(0);
				window.history.pushState("", "", '/user/messages');
				$("#mStatus").html("<div class=\"alert alert-success\">Your message has been sent!</div>");
			}
		})
		.fail(function() {
			$("#pStatus").css("color", "red").html("Network error. Try again later.");
		});
	}
}

$(document).ready(function() {
	var load = false;
	var url = window.location.href;
	var n = url.indexOf("+");
	if (n > 0) {;
		sendMessage(url.substring(n+1));
		load = true;
	}
	
	if (load == false) {
		loadMessages(0);
	}
	$("#allMessages").click(function() {
		console.log("All Messages");
		loadMessages(0);
	})
	
	$("#unreadMessages").click(function() {
		console.log("Unread Messages");
		loadMessages(1);
	})
	
	$("#readMessages").click(function() {
		console.log("Read Messages");
		loadMessages(2);
	})
	
	$("#sentOnly").click(function() {
		console.log("Sent Only");
		loadMessages(3);
	})
});