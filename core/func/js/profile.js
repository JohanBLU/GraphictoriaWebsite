$(document).ready(function() {
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

function switchTo(type) {
	type = type;
	$("#inventoryItems").load("/core/func/api/profile/getInventory.php?type=" + type + "&userId=" + userId);
}

function loadPage(type, page) {
	$("#inventoryItems").load("/core/func/api/profile/getInventory.php?type=" + type + "&userId=" + userId + "&page=" + page);
}
