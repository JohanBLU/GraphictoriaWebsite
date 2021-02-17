// EnergyCell
var page = 0;
function loadForum(id) {
	if ($(window).width() > 767) {
		$(".adContainer").css("display", "block");
	}
	$("#posts").html('<br><br><br><div class="center"><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i></div>');
	window.history.pushState("", "", '/forum-'+id);
	$("html, body").animate({ scrollTop: 0 }, "fast");
	$("#posts").load("/core/func/api/forum/getPosts.php?id=" + id, function() {
		if ($("#posts").height() < 1395) {
			$(".adContainer").css("display", "none");
		}else{
			$(".adContainer").css("display", "block");
		}
		$("#posts").hide().fadeIn('fast');
	})
	page = 0;
}

function loadMiniProfile(userID) {
	$(".modalUsername").html("");
	$(".miniProfileContent").html('<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i></div>');
	$(".miniProfileContent").load("/core/func/api/forum/getMiniProfile.php?id=" + userID);
	$('.miniProfile').modal('show').hide().fadeIn('fast');
}

function search(forumID) {
	$(".modalUsername").html("");
	$(".miniProfileContent").html('<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i></div>');
	$(".miniProfileContent").load("/core/func/api/forum/doSearch.php?id=" + forumID);
	$('.miniProfile').modal('show').hide().fadeIn('fast');
}

function doSearch(forumID) {
	var keyword = $("#searchboxValue").val();
	if (keyword.length == 0) {
		$("#searchError").html('<span style="color:red">Please enter something</span>');
	}else{
		$('.miniProfile').modal('hide');
		$("#posts").html('<br><br><br><div class="center"><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i></div>');
		$("html, body").animate({ scrollTop: 0 }, "fast");
		$("#posts").load("/core/func/api/forum/getPosts.php?id=" + forumID + "&keyword=" + encodeURIComponent(keyword), function() {
			if ($("#posts").height() < 1395) {
				$(".adContainer").css("display", "none");
			}else{
				$(".adContainer").css("display", "block");
			}
			$("#posts").hide().fadeIn('fast');
		})
		page = 0;
	}
}

function loadPost(id) {
	$(".adContainer").css("display", "none");
	$("#posts").html('<br><br><br><div class="center"><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i></div>');
	window.history.pushState("", "", '/forum+'+id);
	$("html, body").animate({ scrollTop: 0 }, "fast");
	$("#posts").load("/core/func/api/forum/showPost.php?id=" + id, function() {
		$("#posts").hide().fadeIn('fast');
		if ($("#posts").height() < 1395) {
			$(".adContainer").css("display", "none");
		}else{
			$(".adContainer").css("display", "block");
		}
	})
	page = 0;
}
function loadMore(page, postId) {
	$(".loadMore").prop("disabled", true);
	$.get("/core/func/api/forum/showPost.php?id=" + postId + "&page=" + page, function(data) {
		$(".loadMore").remove();
		$("#posts").append(data);
	});
}
			
function loadMoreForum(page, forumId) {
	$(".loadMore").prop("disabled", true);
	$.get("/core/func/api/forum/getPosts.php?id=" + forumId + "&page=" + page, function(data) {
		$(".loadMore").remove();
		$("#posts").append(data);
	});
}

function loadMoreForumSearch(page, forumId, keyword) {
	$(".loadMore").prop("disabled", true);
	$.get("/core/func/api/forum/getPosts.php?id=" + forumId + "&page=" + page + "&keyword=" + keyword, function(data) {
		$(".loadMore").remove();
		$("#posts").append(data);
	});
}

function newPost(forumId) {
	$(".adContainer").css("display", "none");
	$("#posts").html('<br><br><br><div class="center"><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i></div>');
	$("#posts").load("/core/func/api/forum/newPost.php?id=" + forumId);
	$("html, body").animate({ scrollTop: 0 }, "fast");
}

function newReply(postId) {
	$(".adContainer").css("display", "none");
	$("#posts").html('<br><br><br><div class="center"><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i></div>');
	$("#posts").load("/core/func/api/forum/newReply.php?id=" + postId);
	$("html, body").animate({ scrollTop: 0 }, "fast");
}

function postMessage(forumId) {
	if ($("#postMessage").is(":disabled") == false) {
		$("#postMessage").prop("disabled", true);
		$("#postContent").prop("disabled", true);
		$("#postTitle").prop("disabled", true);
		
		var postTitle = $("#postTitle").val();
		var postContent = $("#postContent").val();
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		$.post('/core/func/api/forum/post/newPost.php', {
			postTitle: postTitle,
			postContent: postContent,
			csrf: csrf_token,
			forum: forumId
		})
		.done(function(response) {
			$("#postMessage").prop("disabled", false);
			$("#postContent").prop("disabled", false);
			$("#postTitle").prop("disabled", false);
			
			if (response == "error") {
				$("#pStatus").css("color", "red").html("Network error. Try again later.");
			}else if (response == "title-too-short") {
				$("#pStatus").css("color", "red").html("Your title is too short. Try something else.");
			}else if (response == "title-too-long") {
				$("#pStatus").css("color", "red").html("Your title is too long. Try something else.");
			}else if (response == "content-too-short") {
				$("#pStatus").css("color", "red").html("Your content is too short.");
			}else if (response == "content-too-long") {
				$("#pStatus").css("color", "red").html("Your content is too long.");
			}else if (response == "rate-limit") {
				$("#pStatus").css("color", "red").html("You are posting too fast. Please wait.");
			}else if (response == "account-age") {
				$("#pStatus").css("color", "red").html("Your account needs to be a day old to post.");
			}else if (response == "no-forum") {
				$("#pStatus").css("color", "red").html("The forum you are trying to post in does not exist.");
			}else if (response == "access-denied") {
				$("#pStatus").css("color", "red").html("You have no permission to post in this forum.");
			}else{
				$("#pStatus").html(response);
			}
		})
		.fail(function() {
			$("#pStatus").css("color", "red").html("Network error. Try again later.");
		});
	}
}

