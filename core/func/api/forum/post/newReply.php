<?php
	if (isset($_POST['replyContent']) and isset($_POST['csrf']) and isset($_POST['postId'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$replyContent = $_POST['replyContent'];
		$csrf = $_POST['csrf'];
		$postId = $_POST['postId'];
		$contentCheck = preg_replace('!\s+!', ' ', $replyContent);
		$contentCheck = strip_tags($contentCheck);
		$contentCheck = preg_replace("/&#?[a-z0-9]+;/i","", $contentCheck);
		$contentCheck = preg_replace('!\s+!', ' ', $contentCheck);
		$contentCheck = strtolower(preg_replace('|[[\/\!]*?[^\[\]]*?]|si', '', $contentCheck));
		$contentCheck = preg_replace('/\s+/', '', $contentCheck);
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false or strlen($postId) == 0) {
			echo 'error';
			exit;
		}
		
		if (strtolower($replyContent) == strtolower($GLOBALS['userTable']['lastForumContent'])) die('<span style="color:red">You have already posted this</span>');
		
		$badwords = array("fucking", "gay", "rape", "incest", "beastiality", "cum", "maggot", "bullshit", "fuck", "penis",
						"dick", "vagina", "vag", "faggot", "fag", "nigger", "asshole", "shit", "bitch", "anal", "stfu",
						"cunt", "pussy", "hump", "meatspin", "redtube", "porn", "kys", "xvideos", "hentai", "gangbang", "milf",
						"n*", "nobelium", "whore", "wtf", "horny", "raping", "s3x", "boob", "nigga", "nlgga", "gt2008",
						"cock", "dicc", "idiot", "nibba", "nibber", "nude", "kesner", "brickopolis", "nobe", "diemauer", "nuts");
						
		$badwords2 = array("sex", "porn");
		if (context::contains($replyContent, $badwords2)) {
			echo '<span style="color:red">This reply contains filtered words.</span>';
			exit;
		}
		
		
		// Check without special characters removed
		if (context::contains($contentCheck, $badwords)) {
			echo '<span style="color:red">This reply contains filtered words.</span>';
			exit;
		}
		
		// Check with special characters removed, except *.
		$contentCheck = preg_replace("/[^A-Za-z0-9*]/", '', $contentCheck);
		if (context::contains($contentCheck, $badwords)) {
			echo '<span style="color:red">This reply contains filtered words.</span>';
			exit;
		}
		
		if (strlen($replyContent) < 5 or strlen($contentCheck) < 5) {
			echo 'content-too-short';
			exit;
		}
		
		if (strlen($replyContent) > 30000) {
			echo 'content-too-long';
			exit;
		}
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT lastPost, joinDate, rank FROM users WHERE id = :id");
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$timeSince =  round(abs(strtotime(date('Y-m-d H:i:s')) - strtotime($result['lastPost'])) / 60,2);
		if ($timeSince < 0.5 and $result['rank'] == 0) {
			echo 'rate-limit';
			exit;
		}
		
		$timeSince =  round(abs(strtotime(date('Y-m-d H:i:s')) - strtotime($result['joinDate'])) / 60,2);
		if ($timeSince < 1440 and $result['rank'] == 0) {
			echo 'account-age';
			exit;
		}
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM topics WHERE id = :id AND developer = 0");
		$stmt->bindParam(':id', $postId, PDO::PARAM_INT);
		$stmt->execute();		
		if ($stmt->rowCount() == 0) {
			echo 'no-post';
			exit;
		}
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$postId = $result['id'];
		$forumId = $result['forumId'];
		if ($result['locked'] == 1 and $GLOBALS['userTable']['rank'] == 0) {
			echo 'access-denied';
			exit;
		}
		
		$query = "INSERT INTO replies (`postId`, `content`, `author_uid`, `forumId`) VALUES (:id, :content, :poster, :forumId);";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':id', $postId, PDO::PARAM_INT);
		$stmt->bindParam(':poster', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':content', $replyContent, PDO::PARAM_STR);
		$stmt->bindParam(':forumId', $forumId, PDO::PARAM_INT);
		$stmt->execute();
		
		$query = "UPDATE `topics` SET `lastActivity`=NOW() WHERE `id`=:id;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':id', $postId, PDO::PARAM_INT);
		$stmt->execute();
		
		$query = "UPDATE `users` SET `lastPost`=NOW() WHERE `id`=:id;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		
		$query = "UPDATE `users` SET `lastForumContent`=:content WHERE `id`=:id;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':content', $replyContent, PDO::PARAM_STR);
		$stmt->execute();
		
		$query = "DELETE FROM `read` WHERE `postId`=:id";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':id', $postId, PDO::PARAM_INT);
		$stmt->execute();
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT replies FROM forums WHERE id = :id ORDER BY id DESC LIMIT 1;");
		$stmt->bindParam(':id', $forumId, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$posts = $result['replies']+1;
		
		$query = "UPDATE `forums` SET `replies`=:posts WHERE `id`=:id;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':id', $forumId, PDO::PARAM_INT);
		$stmt->bindParam(':posts', $posts, PDO::PARAM_INT);
		$stmt->execute();
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT replies FROM topics WHERE id = :id ORDER BY id DESC LIMIT 1;");
		$stmt->bindParam(':id', $postId, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$posts = $result['replies']+1;
		
		$query = "UPDATE `topics` SET `replies`=:posts WHERE `id`=:id;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':id', $postId, PDO::PARAM_INT);
		$stmt->bindParam(':posts', $posts, PDO::PARAM_INT);
		$stmt->execute();
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT posts FROM users WHERE id = :id ORDER BY id DESC LIMIT 1;");
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$posts = $result['posts']+1;
		$query = "UPDATE `users` SET `posts`=:posts WHERE `id`=:id;";
		$stmt = $dbcon->prepare($query);
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':posts', $posts, PDO::PARAM_INT);
		$stmt->execute();
		
		echo '<script>loadPost('.$postId.');</script>';
	}else{
		echo 'error';
	}
?>