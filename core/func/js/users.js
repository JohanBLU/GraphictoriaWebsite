$(document).ready(function() {
	$("#doSearch_2").click(function() {
		var searchValue = $("#searchValue_2").val()
		$.get("/core/func/api/users/searchUser.php?term=" + searchValue + "&page=0", function(data) {
			$("#searchResults").html(data);
		});
	})
	
	$("#doSearch_2").click();
});

function loadMore(page, term) {
	$(".searchUser").remove();
	$.get("/core/func/api/users/searchUser.php?term=" + term + "&page=" + page, function(data) {
		$("#searchResults").append(data);
	});
}