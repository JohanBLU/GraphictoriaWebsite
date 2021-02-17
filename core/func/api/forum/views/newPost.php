<?php
	if (!defined('IN_PHP')) {
		exit;
	}
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
<div id="pStatus"></div>
<input class="form-control" maxlength="128" id="postTitle" type="text" placeholder="Post title" style="display:inline"><p id="remainingC" style="display:inline"></p>
<textarea rows="10" maxlength="30000" class="form-control" id="postContent" placeholder="Post here"></textarea>
<button class="btn btn-primary" id="postMessage" onclick="postMessage(<?php echo $result['id'];?>)">Post</button>