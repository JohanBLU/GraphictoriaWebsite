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
			$('#remainingC').html('Characters left: ' + left);
			if ($(this).val().length == 0) $("#remainingC").empty();
		});
	});
</script>
<div id="rStatus"></div>
<p id="remainingC"></p>
<textarea rows="10" maxlength="30000" class="form-control" id="replyContent" placeholder="Reply here"></textarea>
<button class="btn btn-primary" id="postReply" onclick="postReply(<?php echo $result['id'];?>)">Reply</button>