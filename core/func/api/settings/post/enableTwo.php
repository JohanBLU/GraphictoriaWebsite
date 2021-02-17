<?php
	if (isset($_POST['csrf'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf'];
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false) {
			echo 'error';
			exit;
		}
		
		if ($GLOBALS['userTable']['2faInit'] == 1 or $GLOBALS['userTable']['2faEnabled'] == 1) {
			echo 'error';
			exit;
		}else{
			$query = "UPDATE `users` SET `2faInit`=1 WHERE `id`=:id;";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
			$stmt->execute();
			
			include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/libs/google/GoogleAuthenticator.php';
			$gAuth = new GoogleAuthenticator();
			$code = $gAuth->generateSecret();
			
			$query = "UPDATE users SET `authKey`=:code WHERE `id`=:uid;";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->bindParam(':code', $code, PDO::PARAM_STR);
			$stmt->bindParam(':uid', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
			$stmt->execute();
		}
		echo 'success';
	}else{
		echo 'error';
	}
?>