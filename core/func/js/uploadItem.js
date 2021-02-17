$(document).ready(function() {
	$(document).on('change', '#itemTypeValue', function() {
		if ($(this).find("option:selected").attr('value') == 3) {
			$("#itempriceContainer").css("display", "none");
		}else{
			$("#itempriceContainer").css("display", "block");
		}
	})
	
	$("#uploadItem").click(function() {
		if ($("#uploadItem").is(":disabled") == false) {
			$("#uploadItem").prop("disabled", true);
			$("#itemNameValue").prop("disabled", true);
			$("#itemDescriptionValue").prop("disabled", true);
			$("#itemTypeValue").prop("disabled", true);
			$("#itemPriceValue").prop("disabled", true);
			$("#fileValue").prop("disabled", true);
			
			var formData = new FormData();
			formData.append('file', $('#fileValue')[0].files[0]);
			formData.append('itemName', $("#itemNameValue").val());
			formData.append('itemDescription', $("#itemDescriptionValue").val());
			formData.append('itemType', $("#itemTypeValue").val());
			formData.append('itemPrice', $("#itemPriceValue").val());
			formData.append('csrf_token', $('meta[name="csrf-token"]').attr('content'));
			$.ajax({
				type: "POST",
				url : "/core/func/api/catalog/post/uploadItem.php",
				data : formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data) {
					console.log(data);
					$("#uploadItem").prop("disabled", false);
					$("#itemNameValue").prop("disabled", false);
					$("#itemDescriptionValue").prop("disabled", false);
					$("#itemTypeValue").prop("disabled", false);
					$("#itemPriceValue").prop("disabled", false);
					$("#fileValue").prop("disabled", false);
					
					if (data == "error") {
						$("#uploadStatus").html("<div class=\"alert alert-danger\">Could not upload item because a network error occurred.</div>");
					}else if (data == "name-too-short") {
						$("#uploadStatus").html("<div class=\"alert alert-danger\">Your item name is too short. Try something else.</div>");
					}else if (data == "name-too-long") {
						$("#uploadStatus").html("<div class=\"alert alert-danger\">Your item name is too long. Try something else.</div>");
					}else if (data == "description-too-long") {
						$("#uploadStatus").html("<div class=\"alert alert-danger\">Your description is too long. Try something else.</div>");
					}else if (data == "price-too-low") {
						$("#uploadStatus").html("<div class=\"alert alert-danger\">Your item price is too low.</div>");
					}else if (data == "rate-limit") {
						$("#uploadStatus").html("<div class=\"alert alert-danger\">Please wait a bit before uploading again.</div>");
					}else if (data == "rate-limit") {
						$("#uploadStatus").html("<div class=\"alert alert-danger\">Please wait a bit before uploading again.</div>");
					}else if (data == "incorrect-size") {
						$("#uploadStatus").html("<div class=\"alert alert-danger\">Your image has an incorrect size. Did you use the template?</div>");
					}else if (data == "no-image") {
						$("#uploadStatus").html("<div class=\"alert alert-danger\">The file you have tried to upload is not an image.</div>");
					}else if (data == "file-too-large") {
						$("#uploadStatus").html("<div class=\"alert alert-danger\">The file you have tried to upload is too large.</div>");
					}else if (data == "incorrect-extension") {
						$("#uploadStatus").html("<div class=\"alert alert-danger\">The file you have tried to upload is not a valid image.</div>");
					}else if (data == "not-enough-coins") {
						$("#uploadStatus").html("<div class=\"alert alert-danger\">You do not have enough coins to upload an item.</div>");
					}else if (data == "file-upload-error") {
						$("#uploadStatus").html("<div class=\"alert alert-danger\">An error occured while uploading your item. Please contact an administrator.</div>");
					}else if (data == "no-file") {
						$("#uploadStatus").html("<div class=\"alert alert-danger\">Please select a file to upload.</div>");
					}else if (data == "bad-hash") {
						$("#uploadStatus").html("<div class=\"alert alert-danger\">This item can not be uploaded to Graphictoria.</div>");
					}else{
						$("#uploadStatus").html("<div class=\"alert alert-success\">Your file has been uploaded. It will appear in the catalog when it has been approved.</div>");
						$("#userCoins").html(data);
					}
				},
				error: function() {
					$("#uploadStatus").html("<div class=\"alert alert-danger\">Could not upload item because a network error occurred.</div>");
				}
			});
		}
	})
});