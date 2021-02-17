var lastMessageResponse;
var getMessages = null;
var getTypingThread = null;
var dChatOpen = false;
var lastTimestamp = 0;
var isTyping = false;
var infoOpen = false;
var chatMemberCount;
var lastMessageID = 0;
var ackIds = [];

$(function() {
	$(".loadChatList").click(function() {
		loadChatList();
		removeChatInfoBar();
	})
	$(".chatOpener").click(function() {
		$(".chatContainer").empty();
		$(".chatContainer").html("Loading...");
		if (dChatOpen == false) {
			dChatOpen = true;
			$(this).css("top", "calc(100% - 371px)");
			$(".chatOpenbackground").css("display", "block");
			$(".chatOptions").css("display", "block");
			loadChatList();
		}else{
			dChatOpen = false;
			$(this).css("top", "calc(100% - 24px)");
			$(".chatOpenbackground").css("display", "none");
			$(".chatOptions").css("display", "none");
			$(".chatOpener").html("Chat");
			stopIntervals();
		}
	})
	
	if ($(".mobileView").css("display") == "block") {
		$(".backandtext").css("top", $(".navbar").css("height"))
	}
});

function stopIntervals() {
	for (var i = 1; i < 9999; i++) {
        window.clearInterval(i);
		getMessages = undefined;
	}
}

function loadChatList() {
	clean();
	if ($(".mobileView").css("display") == "block") {
		window.history.pushState("", "", "/chat");
		var appContainer = $("#appContainer");
		appContainer.load("/core/func/views/app/user/chatlist.php", function() {
			$.get("/core/func/api/chat/getList.php", function(data) {
				var json = JSON.parse(data);
				$(".chatContainer").empty();
				for (var i = 0; i < json.length; i++) {
					var fHTML = json[i].chat_id;
					$(".chatContainer").append('<li class="list-group-item '+fHTML+'" onclick="loadChat(\'' + json[i].chatKey + '\', \'\', \'' + json[i].chat_id + '\');" style="border-radius:0px">' + json[i].chatName + '</li>');
				}
				if (data == "[]") {
					$(".chatContainer").append("<div class=\"center\">You are in no chats at the moment</div>");
				}
			});
		});
	}else{
		$(".chatOpener").html("Chat");
		$(".addChatBtn").css("display", "block");
		$(".chatOptions").html("");
		$.get("/core/func/api/chat/getList.php", function(data) {
			var json = JSON.parse(data);
			$(".chatContainer").empty();
			for (var i = 0; i < json.length; i++) {
				var fHTML = json[i].chat_id;
				$(".chatContainer").append('<li class="list-group-item '+fHTML+'" onclick="loadChat(\'' + json[i].chatKey + '\', \'\', \'' + json[i].chat_id + '\');" style="border-radius:0px">' + json[i].chatName + '</li>');
			}
			if (data == "[]") {
				$(".chatContainer").append("<div class=\"center\">You are in no chats at the moment</div>");
			}
		});
	}
}

function renderChatChoices() {
	$("#gModalTitle").html("Add Chat");
	$("#gModalBody").html("<p>Do you want to create a new chat or join one?</p>");
	$(".gModalErrorContainer").html("");
	$("#gModalBody").append("<button class=\"btn btn-primary rightMargin\" onclick=\"renderCreateChat();\">Create a Chat</button>");
	$("#gModalBody").append("<button class=\"btn btn-primary\" onclick=\"renderJoinChat();\">Join a Chat</button>");
	$("#globalModal2").modal('show');
}

function renderCreateChat() {
	$("#gModalTitle").html("Create Chat");
	$("#gModalBody").html("<p>Give your chat a name and press create!</p>");
	$(".gModalErrorContainer").html("");
	$("#gModalBody").append("<input type=\"text\" placeholder=\"Chat name\" class=\"form-control authField create_chat_name\"></input>");
	$("#gModalBody").append("<button class=\"btn btn-success create_chat\" onclick=\"createChat();\">Create</button>");
}

function renderJoinChat() {
	$("#gModalTitle").html("Join Chat");
	$("#gModalBody").html("<p>Enter a Chat Code and join a chat</p>");
	$(".gModalErrorContainer").html("");
	$("#gModalBody").append("<input type=\"text\" placeholder=\"Chat Code\" class=\"form-control authField join_code\"></input>");
	$("#gModalBody").append("<button class=\"btn btn-success join_chat\" onclick=\"joinChat();\">Join</button>");
}

