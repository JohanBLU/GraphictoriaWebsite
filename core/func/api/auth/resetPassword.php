<?php
	if (isset($_POST['csrf']) and isset($_POST['password1']) and isset($_POST['password2']) and isset($_POST['key']) and isset($_POST['userID'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf'];
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];
		$key = $_POST['key'];
		$userID = $_POST['userID'];
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == true) {
			echo 'error';
			exit;
		}
		
		$query = "SELECT * FROM passwordresets WHERE userid = :uid AND `key` = :key";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':uid', $userID, PDO::PARAM_INT);
		$stmt->bindParam(':key', $key, PDO::PARAM_STR); 
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($stmt->rowCount() == 0 or $result['used'] == 1) {
			echo 'invalid-key';
			exit;
		}
		
		$currentTime = date('Y-m-d H:i:s');
		$to_time = strtotime($currentTime);
		$from_time = strtotime($result['date']);
		$timeSince =  round(abs($to_time - $from_time) / 60,2);
		if ($timeSince > 5) {
			echo 'key-expired';
			exit;
		}
		
		if ($password1 != $password2) {
			echo 'password-mismatch';
			exit;
		}
		
		if (strlen($password1) > 42) {
			echo 'password-too-long';
			exit;
		}
		
		if (strlen($password1) < 6) {
			echo 'password-too-short';
			exit;
		}
		
		$salt = '$2a$07$'.uniqid(mt_rand(), true).'$';
		$hash = crypt($password1, $salt);
		
		$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET password_salt = :salt WHERE id = :user;");
		$stmt->bindParam(':user', $userID, PDO::PARAM_INT);
		$stmt->bindParam(':salt', $salt, PDO::PARAM_STR);
		$stmt->execute();
										
		$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET password_hash = :hash WHERE id = :user;");
		$stmt->bindParam(':user', $userID, PDO::PARAM_INT);
		$stmt->bindParam(':hash', $hash, PDO::PARAM_STR);
		$stmt->execute();
							
		$stmt = $GLOBALS['dbcon']->prepare("UPDATE passwordresets SET used = 1 WHERE `key` = :key AND userid = :uid;");
		$stmt->bindParam(':key', $key, PDO::PARAM_STR);
		$stmt->bindParam(':uid', $userID, PDO::PARAM_INT);
		$stmt->execute();
							
		$stmt = $GLOBALS['dbcon']->prepare("DELETE FROM sessions WHERE userId = :uid;");
		$stmt->bindParam(':uid', $userID, PDO::PARAM_INT);
		$stmt->execute();

		echo 'success';
	}else{
		echo 'error';
	}
?>