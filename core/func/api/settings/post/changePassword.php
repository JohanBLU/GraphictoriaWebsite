<?php
	if (isset($_POST['csrf']) and isset($_POST['newPassword1']) and isset($_POST['newPassword2']) and isset($_POST['currentPassword'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf'];
		$newPassword1 = $_POST['newPassword1'];
		$newPassword2 = $_POST['newPassword2'];
		$currentPassword = $_POST['currentPassword'];
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false) {
			echo 'error';
			exit;
		}
		
		if (strlen($newPassword1) == 0 or strlen($newPassword2) == 0 or strlen($currentPassword) == 0) {
			echo 'missing-info';
			exit;
		}
		
		if ($newPassword1 != $newPassword2) {
			echo 'confirm-failed';
			exit;
		}
		
		if (strlen($newPassword1) < 6) {
			echo 'password-too-short';
			exit;
		}
		
		if (strlen($newPassword1) > 40) {
			echo 'password-too-long';
			exit;
		}
		
		$auth_hash = crypt($currentPassword, $GLOBALS['userTable']['password_salt']);
		if ($auth_hash != $GLOBALS['userTable']['password_hash']) {
			echo 'wrong-password';
			exit;
		}
		
		$salt = '$2a$07$'.uniqid(mt_rand(), true).'$';
		$hash = crypt($newPassword1, $salt);
		$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET password_salt = :salt WHERE id = :id;");
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':salt', $salt, PDO::PARAM_STR);
		$stmt->execute();
				
		$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET password_hash = :hash WHERE id = :id;");
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':hash', $hash, PDO::PARAM_STR);
		$stmt->execute();
				
		$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET passwordVersion = 2 WHERE id = :id;");
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
										
		$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET password = NULL WHERE id = :id;");
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		
		$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET passwordChangeIP = :ip WHERE id = :id;");
		$IP = auth::getIP();
		$stmt->bindParam(':ip', $IP, PDO::PARAM_STR);
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		
		$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET passwordChangeDate = NOW() WHERE id = :id;");
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		
		$stmt = $GLOBALS['dbcon']->prepare("DELETE FROM sessions WHERE userId = :userId");
		$stmt->bindParam(':userId', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		echo 'success';
	}else{
		echo 'error';
	}
?>
