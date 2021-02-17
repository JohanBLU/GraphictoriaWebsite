$(document).ready(function() {
	loadMain();
});

function loadPost(postID) {
	if (postID != undefined) {
		$("#newPostBtn").css("display", "none");
		$.get("/core/func/api/blog/loadPost.php?id=" + postID, function(data) {
			$("#postContainer").html(data);
		})
	}
}

function loadMain() {
	$.get("/core/func/api/blog/getPosts.php", function(data) {
		$("#newPostBtn").css("display", "block");
		$("#title").html("Graphictoria Blog");
		$("#postContainer").html(data);
	})
}

function loadNew() {
	$("#newPostBtn").css("display", "none");
	$.get("/core/func/api/blog/addPost.php", function(data) {
		$("#title").html("New Post");
		$("#postContainer").html(data);
	})
}