$(document).ready(function() {
	var type = "hats";
	var page = 0;
	$("#itemsContainer").load("/core/func/api/catalog/getItems.php?type=hats&page=0");
	
	$("#typeHats").click(function() {
		switchType("hats");
	});
	
	$("#typeHeads").click(function() {
		switchType("heads");
	});
	
	$("#typeFaces").click(function() {
		switchType("faces");
	});
	
	$("#typetshirts").click(function() {
		switchType("tshirts");
	});
	
	$("#typeshirts").click(function() {
		switchType("shirts");
	});
	
	$("#typepants").click(function() {
		switchType("pants");
	});
	
	$("#typeGear").click(function() {
		switchType("gear");
	});
	
	$("#typeDecals").click(function() {
		switchType("decals");
	});
});

function switchType(type) {
	page = 0;
	type = type;
	$("#itemsContainer").load("/core/func/api/catalog/getItems.php?type=" + type + "&page=0");
}

function searchItem() {
	var itemName = $("#searchCValue").val();
	if (itemName.length == 0) {
		$('#errorModal').modal({ show: true});
	}else{
		$("#itemsContainer").load("/core/func/api/catalog/getItems.php?type=" + type + "&page=" +page+"&term=" + itemName);
	}
}