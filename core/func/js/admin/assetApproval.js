$(document).ready(function() {
	console.log("Asset approval ready!");
	$("#assetContainer").load("/core/func/api/admin/get/getAssets.php");
});

function approveAsset(itemID) {
	$(".btn-success").prop("disabled", true);
	$(".btn-danger").prop("disabled", true);
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.post('/core/func/api/admin/post/approveAsset.php', {
		csrf: csrf_token,
		itemID: itemID,
		})
	.done(function(response) {
		console.log(response);
		if (response == "success") {
			$("#assetContainer").load("/core/func/api/admin/get/getAssets.php");
		}else{
			alert("Could not approve this asset because an error occurred." + response);
		}
	})
	.fail(function() {
		alert("An error occurred while reaching the Graphictoria servers. Please contact a developer.");
	});
}

function denyAsset(itemID) {
	$(".btn-success").prop("disabled", true);
	$(".btn-danger").prop("disabled", true);
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.post('/core/func/api/admin/post/denyAsset.php', {
		csrf: csrf_token,
		itemID: itemID,
	})
	.done(function(response) {
		console.log(response);
		if (response == "success") {
			$("#assetContainer").load("/core/func/api/admin/get/getAssets.php");
		}else{
			alert("Could not deny this asset because an error occurred." + response);
		}
	})
	.fail(function() {
		alert("An error occurred while reaching the Graphictoria servers. Please contact a developer.");
	});
}