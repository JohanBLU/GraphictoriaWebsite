<?php
	if (isset($_POST['emailCode']) && isset($_POST['csrf'])) {
		$GLOBALS['bypassRedirect'] = true;
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$emailCode = $_POST['emailCode'];
		$csrf_token = $_POST['csrf'];
		if ($csrf_token != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false) {
			echo 'error';
			exit;
		}
		if (security::getUserEmailVerified() == true) {
			echo 'error';
			exit;
		}
		
		$timeSince =  round(abs(strtotime(context::getCurrentTime()) - strtotime($GLOBALS['userTable']['emailcodeTime'])) / 60,2);
		if (security::getEmailCode() == $emailCode and $timeSince < 5) {
			echo 'success';
			security::finishEmailVerification();
			exit;
		}else{
			echo 'incorrect-code';
			exit;
		}
	}else{
		echo 'error';
		exit;
	}
?>