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
			echo 'can-not-unban-yourself';
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
				echo 'can-not-unban-user';
				exit;
			}
		}else{
			if ($result['rank'] > 0) {
				echo 'can-not-unban-user';
				exit;
			}
		}
		
		if ($result['banned'] == 0) {
			echo 'user-not-banned';
			exit;
		}
		
		$query = "UPDATE `users` SET `banned`=0 WHERE `username`=:uname;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':uname', $username, PDO::PARAM_STR);
		$stmt->execute();
		
		$query = "UPDATE `users` SET `bantype`=0 WHERE `username`=:uname;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':uname', $username, PDO::PARAM_STR);
		$stmt->execute();
		
		$query = "UPDATE `users` SET `banreason`=NULL WHERE `username`=:uname;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':uname', $username, PDO::PARAM_STR);
		$stmt->execute();
		
		echo 'success';
	}else{
		echo 'error';
	}
?>