$(document).ready(function() {
	$("#doUpload").click(function() {
		if ($("#doUpload").is(":disabled") == false) {
			$("#doUpload").prop("disabled", true);
			$("#meshFile").prop("disabled", true);
			$("#textureFile").prop("disabled", true);
			$("#xmlContent").prop("disabled", true);
			$("#isBuyable").prop("disabled", true);
			$("#hatPrice").prop("disabled", true);
			$("#isRBX").prop("disabled", true);
			$("#hatName").prop("disabled", true);
			$("#hatDescription").prop("disabled", true);
			$("#datafileName").prop("disabled", true);
			var formData = new FormData();
			formData.append('meshFile', $('#meshFile')[0].files[0]);
			formData.append('textureFile', $('#textureFile')[0].files[0]);
			formData.append('hatName', $("#hatName").val());
			formData.append('hatDescription', $("#hatDescription").val());
			formData.append('hatPrice', $("#hatPrice").val());
			formData.append('datafileName', $("#datafileName").val());
			formData.append('isBuyable', $('#isBuyable').prop('checked'));
			formData.append('RBXAsset', $('#isRBX').prop('checked'));
			formData.append('xmlContent', $("#xmlContent").val());
			formData.append('csrf_token', $('meta[name="csrf-token"]').attr('content'));
			$.ajax({
				type: "POST",
				url : "/core/func/api/admin/post/newHat.php",
				data : formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					console.log(response);
					$("#doUpload").prop("disabled", false);
					$("#meshFile").prop("disabled", false);
					$("#textureFile").prop("disabled", false);
					$("#xmlContent").prop("disabled", false);
					$("#isBuyable").prop("disabled", false);
					$("#isRBX").prop("disabled", false);
					$("#hatPrice").prop("disabled", false);
					$("#hatName").prop("disabled", false);
					$("#hatDescription").prop("disabled", false);
					$("#datafileName").prop("disabled", false);
					if (response == "success") {
						$("#cStatus").html("<div class=\"alert alert-success\">Hat uploaded!</div>");
					}else if (response == "error") {
						$("#cStatus").html("<div class=\"alert alert-danger\">Could not upload this hat because a network error occurred. Attempt again later.</div>");
					}else if (response == "missing-info" || response == "no-file") {
						$("#cStatus").html("<div class=\"alert alert-danger\">We are missing information from you. Please check again.</div>");
					}else if (response == "name-too-long") {
						$("#cStatus").html("<div class=\"alert alert-danger\">Your hat name is too long.</div>");
					}else if (response == "datafilename-too-long") {
						$("#cStatus").html("<div class=\"alert alert-danger\">Your datafile name is too long.</div>");
					}else if (response == "description-too-long") {
						$("#cStatus").html("<div class=\"alert alert-danger\">Your description is too long.</div>");
					}else if (response == "illegal-buyable") {
						$("#cStatus").html("<div class=\"alert alert-danger\">Illegal alter detected.</div>");
					}else if (response == "price-too-low") {
						$("#cStatus").html("<div class=\"alert alert-danger\">Price is too low. Min: 1 coin</div>");
					}else if (response == "datafile-mesh-already-exists") {
						$("#cStatus").html("<div class=\"alert alert-danger\">This datafile name already exists. Please try another one.</div>");
					}else if (response == "meshfile-illegalFileType") {
						$("#cStatus").html("<div class=\"alert alert-danger\">Your mesh file type is incorrect.</div>");
					}else if (response == "file-upload-error") {
						$("#cStatus").html("<div class=\"alert alert-danger\">File upload failed. Please notify an administrator.</div>");
					}else if (response == "texture-illegalFileType") {
						$("#cStatus").html("<div class=\"alert alert-danger\">Your texture file type is incorrect.</div>");
					}else{
						$("#cStatus").html("<div class=\"alert alert-danger\">Could not upload this hat because a network error occurred. Attempt again later.</div>");
					}
				},
				error: function() {
					$("#cStatus").html("<div class=\"alert alert-danger\">Could not upload this hat because a network error occurred. Attempt again later.</div>");
				}
			});
		}
	})
});