<?php
	if (isset($_POST['csrf']) and isset($_POST['username'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf'];
		$username = $_POST['username'];
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false or $GLOBALS['userTable']['rank'] == 0) {
			echo 'error';
			exit;
		}
		
		if (strlen($username) == 0) {
			echo 'missing-info';
			exit;
		}
		
		if (strtolower($username) == strtolower($GLOBALS['userTable']['username'])) {
			echo 'can-not-reward-yourself';
			exit;
		}
		
		if(!preg_match("/^[a-zA-Z0-9][\w\.]+[a-zA-Z0-9]$/", $username) == 1) {
			echo 'no-user';
			exit;
		}
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT id, rank, banned, lastAward2, posties FROM users WHERE username=:uname;");
		$stmt->bindParam(':uname', $username, PDO::PARAM_STR);
		$stmt->execute();
		if ($stmt->rowCount() == 0) {
			echo 'no-user';
			exit;
		}
		
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		// Rate limiting
		$timeSince =  round(abs(strtotime(date('Y-m-d H:i:s')) - strtotime($result['lastAward2'])) / 60,2);
		if ($timeSince < 5) {
			echo 'can-not-reward-user';
			exit;
		}
		
		$newPosties = $result['posties']+10;
		
		$query = "UPDATE `users` SET `posties`=:newPosties WHERE `username`=:uname;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':newPosties', $newPosties, PDO::PARAM_STR);
		$stmt->bindParam(':uname', $username, PDO::PARAM_STR);
		$stmt->execute();
		
		$query = "UPDATE `users` SET `lastAward2`=NOW() WHERE `username`=:uname;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':uname', $username, PDO::PARAM_STR);
		$stmt->execute();
		
		context::sendDiscordMessage($GLOBALS['userTable']['username'].' has awarded 10 posties to user **'.$username.'**');
		
		echo 'success';
	}else{
		echo 'error';
	}
?>