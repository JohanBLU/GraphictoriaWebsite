<?php
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		if (is_array($id)) {
			echo 'Something went wrong.';
			exit;
		}
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
			if ($GLOBALS['loggedIn'] == false) {
			exit;
		}
		$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM messages WHERE id = :id");
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		if ($stmt->rowCount() == 0) {
			echo 'Message not found';
			exit;
		}
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($result['recv_uid'] != $GLOBALS['userTable']['id'] && $result['sender_uid'] != $GLOBALS['userTable']['id']) {
			echo 'Message not found!';
			exit;
		}
		if ($result['read'] == 0) {
			$read = false;
		}else{
			$read = true;
		}
		if ($read == false and $loggedIn == true) {
			if ($result['recv_uid'] == $GLOBALS['userTable']['id']) {
				$stmt = $GLOBALS['dbcon']->prepare("UPDATE messages SET `read` = 1 WHERE id = :id");
				$stmt->bindParam(':id', $id, PDO::PARAM_INT);
				$stmt->execute();
			}
		}
		
		$id = $result['id'];
		if ($result['recv_uid'] == $GLOBALS['userTable']['id']) {
			$userSheet = context::getUserSheetByID($result['sender_uid']);
		}else{
			$userSheet = context::getUserSheetByID($result['recv_uid']);
		}
		echo '<div class="nav navbar-nav navbar-right" style="margin-right:15px;">';
		if ($result['recv_uid'] == $GLOBALS['userTable']['id']) {
			echo '<a style="margin:-2px -15px 5px;" class="btn btn-primary" href="/user/messages/+'.$userSheet['username'].'">Reply</a>';
		}
		echo '<div id="pStatus"></div>';
		echo '</div>';
		echo '<h3>'.context::secureString($result['title']).'</h3>';
		echo '<div class="nav navbar-nav navbar-right" style="margin-right:15px;">';
		echo '</div>';
		if ($userSheet['rank'] == 0) {
			$usern = $userSheet['username'];
		}elseif ($userSheet['rank'] == 1) {
			$usern = '<b style="color:#158cba">'.$userSheet['username'].'</b>';
		}elseif ($userSheet['rank'] == 2) {
			$usern = '<b style="color:#28b62c">'.$userSheet['username'].'</b>';
		}
		echo '<div class="list-group-item" style="border:none;border-bottom:2px solid #eeeeee"><div class="row"><div class="col-xs-12 col-sm-12 col-md-2 center">
		<div class="center">'.context::getOnline($userSheet).'<a href="/user/profile/'.$userSheet['username'].'">'.$usern.'</a></div>
		<a href="/user/profile/'.$userSheet['username'].'"><img height="150" width="150" class="img-responsive" style="display:inline" src="'.context::getUserImage($userSheet).'"></a><br>';
		if ($userSheet['rank'] == 1) {
			echo '<p style="color:#158cba;margin:0 0 0px"><span class="fa fa-bookmark"></span> <b>Administrator</b></p>';
		}
		if ($userSheet['rank'] == 2) {
			echo '<p style="color:#28b62c;margin:0 0 0px"><span class="fa fa-gavel"></span> <b>Moderator</b></p>';
		}
		echo '<b>Posts: </b>'.$userSheet['posts'].'<br>
		<b>Joined: </b>'.date('M j Y', strtotime($userSheet['joinDate'])).'
		</div>';
		$content = strip_tags($result['content']);
		$content = context::secureString($content);
		if ($userSheet['rank'] > 0) {
			$content = preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","<iframe width=\"420\" height=\"315\" src=\"//www.youtube.com/embed/$1\" frameborder=\"0\" allowfullscreen></iframe>", $content);
			$content = preg_replace("/https?:\/\/[^ ]+?(?:\.jpg|\.png|\.gif)/",'<img class="img-responsive" src="$0">', $content);
		}
		echo '<div class="col-xs-10">
		<b><span class="fa fa-clock-o"></span> Sent on: </b>'.date('M j Y g:i A', strtotime($result['date'])).'<br>
		'.nl2br($content).'
		</div></div></div>';
	}else{
		echo 'An error occurred';
	}
?>