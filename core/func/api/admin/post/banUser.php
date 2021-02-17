<?php
	if (isset($_POST['csrf']) and isset($_POST['username']) and isset($_POST['banReason']) and isset($_POST['duration'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf'];
		$username = $_POST['username'];
		$banReason = $_POST['banReason'];
		$duration = $_POST['duration'];
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false or $GLOBALS['userTable']['rank'] == 0) {
			echo 'error';
			exit;
		}
		
		if (strlen($username) == 0 or strlen($banReason) == 0 or strlen($duration) == 0) {
			echo 'missing-info';
			exit;
		}
		
		if (is_numeric($duration == false) || $duration > 5) die("invalid-duration");
		
		if (strtolower($username) == strtolower($GLOBALS['userTable']['username'])) {
			echo 'can-not-ban-yourself';
			exit;
		}
		
		if (strlen($banReason) > 512) {
			echo 'reason-too-long';
			exit;
		}
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT id, rank, banned, email, username FROM users WHERE username=:uname;");
		$stmt->bindParam(':uname', $username, PDO::PARAM_STR);
		$stmt->execute();
		if ($stmt->rowCount() == 0) {
			echo 'no-user';
			exit;
		}
		
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$email = $result['email'];
		$udb = $result['username'];
		if ($GLOBALS['userTable']['rank'] == 1) {
			if ($result['rank'] == 1) {
				echo 'can-not-ban-user';
				exit;
			}
		}else{
			if ($result['rank'] > 0) {
				echo 'can-not-ban-user';
				exit;
			}
		}
		
		if ($result['banned'] == 1) {
			echo 'user-already-banned';
			exit;
		}
		
		$query = "UPDATE `users` SET `banned`=1 WHERE `username`=:uname;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':uname', $username, PDO::PARAM_STR);
		$stmt->execute();
		
		$query = "UPDATE `users` SET `bantype`=:type WHERE `username`=:uname;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':uname', $username, PDO::PARAM_STR);
		$stmt->bindParam(':type', $duration, PDO::PARAM_STR);
		$stmt->execute();
		
		$query = "UPDATE `users` SET `banreason`=:reason WHERE `username`=:uname;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':uname', $username, PDO::PARAM_STR);
		$stmt->bindParam(':reason', $banReason, PDO::PARAM_STR);
		$stmt->execute();
		
		$query = "UPDATE `users` SET `bantime`=NOW() WHERE `username`=:uname;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':uname', $username, PDO::PARAM_STR);
		$stmt->execute();
		
		// Get userID
		$query = "SELECT id FROM users WHERE username = :uname";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':uname', $username, PDO::PARAM_STR); 
		$stmt->execute(); 
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$userID = $result['id'];
			
		$query = "INSERT INTO `banlogs` (`banned_by_uid`, `banned_by_uname`, `banned_uid`, `banned_uname`, `reason`, `bantype`) VALUES (:bannedbyuid, :bannedbyuname, :banneduid, :banneduname, :reason, :bantype);";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':bannedbyuid', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':bannedbyuname', $GLOBALS['userTable']['username'], PDO::PARAM_STR);
		$stmt->bindParam(':banneduid', $userID, PDO::PARAM_INT);
		$stmt->bindParam(':banneduname', $username, PDO::PARAM_STR);
		$stmt->bindParam(':reason', $banReason, PDO::PARAM_STR);
		$stmt->bindParam(':bantype', $duration, PDO::PARAM_INT);
		$stmt->execute();
		
		context::sendDiscordMessage(":first_place: ".$GLOBALS['userTable']['username']." has banned **".$username."** for reason **".$banReason."** (banType=".$duration.")");
		
		echo 'success';
	}else{
		echo 'error';
	}
?>