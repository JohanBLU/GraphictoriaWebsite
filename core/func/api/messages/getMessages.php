<?php
	if (isset($_GET['filter'])) {
		$filter = $_GET['filter'];
		if (is_array($filter)) {
			exit;
		}
		if (isset($_GET['page'])) {
			$page = $_GET['page'];
		}else{
			$page = 0;
		}
		
		if (is_numeric($page) == false) exit;
		if (is_numeric($filter) == false) exit;
		
		if (is_array($page)) {
			echo 'Something went wrong.';
			exit;
		}
		if ($page == 0) {
			echo '<h3>Messages</h3>';
		}
		
		function showReadStatus($read) {
			if ($read == 0) {
				return '<span style="color:#158cba" class="fa fa-envelope-open-o"></span>';
			}
		}
		
		$offset = $page*25;
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
			if ($GLOBALS['loggedIn'] == false) {
			exit;
		}
		if ($filter == 0) {
			$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM messages WHERE recv_uid = :rId ORDER BY id DESC LIMIT 26 OFFSET :offset");
		}elseif ($filter == 1) {
			$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM messages WHERE recv_uid = :rId AND `read` = 0 ORDER BY id DESC LIMIT 26 OFFSET :offset");
		}elseif ($filter == 2) {
			$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM messages WHERE recv_uid = :rId AND `read` = 1 ORDER BY id DESC LIMIT 26 OFFSET :offset");
		}elseif ($filter == 3) {
			$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM messages WHERE sender_uid = :rId ORDER BY id DESC LIMIT 26 OFFSET :offset");
		}else{
			$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM messages WHERE recv_uid = :rId ORDER BY id DESC LIMIT 26 OFFSET :offset");
		}
		
		$stmt->bindParam(':rId', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
		$stmt->execute();
		echo '<div class="list-group" style="margin-bottom:0px;">';
		$count = 0;
		foreach($stmt as $result) {
			$count++;
			if ($count < 25) {
				if ($filter == 3) {
					$userSheet = context::getUserSheetByID($result['recv_uid']);
				}else{
					$userSheet = context::getUserSheetByID($result['sender_uid']);
				}
				if ($userSheet['rank'] == 0) {
					$usern = $userSheet['username'];
				}elseif ($userSheet['rank'] == 1) {
					$usern = '<b style="color:#158cba">'.$userSheet['username'].'</b>';
				}elseif ($userSheet['rank'] == 2) {
					$usern = '<b style="color:#28b62c">'.$userSheet['username'].'</b>';
				}
				echo '<div class="list-group-item" style="border:none;border-bottom:2px solid #eeeeee">';
				echo '<h4 class="list-group-item-heading" onclick="loadMessage('.$result['id'].')" style="display:inline">'.showReadStatus($result['read']).' '.context::secureString($result['title']).'</h4>';
				echo '<div class="nav navbar-nav navbar-right" style="margin-right:0px;">';
				echo '<b>Date: </b>'.date('M j Y g:i A', strtotime($result['date']));
				echo '</div>';
				if ($filter == 3) {
					echo '<p class="list-group-item-text">Sent to <a href="/user/profile/'.$userSheet['username'].'">'.$usern.'</a></p>';
				}else{
					echo '<p class="list-group-item-text">Sent by <a href="/user/profile/'.$userSheet['username'].'">'.$usern.'</a></p>';
				}
				echo '<div class="nav navbar-nav navbar-right" style="margin-right:0px;display:inline;margin:-15px 0px 0px;">';
				echo '</div></div>';
			}
		}
		if ($stmt->rowCount() == 0) {
			echo 'You do not have any message';
		}
		if ($count > 25) {
			echo '<button class="btn btn-primary fullWidth loadMore" onclick="loadMore(page, '.$filter.')">Load more</button><script>page++;</script>';
		}
		echo '</div>';
	}else{
		echo 'An error occurred';
	}
?>