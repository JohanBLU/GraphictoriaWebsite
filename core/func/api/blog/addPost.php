<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	if ($GLOBALS['loggedIn'] == false || $GLOBALS['userTable']['rank'] != 1) die("Access has been denied, if you believe you should have access please contact an administrator.");
?>
<script>
	$(document).ready(function () {
		var charactersAllowed = 30000;
		$('textarea').keyup(function () {
			var left = charactersAllowed - $(this).val().length;
			$('#remainingC').html('<br>Characters left: ' + left);
			if ($(this).val().length == 0) $("#remainingC").empty();
		});
	});
</script>
<script>$("#title").html("New Post <div style=\"float:right;color:#158cba;cursor:pointer\" onclick=\"loadMain();\">Back</div>");</script>
<div id="pStatus"></div>
<input type="text" class="form-control" placeholder="Post title" style="display:inline" maxlength="64"/><p id="remainingC" style="display:inline"></p>
<textarea rows="5" class="form-control" placeholder="Post content here" maxlength="30000"></textarea>
<button class="btn btn-primary" onclick="addPost();">Add Post</button>