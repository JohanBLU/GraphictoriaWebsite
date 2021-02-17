<?php
	if (!defined('IN_PHP')) {
		exit;
	}
?>
<div id="pStatus"></div>
<input class="form-control" maxlength="128" id="messageTitle" type="text" placeholder="Message Title">
<textarea rows="10" maxlength="30000" class="form-control" id="messageContent" placeholder="Message here"></textarea>
<button class="btn btn-primary" id="sendMessage" onclick="sendMessagePost(<?php echo $result['id'];?>)">Send Message</button>