<?php
	if (isset($_POST['csrf']) and isset($_POST['username'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf'];
		$username = $_POST['username'];
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false or $GLOBALS['userTable']['rank'] != 1) {
			echo 'error';
			exit;
		}
		
		if (strlen($username) == 0) {
			echo 'missing-info';
			exit;
		}
		
		if (strtolower($username) == strtolower($GLOBALS['userTable']['username'])) {
			echo 'can-not-prune-yourself';
			exit;
		}
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT id, rank, banned FROM users WHERE username=:uname;");
		$stmt->bindParam(':uname', $username, PDO::PARAM_STR);
		$stmt->execute();
		if ($stmt->rowCount() == 0) {
			echo 'no-user';
			exit;
		}
		
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($GLOBALS['userTable']['rank'] == 1) {
			if ($result['rank'] == 1) {
				echo 'can-not-prune-user';
				exit;
			}
		}else{
			if ($result['rank'] > 0) {
				echo 'can-not-prune-user';
				exit;
			}
		}
		
		$userID = $result['id'];
		$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET posts = 0 WHERE id=:id;");
		$stmt->bindParam(':id', $userID, PDO::PARAM_INT);
		$stmt->execute();
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM topics WHERE author_uid = :uid");
		$stmt->bindParam(':uid', $userID, PDO::PARAM_STR);
		$stmt->execute();
		foreach($stmt as $result) {
			$postID = $result['id'];
			$forumId = $result['forumId'];
			$stmt = $GLOBALS['dbcon']->prepare("DELETE FROM topics WHERE id = :id");
			$stmt->bindParam(':id', $postID, PDO::PARAM_STR);
			$stmt->execute();
			
			$stmt = $GLOBALS['dbcon']->prepare("DELETE FROM replies WHERE postId = :id");
			$stmt->bindParam(':id', $postID, PDO::PARAM_STR);
			$stmt->execute();
			
			$query = "SELECT id FROM topics WHERE forumId=:id;";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->bindParam(':id', $forumId, PDO::PARAM_INT);
			$stmt->execute();
			$total = $stmt->rowCount();
			
			$stmt = $GLOBALS['dbcon']->prepare("UPDATE forums SET posts = :posts WHERE id=:id;");
			$stmt->bindParam(':id', $forumId, PDO::PARAM_INT);
			$stmt->bindParam(':posts', $total, PDO::PARAM_INT);
			$stmt->execute();
			
			$query = "SELECT id FROM replies WHERE forumId=:id;";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->bindParam(':id', $forumId, PDO::PARAM_INT);
			$stmt->execute();
			$total = $stmt->rowCount();
			
			$stmt = $GLOBALS['dbcon']->prepare("UPDATE forums SET replies = :posts WHERE id=:id;");
			$stmt->bindParam(':id', $forumId, PDO::PARAM_INT);
			$stmt->bindParam(':posts', $total, PDO::PARAM_INT);
			$stmt->execute();
		}
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT id, postId, forumId FROM replies WHERE author_uid = :uid");
		$stmt->bindParam(':uid', $userID, PDO::PARAM_INT);
		$stmt->execute();
		foreach($stmt as $result) {
			$replyID = $result['id'];
			$postID = $result['postId'];
			$forumId = $result['forumId'];
			$stmt = $GLOBALS['dbcon']->prepare("DELETE FROM replies WHERE id = :id;");
			$stmt->bindParam(':id', $replyID, PDO::PARAM_STR);
			$stmt->execute();
			
			$stmt = $GLOBALS['dbcon']->prepare("SELECT post_time FROM replies WHERE postId = :id ORDER BY id DESC LIMIT 1;");
			$stmt->bindParam(':id', $postID, PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$postTime = $result['post_time'];
			
			if ($stmt->rowCount() > 0) {
				$query = "UPDATE `topics` SET `lastActivity`=:date WHERE `id`=:id;";
				$stmt = $GLOBALS['dbcon']->prepare($query);
				$stmt->bindParam(':id', $postID, PDO::PARAM_INT);
				$stmt->bindParam(':date', $postTime, PDO::PARAM_STR);
				$stmt->execute();
			}else{
				$stmt = $GLOBALS['dbcon']->prepare("SELECT postTime FROM topics WHERE id = :id;");
				$stmt->bindParam(':id', $postID, PDO::PARAM_INT);
				$stmt->execute();
				$result = $stmt->fetch(PDO::FETCH_ASSOC);
				$OPPostTime = $result['postTime'];
									
				$query = "UPDATE `topics` SET `lastActivity`=:date WHERE `id`=:id;";
				$stmt = $GLOBALS['dbcon']->prepare($query);
				$stmt->bindParam(':id', $postID, PDO::PARAM_INT);
				$stmt->bindParam(':date', $OPPostTime , PDO::PARAM_STR);
				$stmt->execute();
			}
			
			$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM replies WHERE postId = :id;");
			$stmt->bindParam(':id', $postID, PDO::PARAM_INT);
			$stmt->execute();
			$replyCount = $stmt->rowCount();
								
			$query = "UPDATE `topics` SET `replies`=:rCount WHERE `id`=:id;";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->bindParam(':id', $postID, PDO::PARAM_INT);
			$stmt->bindParam(':rCount', $replyCount , PDO::PARAM_STR);
			$stmt->execute();
								
			$query = "SELECT * FROM topics WHERE forumId=:id;";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->bindParam(':id', $forumId, PDO::PARAM_INT);
			$stmt->execute();
			$total = $stmt->rowCount();
								
			$stmt = $GLOBALS['dbcon']->prepare("UPDATE forums SET posts = :posts WHERE id=:id;");
			$stmt->bindParam(':id', $forumId, PDO::PARAM_INT);
			$stmt->bindParam(':posts', $total, PDO::PARAM_INT);
			$stmt->execute();
								
			$query = "SELECT * FROM replies WHERE forumId=:id;";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->bindParam(':id', $forumId, PDO::PARAM_INT);
			$stmt->execute();
			$total = $stmt->rowCount();
								
			$stmt = $GLOBALS['dbcon']->prepare("UPDATE forums SET replies = :posts WHERE id=:id;");
			$stmt->bindParam(':id', $forumId, PDO::PARAM_INT);
			$stmt->bindParam(':posts', $total, PDO::PARAM_INT);
			$stmt->execute();
		}
		
		context::sendDiscordMessage($GLOBALS['userTable']['username'].' has pruned the posts of user **'.$username.'**');
		
		echo 'success';
	}else{
		echo 'error';
	}
?>