function createChat() {
	$(".create_chat_name").prop("disabled", true);
	$(".create_chat").prop("disabled", true);
	var chatName = $(".create_chat_name").val();
	$.post('/core/func/api/chat/createChat.php', {
		chatName: chatName,
		csrfToken: $('meta[name="csrf-token"]').attr('content')
	})
	.done(function(response) {
		$(".create_chat_name").prop("disabled", false);
		$(".create_chat").prop("disabled", false);
		if (response == "no-name") {
			$(".gModalErrorContainer").html('<div class="alert alert-danger gModalError">You must have a chat name</div>');
		}else if (response == "chat-name-too-long") {
			$(".gModalErrorContainer").html('<div class="alert alert-danger gModalError">Your chat name is too long</div>');
		}else if (response == "rate-limit") {
			$(".gModalErrorContainer").html('<div class="alert alert-danger gModalError">Please wait before making another chat</div>');
		}else{
			if ($(".mobileView").css("display") == "none") {
				loadChatList();
			}
			var json = JSON.parse(response);
			for (var i = 0; i < json.length; i++) {
				loadChat(json[i].chatKey, json[i].chatName, json[i].chat_id);
			}
			$("#globalModal2").modal('hide');
		}
	})
	.fail(function() {
		$(".gModalErrorContainer").html('<div class="alert alert-danger gModalError">Could not create a chat because a network error occurred</div>');
	});
}

function joinChat() {
	$(".join_code").prop("disabled", true);
	$(".join_chat").prop("disabled", true);
	var chatCode = $(".join_code").val();
	$.post('/core/func/api/chat/joinChat.php', {
		chatCode: chatCode,
		csrfToken: $('meta[name="csrf-token"]').attr('content')
	})
	.done(function(response) {
		$(".join_code").prop("disabled", false);
		$(".join_chat").prop("disabled", false);
		if (response == "no-code") {
			$(".gModalErrorContainer").html('<div class="alert alert-danger gModalError">Please enter a chat code</div>');
		}else if (response == "chat-code-too-long") {
			$(".gModalErrorContainer").html('<div class="alert alert-danger gModalError">The chat code you entered is too long</div>');
		}else if (response == "invalid-code") {
			$(".gModalErrorContainer").html('<div class="alert alert-danger gModalError">You have entered an invalid chat code</div>');
		}else if (response == "already-in") {
			$(".gModalErrorContainer").html('<div class="alert alert-danger gModalError">You are already in this chat</div>');
		}else{
			if ($(".mobileView").css("display") == "none") {
				loadChatList();
			}
			var json = JSON.parse(response);
			for (var i = 0; i < json.length; i++) {
				loadChat(json[i].chatKey, json[i].chatName, json[i].chat_id);
			}
			$("#globalModal2").modal('hide');
		}
	})
	.fail(function() {
		$(".gModalErrorContainer").html('<div class="alert alert-danger gModalError">Could not join the chat because a network error occurred</div>');
	});
}

function clean() {
	infoOpen = false;
	stopIntervals();
	lastMessageResponse = undefined;
	lastTimestamp = 0;
}

function sendTyping(chatId) {
	if (!isTyping) {
		isTyping = true;
		$.post('/core/func/api/chat/sendTyping.php', {
			chatId: chatId,
			csrfToken: $('meta[name="csrf-token"]').attr('content')
		}).fail(function() {
			console.log("Error sending typing status");
		});
		setTimeout(function() {
			isTyping = false;
		}, 3000);
	}
}


function executeAsync(func) {
	setTimeout(func, 0);
}

function chatInfo(chatKey, chatName, chatId) {
	if (infoOpen) {
		infoOpen = false;
		loadChat(chatKey, chatName, chatId);
	}else{
		clean();
		infoOpen = true;
		console.log("Got chat info");
		$(".backandtext").html("Chat information");
		$(".chatContainer").empty();
	}
}

