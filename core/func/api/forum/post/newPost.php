<?php
	if (isset($_POST['postTitle']) and isset($_POST['postContent']) and isset($_POST['csrf']) and isset($_POST['forum'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$postTitle = $_POST['postTitle'];
		$postContent = $_POST['postContent'];
		
		$titleCheck = preg_replace('!\s+!', ' ', $postTitle);
		$titleCheck = strip_tags($titleCheck);
		$titleCheck = preg_replace("/&#?[a-z0-9]+;/i","", $titleCheck);
		$titleCheck = preg_replace('!\s+!', ' ', $titleCheck);
		$titleCheck = strtolower(preg_replace('|[[\/\!]*?[^\[\]]*?]|si', '', $titleCheck));
		$titleCheck = preg_replace('/\s+/', '', $titleCheck);
		
		$contentCheck = preg_replace('!\s+!', ' ', $postContent);
		$contentCheck = strip_tags($contentCheck);
		$contentCheck = preg_replace("/&#?[a-z0-9]+;/i","", $contentCheck);
		$contentCheck = preg_replace('!\s+!', ' ', $contentCheck);
		$contentCheck = strtolower(preg_replace('|[[\/\!]*?[^\[\]]*?]|si', '', $contentCheck));
		$contentCheck = preg_replace('/\s+/', '', $contentCheck);
		
		$badwords = array("fucking", "gay", "rape", "incest", "beastiality", "maggot", "bullshit", "fuck", "penis",
						"dick", "vagina", "vag", "faggot", "fag", "nigger", "asshole", "shit", "bitch", "anal", "stfu",
						"cunt", "pussy", "hump", "meatspin", "redtube", "porn", "kys", "xvideos", "hentai", "gangbang", "milf",
						"n*", "nobelium", "whore", "wtf", "horny", "raping", "s3x", "boob", "nigga", "nlgga", "gt2008",
						"cock", "dicc", "idiot", "nibba", "nibber", "nude", "kesner", "brickopolis", "nobe", "diemauer", "nuts");
		
		$csrf = $_POST['csrf'];
		$forum = $_POST['forum'];
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false or strlen($forum) == 0) {
			echo 'error';
			exit;
		}
		
		if (strtolower($postContent) == strtolower($GLOBALS['userTable']['lastForumContent'])) die('<span style="color:red">You have already posted this</span>');
		
		// Fixes things like "this i>s ex<"
		$badwords2 = array("sex", "porn");
		if (context::contains($postContent, $badwords2)) {
			echo '<span style="color:red">This post contains filtered words.</span>';
			exit;
		}
		
		// Check without special characters removed, will catch stuff like N*
		if (context::contains($contentCheck, $badwords) or context::contains($titleCheck, $badwords)) {
			echo '<span style="color:red">This post or post title contains filtered words.</span>';
			exit;
		}
		
		// Check again but with special characters removed, except *
		$titleCheck = preg_replace("/[^A-Za-z0-9*]/", '', $titleCheck);
		$contentCheck = preg_replace("/[^A-Za-z0-9*]/", '', $contentCheck);
		if (context::contains($contentCheck, $badwords) or context::contains($titleCheck, $badwords)) {
			echo '<span style="color:red">This post or post title contains filtered words.</span>';
			exit;
		}
		
		if (!preg_match("/^[\w*?!\/@',:#$%\^&*\(\) -]+$/", $postTitle) == 1) {
			die('<span style="color:red">Invalid characters in title.</span>');
		}
		
		if (strlen($postTitle) < 5 or strlen($titleCheck) < 5) {
			echo 'title-too-short';
			exit;
		}
		
		if (strlen($postTitle) > 128) {
			echo 'title-too-long';
			exit;
		}
		
		if (strlen($postContent) < 5 or strlen($contentCheck) < 5) {
			echo 'content-too-short';
			exit;
		}
		
		if (strlen($postContent) > 30000) {
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
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM forums WHERE id = :id AND developer = 0");
		$stmt->bindParam(':id', $forum, PDO::PARAM_INT);
		$stmt->execute();		
		if ($stmt->rowCount() == 0) {
			echo 'no-forum';
			exit;
		}
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($result['locked'] == 1 and $GLOBALS['userTable']['rank'] != 1) {
			echo 'access-denied';
			exit;
		}
		
		$query = "INSERT INTO topics (`forumId`, `title`, `author_uid`, `content`, `lastActivity`) VALUES (:forumid, :topicname, :poster, :content, NOW());";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':forumid', $forum, PDO::PARAM_INT);
		$stmt->bindParam(':topicname', $postTitle, PDO::PARAM_STR);
		$stmt->bindParam(':poster', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':content', $postContent, PDO::PARAM_STR);
		$stmt->execute();
		
		$query = "UPDATE `users` SET `lastPost`=NOW() WHERE `id`=:id;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		
		$query = "UPDATE `users` SET `lastForumContent`=:content WHERE `id`=:id;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':content', $postContent, PDO::PARAM_STR);
		$stmt->execute();
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM forums WHERE id = :id ORDER BY id DESC LIMIT 1;");
		$stmt->bindParam(':id', $forum, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$posts = $result['posts']+1;
		$query = "UPDATE `forums` SET `posts`=:posts WHERE `id`=:id;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':id', $forum, PDO::PARAM_INT);
		$stmt->bindParam(':posts', $posts, PDO::PARAM_INT);
		$stmt->execute();
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT posts FROM users WHERE id = :id ORDER BY id DESC LIMIT 1;");
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$posts = $result['posts']+1;
		$query = "UPDATE `users` SET `posts`=:posts WHERE `id`=:id;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':posts', $posts, PDO::PARAM_INT);
		$stmt->execute();
		
		$stmt = $dbcon->prepare("SELECT id FROM topics WHERE author_uid = :id ORDER BY id DESC LIMIT 1;");
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		echo '<script>loadPost('.$result['id'].');</script>';
	}else{
		echo 'error';
	}
?>