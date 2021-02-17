function postPose(pose) {
	if (pose == "walking") {
		$("#poseStatus").html("<div class=\"alert alert-success\">Changed pose to walking</div>");
	}
	if (pose == "sitting") {
		$("#poseStatus").html("<div class=\"alert alert-success\">Changed pose to sitting</div>");
	}
	if (pose == "normal") {
		$("#poseStatus").html("<div class=\"alert alert-success\">Changed pose to normal</div>");
	}
	if (pose == "overlord") {
		$("#poseStatus").html("<div class=\"alert alert-success\">Changed pose to overlord</div>");
	}
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	var pose = pose;
	$.post('/core/func/api/character/post/changePose.php', {
		csrf: csrf_token,
		pose: pose
	})
}

$(document).ready(function() {
	$("#normal").click(function() {
		postPose("normal");
	})

	$("#walking").click(function() {
		postPose("walking");
	})

	$("#sitting").click(function() {
		postPose("sitting");
	})
	
	$("#overlord").click(function() {
		postPose("overlord");
	})

	$("#regen").click(function() {
		if ($("#regen").is(":disabled") == false) {
			$("#regen").remove();
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.post('/core/func/api/character/post/regenCharacter.php', {
				csrf: csrf_token
			})
			$("#poseStatus").html("<div class=\"alert alert-success\">Regenerating character...</div>");
		}
	})


	switchTo("hats");

	$("#showHats").click(function() {
		switchTo("hats");
	})

	$("#showHeads").click(function() {
		switchTo("heads");
	})

	$("#showFaces").click(function() {
		switchTo("faces");
	})

	$("#showTshirts").click(function() {
		switchTo("tshirts");
	})

	$("#showShirts").click(function() {
		switchTo("shirts");
	})

	$("#showPants").click(function() {
		switchTo("pants");
	})

	$("#showGear").click(function() {
		switchTo("gear");
	})
});

function startGeneration(userID) {
	setInterval(function() {
	var characterElement = document.getElementById('character');
		characterElement.src = 'https://api.xdiscuss.net/imageServer/user/'+userID+'.png?tick=' + Math.random();
	}, 5000);
}

function switchTo(type) {
	type = type;
	$("#inventoryItems").load("/core/func/api/character/getInventory.php?type=" + type);
	$("#wearing").load("/core/func/api/character/getWearing.php?type=" + type);
}

function loadPage(type, page) {
	$("#inventoryItems").load("/core/func/api/character/getInventory.php?type=" + type + "&page=" + page);
}

function wearItem(itemId, type, page) {
	if ($(".wearItem").is(":disabled") == false) {
		$(".wearItem").prop("disabled", true);
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		$.post('/core/func/api/character/post/wearItem.php', {
			csrf: csrf_token,
			itemId: itemId,
			type: type
		})
		.done(function() {
			$("#inventoryItems").load("/core/func/api/character/getInventory.php?type=" + type + "&page=" + page);
			$("#wearing").load("/core/func/api/character/getWearing.php?type=" + type);
		})
	}
}

function removeItem(itemId, type, page) {
	if ($(".removeItem").is(":disabled") == false) {
		$(".removeItem").prop("disabled", true);
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		$.post('/core/func/api/character/post/removeItem.php', {
			csrf: csrf_token,
			itemId: itemId
		})
		.done(function() {
			$("#inventoryItems").load("/core/func/api/character/getInventory.php?type=" + type + "&page=" + page);
			$("#wearing").load("/core/func/api/character/getWearing.php?type=" + type);
		})
	}
}
