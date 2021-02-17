<?php
	if (isset($_POST['csrf']) and isset($_POST['finalCode'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf'];
		$finalCode = $_POST['finalCode'];
		$finalCode = str_replace(" ", "", $finalCode);
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false) {
			echo 'error';
			exit;
		}
		
		if (strlen($finalCode) == 0) {
			echo 'missing-info';
			exit;
		}
		
		if ($GLOBALS['userTable']['2faInit'] == 0 or $GLOBALS['userTable']['2faEnabled'] == 1) {
			echo 'error';
			exit;
		}else{
			include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/libs/google/GoogleAuthenticator.php';
			$gAuth = new GoogleAuthenticator();
			if (!$gAuth->checkCode($GLOBALS['userTable']['authKey'], $finalCode)) {
				echo 'wrong-code';
				exit;
			}
			
			$query = "UPDATE `users` SET `2faInit`=1 WHERE `id`=:id;";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
			$stmt->execute();
			
			$query = "UPDATE `users` SET `2faEnabled`=1 WHERE `id`=:id;";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
			$stmt->execute();
			
			$query = "UPDATE `sessions` SET `factorFinish`=1 WHERE `id`=:id;";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->bindParam(':id', $GLOBALS['sessionTable']['id'], PDO::PARAM_INT);
			$stmt->execute();
			echo 'success';
		}
	}else{
		echo 'error';
	}
?>