function postReply(postId) {
	if ($("#postReply").is(":disabled") == false) {
		$("#postReply").prop("disabled", true);
		$("#replyContent").prop("disabled", true);
		
		var replyContent = $("#replyContent").val();
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		$.post('/core/func/api/forum/post/newReply.php', {
			replyContent: replyContent,
			csrf: csrf_token,
			postId: postId
		})
		.done(function(response) {
			$("#postReply").prop("disabled", false);
			$("#replyContent").prop("disabled", false);
			
			if (response == "error") {
				$("#rStatus").css("color", "red").html("Network error. Try again later.");
			}else if (response == "content-too-short") {
				$("#rStatus").css("color", "red").html("Your reply is too short.");
			}else if (response == "content-too-long") {
				$("#rStatus").css("color", "red").html("Your reply is too long.");
			}else if (response == "rate-limit") {
				$("#rStatus").css("color", "red").html("You are posting too fast. Please wait.");
			}else if (response == "account-age") {
				$("#rStatus").css("color", "red").html("Your account must be at least a day old to use the forums.");
			}else if (response == "no-post") {
				$("#rStatus").css("color", "red").html("The post you are trying to reply to does not exist.");
			}else if (response == "access-denied") {
				$("#rStatus").css("color", "red").html("Access Denied.");
			}else{
				$("#rStatus").html(response);
			}
		})
		.fail(function() {
			$("#rStatus").css("color", "red").html("Network error. Try again later.");
		});
	}
}
			
function lockPost(postId) {
	if ($("#lockPost").is(":disabled") == false) {
		$("#lockPost").prop("disabled", true);
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		$.post('/core/func/api/forum/post/lockPost.php', {
			csrf: csrf_token,
			postId: postId
		})
		.done(function(response) {
			if (response == "error") {
				$("#pStatus").css("color", "red").html("Network error. Try again later.");
			}else{
				loadPost(postId);
			}
		})
		.fail(function() {
			$("#pStatus").css("color", "red").html("Network error. Try again later.");
		});
	}
}

function unlockPost(postId) {
	if ($("#unlockPost").is(":disabled") == false) {
		$("#unlockPost").prop("disabled", true);
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		$.post('/core/func/api/forum/post/unlockPost.php', {
			csrf: csrf_token,
			postId: postId
		})
		.done(function(response) {
			if (response == "error") {
				$("#pStatus").css("color", "red").html("Network error. Try again later.");
			}else{
				loadPost(postId);
			}
		})
		.fail(function() {
			$("#pStatus").css("color", "red").html("Network error. Try again later.");
		});
	}
}

function pinPost(postId) {
	if ($("#pinPost").is(":disabled") == false) {
		$("#pinPost").prop("disabled", true);
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		$.post('/core/func/api/forum/post/pinPost.php', {
			csrf: csrf_token,
			postId: postId
		})
		.done(function(response) {
			if (response == "error") {
				$("#pStatus").css("color", "red").html("Network error. Try again later.");
			}else{
				loadPost(postId);
			}
		})
		.fail(function() {
			$("#pStatus").css("color", "red").html("Network error. Try again later.");
		});
	}
}

function unpinPost(postId) {
	if ($("#unpinPost").is(":disabled") == false) {
		$("#unpinPost").prop("disabled", true);
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		$.post('/core/func/api/forum/post/unpinPost.php', {
			csrf: csrf_token,
			postId: postId
		})
		.done(function(response) {
			if (response == "error") {
				$("#pStatus").css("color", "red").html("Network error. Try again later.");
			}else{
				loadPost(postId);
			}
		})
		.fail(function() {
			$("#pStatus").css("color", "red").html("Network error. Try again later.");
		});
	}
}
			
function deletePost(postId, forumId) {
	if ($("#deletePost").is(":disabled") == false) {
		if ($("#deletePost").text() != "Are you sure?") {
			$("#deletePost").text("Are you sure?");
		}else{
			$("#deletePost").prop("disabled", true);
			$("#deletePost").text("Deleting Post...");
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.post('/core/func/api/forum/post/deletePost.php', {
				csrf: csrf_token,
				postId: postId
			})
			.done(function(response) {
				if (response == "error") {
					$("#pStatus").css("color", "red").html("Network error. Try again later.");
				}else{
					loadForum(forumId);
				}
			})
			.fail(function() {
				$("#pStatus").css("color", "red").html("Network error. Try again later.");
			});
		}
	}
}

$(document).ready(function() {
	$("#catagories").load("/core/func/api/forum/getCatagories.php");
	var load = false;
	var url = window.location.href;
	var n = url.indexOf("+");
	if (n > 0) {;
		loadPost(url.substring(n+1));
		load = true;
	}
	var n = url.indexOf("-");
	if (n > 0) {;
		loadForum(url.substring(n+1));
		load = true;
	}

	if (load == false) {
		loadForum(2);
	}
	
	$(window).on('popstate', function() {
		var load = false;
		var url = window.location.href;
		var n = url.indexOf("+");
		if (n > 0) {;
			loadPost(url.substring(n+1));
			load = true;
		}
		var n = url.indexOf("-");
		if (n > 0) {;
			loadForum(url.substring(n+1));
			load = true;
		}

		if (load == false) {
			loadForum(2);
		}
	})
});