function loadChat(chatKey, chatName, chatId) {
	ackIds = [];
	if ($(".mobileView").css("display") == "none") {
		$(".list-group-item").css("background-color", "#fff");
		$("."+chatId).css("background-color", "#ddd");
		$(".chatOptions").html('<button type="button" style="padding: .1rem .1rem;border-radius: .1rem" onclick="loadChatList();" class="btn btn-secondary btn-sm">Back</button>');
	}
	clean();
	$(".chatContainer").html('<div class="container messageContainer" style="width:225px"></div><div class="container loadContainer"></div><div style="margin-top:40px"></div><input type="text" class="form-control" style="border-radius:0px" maxlength="1024" id="chatMessageBox" onkeydown="sendTyping('+chatId+')"/><button class="btn" style="border-radius:0px" id="sendChatButton" onclick="sendMessageInChat(\'' + chatId + '\', \'' + chatKey + '\');"><span class="fa fa-paper-plane"></span></button>');
		
	$("#chatMessageBox").css("width", "215px");
	$("#sendChatButton").css("margin-left", "215px").css("width", "41px")
	$(".addChatBtn").css("display", "none");
	
	$('#chatMessageBox').keypress(function (e) {
		var key = e.which;
		if(key == 13)  {
			$('#sendChatButton').click();
		}
	});
	
	$(window).resize(function() {
		if ($(".mobileView").css("display") == "block") {
			$(window).scrollTop($(document).height());
		}
	});
	
	$.get("/core/func/api/chat/getChatInfo.php?id=" + chatId, function(data) {
		if (data == "error" || data == "[]") {
			loadChatList();
			throw new Error("An error occurred");
		}
		var json = JSON.parse(data);
		for (var i = 0; i < json.length; i++) {
			chatMemberCount = json[i].chatMembers;
			if (chatMemberCount == 1) {
				var bText = "member";
			}else{
				var bText = "members";
			}
			chatJoinKey = json[i].joinKey;
			$(".chatOpener").html(json[i].chatName);
		}
		
		if ($(".mobileView").css("display") == "block") {
			$(".chatBreaker").empty();
			times = 0;
			while(times < $(".backandtext").height()/24) {
				$(".chatBreaker").append("<br>");
				times++;
			}
		}
		
		executeAsync(loadMessagesInChat(chatKey, chatId));
		executeAsync(getTyping(chatId));
		getMessages = setInterval(function() { 
			loadMessagesInChat(chatKey, chatId);
		}, 1000);
		
		getTypingThread = setInterval(function() {
			getTyping(chatId);
		}, 3000);
		$(".messageContainer").css("opacity", 0);
		$(".loadContainer").append('<div id="loadingChat"><div class="bottomMargin"></div><div class="center"><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i></div></div>').hide();
	}).fail(function() {
		loadChat(chatKey, chatName, chatId);
	});
}

function getTyping(chatId) {
	$.get("/core/func/api/chat/getTyping.php?chatId=" + chatId, function(data) {
		var json = JSON.parse(data);
		if (chatMemberCount == 1) {
			var bText = "member";
		}else{
			var bText = "members";
		}
		//if ($(".mobileView").css("display") == "block") {
			if (json.mode == "none") {
				$(".chatOptions").html(chatMemberCount + " " + bText + " [Invite code: " + chatJoinKey + "]");
			}
			if (json.mode == "showTyping") {
				var username1 = json.usernames[0];
				var username2 = json.usernames[1];
				var arrayLength = json.usernames.length;
				if (arrayLength == 1) {
					$(".chatOptions").html(username1 + " is typing...").css("position", "fixed");
				}else{
					$(".chatOptions").html(username1 + " and " + username2 + " are typing...");
				}
			}
			if (json.mode == "several") {
				$(".chatOptions").html("Several people are typing");
			}
		//}
	});
}

function parseDate(date) {
	var dateMessage = new Date(date*1000);
	var dateCurrent = new Date();
	var dayMessageNum = dateMessage.getDate();
	var yearMessage = dateMessage.getFullYear();
	var monthMessage = dateMessage.getMonth();
	
	var dateCurrentNum = dateCurrent.getDate();
	var currentYear = dateCurrent.getFullYear();
	var currentMonth = dateCurrent.getMonth();
	var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
	var dayOfWeekMessage = days[dateMessage.getDay()]
	var dayOfWeekCurrent = days[dateCurrent.getDay()]
	dateCurrent.setDate(dateCurrent.getDate() - 1);
	var dayYesterday = days[dateCurrent.getDay()];
	if (dayOfWeekCurrent == dayOfWeekMessage && dayMessageNum == dateCurrentNum && yearMessage == currentYear && monthMessage == currentMonth) {
		date = intlDate2.format(new Date(1000*date));
	}else if (dayOfWeekMessage == dayYesterday && dayMessageNum == dateCurrentNum-1 && yearMessage == currentYear && monthMessage == currentMonth) {
		date = "yesterday at " + intlDate2.format(new Date(1000*date));
	}else{
		date = intlDate.format(new Date(1000*date));
		date = date.replace("M01", "Jan");
		date = date.replace("M02", "Feb");
		date = date.replace("M03", "Mar");
		date = date.replace("M04", "Apr");
		date = date.replace("M05", "May");
		date = date.replace("M06", "Jun");
		date = date.replace("M07", "Jul");
		date = date.replace("M08", "Aug");
		date = date.replace("M09", "Sep");
		date = date.replace("M10", "Oct");
		date = date.replace("M11", "Nov");
		date = date.replace("M12", "Dec");
	}
	return date;
}

