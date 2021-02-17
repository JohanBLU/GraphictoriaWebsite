<?php
	if (isset($_POST['csrf']) and isset($_POST['factorCode'])) {
		$GLOBALS['bypassRedirect'] = true;
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf'];
		$factorCode = $_POST['factorCode'];
		$factorCode = str_replace(" ", "", $factorCode);
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false) {
			echo 'error';
			exit;
		}
		
		if (strlen($factorCode) == 0) {
			echo 'missing-info';
			exit;
		}
		
		if ($GLOBALS['userTable']['2faEnabled'] == 0) {
			echo 'error';
			exit;
		}
		
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/libs/google/GoogleAuthenticator.php';
		$gAuth = new GoogleAuthenticator();
		if (!$gAuth->checkCode($GLOBALS['userTable']['authKey'], $factorCode)) {
			echo 'wrong-code';
			exit;
		}else{
			$stmt = $GLOBALS['dbcon']->prepare("UPDATE sessions SET factorFinish = 1 WHERE id = :id;");
			$stmt->bindParam(':id', $GLOBALS['sessionTable']['id'], PDO::PARAM_INT);
			$stmt->execute();
			echo 'success';
		}
	}else{
		echo 'error';
	}
?>