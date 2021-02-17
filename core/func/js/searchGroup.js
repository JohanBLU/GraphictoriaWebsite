$(document).ready(function() {
	$("#doSearch_2").click(function() {
		var searchValue = $("#searchValue_2").val()
		$.get("/core/func/api/groups/searchGroup.php?term=" + searchValue + "&page=0", function(data) {
			$("#searchResults").html(data);
		});
	})
	
	$("#doSearch_2").click();
});

function loadMore(page, term) {
	$(".searchGroup").remove();
	$.get("/core/func/api/groups/searchGroup.php?term=" + term + "&page=" + page, function(data) {
		$("#searchResults").append(data);
	});
}