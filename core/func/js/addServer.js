$(document).ready(function() {
	$(document).on('change', '#serverType', function() {
		$("#addServer02").prop("disabled", false);
		$("#serverName").prop("disabled", false);
		$("#serverDescription").prop("disabled", false);
		$("#placeFile").prop("disabled", false);
		$("#addServer01").prop("disabled", false);
		$("#serverName").prop("disabled", false);
		$("#serverDescription").prop("disabled", false);
		$("#serverIP").prop("disabled", false);
		$("#serverPort").prop("disabled", false);
		$("#privacyType").prop("disabled", false);
			
		if ($(this).find("option:selected").attr('value') == 0) {
			$("#selfHostOptions").css("display", "block");
			$("#dedicatedOptions").css("display", "none");
		}else{
			$("#selfHostOptions").css("display", "none");
			$("#dedicatedOptions").css("display", "block");
		}
	})
	$("#addServer01").click(function() {
		if ($("#addServer01").is(":disabled") == false) {
			$("#addServer01").prop("disabled", true);
			$("#serverName").prop("disabled", true);
			$("#serverDescription").prop("disabled", true);
			$("#serverIP").prop("disabled", true);
			$("#serverPort").prop("disabled", true);
			$("#privacyType").prop("disabled", true);
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			var serverName = $("#serverName").val();
			var serverDescription = $("#serverDescription").val();
			var serverIP = $("#serverIP").val();
			var serverPort = $("#serverPort").val();
			var privacyType = $("#privacyType").val();
			var gameVersion = $("#versionType").val();
			$.post('/core/func/api/games/post/addServer.php', {
				csrf: csrf_token,
				serverName: serverName,
				serverDescription: serverDescription,
				serverIP: serverIP,
				serverPort: serverPort,
				privacyType: privacyType,
				gameVersion: gameVersion
			})
			.done(function(response) {
				$("#addServer01").prop("disabled", false);
				$("#serverName").prop("disabled", false);
				$("#serverDescription").prop("disabled", false);
				$("#serverIP").prop("disabled", false);
				$("#serverPort").prop("disabled", false);
				$("#privacyType").prop("disabled", false);
				
				if (response == "error") {
					$("#aStatus").html("<div class=\"alert alert-danger\">Could not add the server because a network error has occurred.</div>");
				}else if (response == "server-name-too-long") {
					$("#aStatus").html("<div class=\"alert alert-danger\">Your server name is too long.</div>");
				}else if (response == "server-name-too-short") {
					$("#aStatus").html("<div class=\"alert alert-danger\">Your server name is too short.</div>");
				}else if (response == "server-description-too-long") {
					$("#aStatus").html("<div class=\"alert alert-danger\">Your server description is too long.</div>");
				}else if (response == "server-ip-too-short") {
					$("#aStatus").html("<div class=\"alert alert-danger\">Your server IP is too short.</div>");
				}else if (response == "server-ip-too-long") {
					$("#aStatus").html("<div class=\"alert alert-danger\">Your server IP is too long.</div>");
				}else if (response == "server-port-too-short") {
					$("#aStatus").html("<div class=\"alert alert-danger\">Your server port is too short.</div>");
				}else if (response == "server-port-too-long") {
					$("#aStatus").html("<div class=\"alert alert-danger\">Your server port is too long.</div>");
				}else if (response == "invalid-port") {
					$("#aStatus").html("<div class=\"alert alert-danger\">Your server port is invalid.</div>");
				}else if (response == "invalid-ip") {
					$("#aStatus").html("<div class=\"alert alert-danger\">Your server IP is invalid.</div>");
				}else if (response == "invalid-privacy") {
					$("#aStatus").html("<div class=\"alert alert-danger\">An error has occurred.</div>");
				}else if (response == "invalid-version") {
					$("#aStatus").html("<div class=\"alert alert-danger\">Invalid server version received.</div>");
				}else{
					window.location = "/games/view/"+response;
				}
			})
			.fail(function() {
				$("#aStatus").html("<div class=\"alert alert-danger\">Could not add the server because a network error has occurred.</div>");
			});
		}
	})
	
	$(document).on('change', '#placeFile', function() {
		$(".place").css("filter", "grayscale(100%)");
	});
	
	$("#placeFile").click(function() {
		$(".place").css("box-shadow", "none");
	});
	
	$(".place").click(function() {
		if ($('#placeFile')[0].files[0] == undefined)
			$(this).css("box-shadow", "0 0 0 1px #75caeb");
	});
	
	$("#addServer02").click(function() {
		if ($("#addServer02").is(":disabled") == false) {
			$("#addServer02").prop("disabled", true);
			$("#serverName").prop("disabled", true);
			$("#serverDescription").prop("disabled", true);
			$("#placeFile").prop("disabled", true);
			$("#versionTypeDedi").prop("disabled", true);
			$("#privacyTypeDedi").prop("disabled", true);
			
			var genPlace = 0;
			if ($("#place0").css("box-shadow") != "none") genPlace = 1;
			
			var formData = new FormData();
			formData.append('placeFile', $('#placeFile')[0].files[0]);
			formData.append('serverName', $("#serverName").val());
			formData.append('serverDescription', $("#serverDescription").val());
			formData.append('versionType', $("#versionTypeDedi").val());
			formData.append('privacyType', $("#privacyTypeDedi").val());
			formData.append('csrf_token', $('meta[name="csrf-token"]').attr('content'));
			formData.append('genPlace', genPlace);
			$.ajax({
				type: "POST",
				url : "/core/func/api/games/post/addServerDedicated.php",
				data : formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data) {
					$("#addServer02").prop("disabled", false);
					$("#serverName").prop("disabled", false);
					$("#serverDescription").prop("disabled", false);
					$("#placeFile").prop("disabled", false);
					$("#versionTypeDedi").prop("disabled", false);
					$("#privacyTypeDedi").prop("disabled", false);
					if (data == "server-name-too-long") {
						$("#aStatus").html("<div class=\"alert alert-danger\">Server name is too long</div>");
					}else if (data == "server-name-too-short") {
						$("#aStatus").html("<div class=\"alert alert-danger\">Server name is too short</div>");
					}else if (data == "server-description-too-long") {
						$("#aStatus").html("<div class=\"alert alert-danger\">Server description is too long</div>");
					}else if (data == "invalid-placefile") {
						$("#aStatus").html("<div class=\"alert alert-danger\">Invalid place file.</div>");
					}else if (data == "rate-limit") {
						$("#aStatus").html("<div class=\"alert alert-danger\">You can only add a server each 5 minutes.</div>");
					}else if (data == "invalid-privacy") {
						$("#aStatus").html("<div class=\"alert alert-danger\">Invalid privacy type received</div>");
					}else if (data == "success") {
						$("#aStatus").html("<div class=\"alert alert-success\">Your dedicated server will be up and running shortly.</div>");
						$(".profileCard").html("<h3>Loading</h3><p>Please wait as we load your server</p>");
						setTimeout(function(){ 
							$(".profileCardContainer").load("/core/func/api/games/getLatestServer.php");
							$(".profileCardContainerHead").append("<h3>Your server has been created</h3>");
							$(".profileCard").remove();
							$(".alert").remove();
						}, 10000);
					}else if (data == "error") {
						$("#aStatus").html("<div class=\"alert alert-danger\">Please select a place</div>");
					}else{
						$("#aStatus").html("<div class=\"alert alert-danger\">Could not add the server because a network error has occurred.</div>");
					}
				},error: function() {
					$("#aStatus").html("<div class=\"alert alert-danger\">Could not add the server because a network error has occurred.</div>");
				}
			});
		}
	})
})