function loadMessagesInChat(chatKey, chatId) {
	$.get("/core/func/api/chat/getMessages.php?id=" + chatId + "&timestamp=" + lastTimestamp, function(data) {
		if (data == "error") {
			loadChatList();
			throw new Error("An error occurred");
		}
		if (lastMessageResponse != data) {
			if ($(".messageContainer").css("opacity") == 0) {
				$("#loadContainer").empty();
				$(".messageContainer").animate({opacity: 1});
			}
			var json = JSON.parse(data);
			if (data == "[]" && lastTimestamp == 0) {
				createMessage("Welcome to the beginning of this chat.", "GraphictoriaBot", "red", 2, undefined, true, false);
			}
			for (var i = 0; i < json.length; i++) {
				if (lastMessageID != json[i].messageId && ackIds.includes(json[i].messageId) == false) {
					lastMessageID = json[i].messageId;
					ackIds.push(json[i].messageId);
					lastTimestamp = Math.round((new Date()).getTime() / 1000);
					if (json[i].userId == 0) {
						createMessage(json[i].message, "Graphictoria", "red", 2, json[i].date, true, false, 0);
					}else{
						createMessage(json[i].message, json[i].username, json[i].userColor, json[i].staff, json[i].date, false, json[i].setRight, json[i].userID);
					}
					if (lastMessageResponse != undefined && data != "[]" && json[i].setRight == false) {
						console.log("New message");
						var receiveSound = new Audio('/core/func/api/chat/sounds/send.mp3')
						receiveSound.play();
					}
				}else{
					console.log("Caught duplicate message");
				}
			}
			if (data != "[]") {
				if ($(".mobileView").css("display") == "block") {
					$(window).scrollTop($(document).height());
				}else{
					$(".chatOpenbackground").scrollTop($(".chatOpenbackground")[0].scrollHeight);
				}
			}
		}
		lastMessageResponse = data;
	});
}

var options = {
	weekday: 'long',
	month: 'short',
	year: 'numeric',
	day: 'numeric',
	hour: 'numeric',
	minute: 'numeric'
},intlDate = new Intl.DateTimeFormat(undefined, options);		
	
var options2 = {
	hour: 'numeric',
	minute: 'numeric'
},intlDate2 = new Intl.DateTimeFormat(undefined, options2);

function createMessage(message, sender, cColor, tagId, date, doCenter, setRight, userID) {
	var color = cColor;
	var cHTML = "";
	if (doCenter == true) {
		cHTML = "center";
	}
	
	var sHTML = '<span onclick="loadProfile('+userID+');">' + sender + '</span>';
	
	if (setRight == false) {
		if (tagId == 1) {
			var uHTML = '<span class="badge badge-pill badge-primary"><span class="fa fa-gavel"></span></span> ' + sHTML + '<span style="color:grey">:</span>';
		}else if (tagId == 2) {
			var uHTML = '<span class="badge badge-pill badge-primary">BOT</span> ' + sender + '<span style="color:grey">:</span>';
		}else{
			var uHTML = sHTML + '<span style="color:grey">:</span>';
		}
	}else{
		var uHTML = "";
	}
	
	if (setRight == true) {
		var style = "style=\"float:right;max-width:100%\"";
	}else{
		var style = "style=\"width:100%\"";
	}
	
	if (date == undefined) {
		date = "";
	}else{
		date = parseDate(date);
	}
	$(".messageContainer").append('<pre class="cMessage" style="margin-bottom:0px;padding:0px;background-color:rgba(245, 245, 245, 0);border:none;font-size:9px"><div class="'+ cHTML+'" '+style+'><span class="badge badge-pill chatMessage"><span style="color: '+color+';"><b>' + uHTML + '</b> </span> <font color="black" style="font-size:120%">' + message + '</font> <span class="chatDate"> ' + date + '</span></span></div></pre>');
}

function sendMessageInChat(chatId, chatKey) {
	$("#sendChatButton").prop("disabled", true);
	$("#chatMessageBox").prop("disabled", true);
	var chatMessage = $("#chatMessageBox").val();
	$("#chatMessageBox").val("");
	$.post('/core/func/api/chat/sendMessage.php', {
		message: chatMessage,
		chatId: chatId,
		csrfToken: $('meta[name="csrf-token"]').attr('content')
	})
	.done(function(response) {
		$("#sendChatButton").prop("disabled", false);
		$("#chatMessageBox").prop("disabled", false);
		$("#chatMessageBox").focus();
		if (response == "message-too-short") {
			$("#chatMessageBox").css("border", "1px solid #d9534f");
		}else if (response == "message-too-long") {
			$("#chatMessageBox").css("border", "1px solid #d9534f");
		}else{
			$("#chatMessageBox").css("border", "0px solid #F44336");
		}
	})
	.fail(function() {
		console.log("Error while sending message");
		$("#chatMessageBox").css("border", "0px solid #F44336");
	});
}