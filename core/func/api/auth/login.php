<?php
	if (isset($_POST['username']) && isset($_POST['passwd']) && isset($_POST['csrf'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$IP = auth::getIP();
		$username = $_POST['username'];
		$password = $_POST['passwd'];
		$csrf_token = $_POST['csrf'];
		
		if ($csrf_token != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == true) {
			echo 'error';
			exit;
		}
		
		if (strlen($username) == 0 or strlen($password) == 0) {
			echo 'missing-info';
			exit;
		}
		
		$query = "SELECT * FROM loginAttempts WHERE ip = :ip ORDER BY id DESC LIMIT 1";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':ip', $IP, PDO::PARAM_STR);
		$stmt->execute();
		if ($stmt->rowCount() == 1) {
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($result['count'] == 3) {
				$from_time = strtotime($result['time']);
				$to_time = strtotime(context::getCurrentTime());
				$timeSince = round(abs($to_time - $from_time) / 60,2);
				if ($timeSince < 2) {
					echo 'rate-limit';
					exit;
				}
			}
		}
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT id, password_salt, password, password_hash, registerIP, passwordVersion, rank, posts, username FROM users WHERE username = :username OR email = :email;");
		$stmt->bindParam(':username', $username, PDO::PARAM_STR); 
		$stmt->bindParam(':email', $username, PDO::PARAM_STR); 
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($stmt->rowCount() == 0) {
			echo 'no-user';
			exit;
		}
		
		$auth_hash = crypt($password, $result['password_salt']);
		if ($auth_hash == $result['password_hash']) {
			if ($result['registerIP'] == NULL) {
				$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET registerIP = :ip WHERE id = :id;");
				$stmt->bindParam(':id', $result['id'], PDO::PARAM_INT);
				$stmt->bindParam(':ip', $IP, PDO::PARAM_STR);
				$stmt->execute(); 
			}
			
			$form_code = md5(uniqid());
			$aid = context::random_str(128);
			$location = $_SERVER["HTTP_CF_IPCOUNTRY"];
			$stmt = $GLOBALS['dbcon']->prepare('INSERT INTO `sessions` (`userId`, `sessionId`, `csrfToken`, `useragent`, `location`) VALUES (:userId, :sid, :csrf, :useragent, :location);');
			$stmt->bindParam(':userId', $result['id'], PDO::PARAM_INT);
			$stmt->bindParam(':sid', $aid, PDO::PARAM_STR);
			$stmt->bindParam(':csrf', $form_code, PDO::PARAM_STR);
			$stmt->bindParam(':useragent', $_SERVER['HTTP_USER_AGENT'], PDO::PARAM_STR);
			$stmt->bindParam(':location', $location, PDO::PARAM_STR);
			$stmt->execute();
			
			if (isset($_COOKIE['auth_uid']) || isset($_COOKIE['a_id'])) {
				setcookie('auth_uid', "", time() - 3600);
				setcookie('a_id', "", time() - 3600);
			}
			
			setcookie("auth_uid", $result['id'], time() + (86400 * 30), "/", ".xdiscuss.net", false, true);
			setcookie("a_id", $aid, time() + (86400 * 30), "/", ".xdiscuss.net", false, true);
			
			$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET lastIP = :ip WHERE id = :id;");
			$stmt->bindParam(':id', $result['id'], PDO::PARAM_INT);
			$stmt->bindParam(':ip', $IP, PDO::PARAM_STR);
			$stmt->execute(); 
			
			$key = sha1($form_code);
			$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET gameKey = :key WHERE id = :id;");
			$stmt->bindParam(':id', $result['id'], PDO::PARAM_INT);
			$stmt->bindParam(':key', $key, PDO::PARAM_STR);
			$stmt->execute();
			
			// Award badges
			if ($result['rank'] == 1) {
				// Check if the admin badge is owned
				$stmt = $GLOBALS['dbcon']->prepare("SELECT id FROM badges WHERE uid = :uid AND badgeId = 2");
				$stmt->bindParam(':uid', $result['id'], PDO::PARAM_INT);
				$stmt->execute();
				if ($stmt->rowCount() == 0) {
					// Award badge
					$query = "INSERT INTO badges (`uid`, `badgeId`) VALUES (:uid, 2);";
					$stmt = $GLOBALS['dbcon']->prepare($query);
					$stmt->bindParam(':uid', $result['id'], PDO::PARAM_INT);
					$stmt->execute();
				}
				
				// Check if the moderator badge is owned
				$stmt = $GLOBALS['dbcon']->prepare("SELECT id FROM badges WHERE uid = :uid AND badgeId = 3");
				$stmt->bindParam(':uid', $result['id'], PDO::PARAM_INT);
				$stmt->execute();
				if ($stmt->rowCount() == 0) {
					// Award badge
					$query = "INSERT INTO badges (`uid`, `badgeId`) VALUES (:uid, 3);";
					$stmt = $GLOBALS['dbcon']->prepare($query);
					$stmt->bindParam(':uid', $result['id'], PDO::PARAM_INT);
					$stmt->execute();
				}
			}
			
			if ($result['rank'] == 2) {
				// Check if the moderator badge is owned
				$stmt = $GLOBALS['dbcon']->prepare("SELECT id FROM badges WHERE uid = :uid AND badgeId = 3");
				$stmt->bindParam(':uid', $result['id'], PDO::PARAM_INT);
				$stmt->execute();
				if ($stmt->rowCount() == 0) {
					// Award badge
					$query = "INSERT INTO badges (`uid`, `badgeId`) VALUES (:uid, 3);";
					$stmt = $GLOBALS['dbcon']->prepare($query);
					$stmt->bindParam(':uid', $result['id'], PDO::PARAM_INT);
					$stmt->execute();
				}
				
				// Remove admin badge if any
				$query = "DELETE FROM badges WHERE badgeId = 2 AND uid = :uid";
				$stmt = $GLOBALS['dbcon']->prepare($query);
				$stmt->bindParam(':uid', $result['id'], PDO::PARAM_INT);
				$stmt->execute();
			}
			
			if ($result['rank'] == 0) {
				// Remove staff badges if any
				$query = "DELETE FROM badges WHERE badgeId = 2 AND uid = :uid";
				$stmt = $GLOBALS['dbcon']->prepare($query);
				$stmt->bindParam(':uid', $result['id'], PDO::PARAM_INT);
				$stmt->execute();
				
				$query = "DELETE FROM badges WHERE badgeId = 3 AND uid = :uid";
				$stmt = $GLOBALS['dbcon']->prepare($query);
				$stmt->bindParam(':uid', $result['id'], PDO::PARAM_INT);
				$stmt->execute();
			}
			
			// Check if the member badge is owned
			$stmt = $GLOBALS['dbcon']->prepare("SELECT id FROM badges WHERE uid = :uid AND badgeId = 5");
			$stmt->bindParam(':uid', $result['id'], PDO::PARAM_INT);
			$stmt->execute();
			if ($stmt->rowCount() == 0) {
				// Award badge
				$query = "INSERT INTO badges (`uid`, `badgeId`) VALUES (:uid, 5);";
				$stmt = $GLOBALS['dbcon']->prepare($query);
				$stmt->bindParam(':uid', $result['id'], PDO::PARAM_INT);
				$stmt->execute();
			}
			
			// Get forum post count
			$postCount = $result['posts'];
			$stmt = $GLOBALS['dbcon']->prepare("SELECT id FROM badges WHERE uid = :uid AND badgeId = 4");
			$stmt->bindParam(':uid', $result['id'], PDO::PARAM_INT);
			$stmt->execute();
			if ($stmt->rowCount() == 0 and $postCount > 999) {
				// Award badge
				$query = "INSERT INTO badges (`uid`, `badgeId`) VALUES (:uid, 4);";
				$stmt = $GLOBALS['dbcon']->prepare($query);
				$stmt->bindParam(':uid', $result['id'], PDO::PARAM_INT);
				$stmt->execute();
			}else{
				if ($postCount < 1000) {
					$query = "DELETE FROM badges WHERE badgeId = 4 AND uid = :uid";
					$stmt = $GLOBALS['dbcon']->prepare($query);
					$stmt->bindParam(':uid', $result['id'], PDO::PARAM_INT);
					$stmt->execute();
				}
			}
			
			$stmt = $GLOBALS['dbcon']->prepare("SELECT id FROM badges WHERE uid = :uid AND badgeId = 7");
			$stmt->bindParam(':uid', $result['id'], PDO::PARAM_INT);
			$stmt->execute();
			if ($stmt->rowCount() == 0 and $result['id'] < 101) {
				// Award badge
				$query = "INSERT INTO badges (`uid`, `badgeId`) VALUES (:uid, 7);";
				$stmt = $GLOBALS['dbcon']->prepare($query);
				$stmt->bindParam(':uid', $result['id'], PDO::PARAM_INT);
				$stmt->execute();
			}else{
				if ($result['id'] > 100) {
					$query = "DELETE FROM badges WHERE badgeId = 7 AND uid = :uid";
					$stmt = $GLOBALS['dbcon']->prepare($query);
					$stmt->bindParam(':uid', $result['id'], PDO::PARAM_INT);
					$stmt->execute();
				}
			}
			
			context::sendDiscordMessage("**User logged in** | ".$result['username']);
			
			echo 'success';
			exit;
		}else{
			$query = "SELECT * FROM loginAttempts WHERE ip = :ip";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->bindParam(':ip', $IP, PDO::PARAM_STR);
			$stmt->execute();
			
			$delete = false;
			if ($stmt->rowCount() == 3) {
				$query = "DELETE FROM loginAttempts WHERE ip = :ip;";
				$stmt = $GLOBALS['dbcon']->prepare($query);
				$stmt->bindParam(':ip', $IP, PDO::PARAM_STR);
				$stmt->execute();
				$delete = true;
			}
			
			if ($delete == true) {
				$count = 1;
			}else{
				$count = $stmt->rowCount()+1;
			}
			
			$query = "INSERT INTO loginAttempts (`ip`, `uid`, `count`) VALUES (:ip, :uid, :count);";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->bindParam(':ip', $IP, PDO::PARAM_STR);
			$stmt->bindParam(':uid', $result['id'], PDO::PARAM_INT);
			$stmt->bindParam(':count', $count, PDO::PARAM_INT);
			$stmt->execute();
			echo 'incorrect-password';
			exit;
		}
		
	}else{
		echo 'error';
		exit;
	}
?>
