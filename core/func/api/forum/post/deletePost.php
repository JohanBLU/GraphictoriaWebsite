<?php
	if (isset($_POST['csrf']) and isset($_POST['postId'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf'];
		$postId = $_POST['postId'];
		if (is_array($postId)) die("error");
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false or strlen($postId) == 0 or $GLOBALS['userTable']['rank'] == 0) die("error");
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT author_uid, forumId FROM topics WHERE id = :id AND developer = 0 ORDER BY id DESC LIMIT 1;");
		$stmt->bindParam(':id', $postId, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($stmt->rowCount() == 0) {
			echo 'error';
			exit;
		}
		
		$timeSince = round(abs(strtotime(context::getCurrentTime()) - strtotime($GLOBALS['userTable']['lastUpload'])) / 60,2);
		if ($timeSince < 1 && $GLOBALS['userTable']['rank'] != 1) {
			echo 'rate-limit';
			exit;
		}
		
		$forumId = $result['forumId'];
		$userId = $result['author_uid'];
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT posts FROM users WHERE id = :id ORDER BY id DESC LIMIT 1;");
		$stmt->bindParam(':id', $userId, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$posts = $result['posts']-1;
						
		$query = "UPDATE `users` SET `posts`=:posts WHERE `id`=:id;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':id', $userId, PDO::PARAM_INT);
		$stmt->bindParam(':posts', $posts, PDO::PARAM_INT);
		$stmt->execute();
						
		$query = "DELETE FROM `topics` WHERE `id`=:id";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':id', $postId, PDO::PARAM_INT);
		$stmt->execute();
						
		$stmt = $GLOBALS['dbcon']->prepare("SELECT id, author_uid FROM replies WHERE postId = :id");
		$stmt->bindParam(':id', $postId, PDO::PARAM_INT);
		$stmt->execute();
		foreach($stmt as $result) {
			$userId = $result['author_uid'];
			$stmt = $GLOBALS['dbcon']->prepare("SELECT posts FROM users WHERE id = :id ORDER BY id DESC LIMIT 1;");
			$stmt->bindParam(':id', $userId, PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			
			$posts = $result['posts']-1;
			$query = "UPDATE `users` SET `posts`=:posts WHERE `id`=:id;";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->bindParam(':id', $userId, PDO::PARAM_INT);
			$stmt->bindParam(':posts', $posts, PDO::PARAM_INT);
			$stmt->execute();
		}
		
		$query = "DELETE FROM `replies` WHERE `postId`=:id";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':id', $postId, PDO::PARAM_INT);
		$stmt->execute();
		
		$query = "DELETE FROM `read` WHERE `postId`=:id";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':id', $postId, PDO::PARAM_INT);
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
		
		if ($GLOBALS['userTable']['rank'] != 1) {
			$stmt = $dbcon->prepare("UPDATE users SET lastUpload = NOW() WHERE id = :user;");
			$stmt->bindParam(':user', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
			$stmt->execute();
		}
		
		echo 'success';
	}else{
		echo 'error';
	}
?>