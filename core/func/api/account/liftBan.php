<?php
	if (isset($_POST['csrf'])) {
		$GLOBALS['bypassRedirect'] = true;
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf_token = $_POST['csrf'];
		if ($csrf_token != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false or $GLOBALS['userTable']['banned'] == 0) die("error");
		
		if ($GLOBALS['userTable']['bantype'] != 5 and $GLOBALS['userTable']['bantype'] != 0) {
			$timeSince = round(abs(strtotime(context::getCurrentTime()) - strtotime($GLOBALS['userTable']['bantime'])) / 60,2);
			if ($GLOBALS['userTable']['bantype'] == 1) {
				security::liftBan();
			}
			if ($GLOBALS['userTable']['bantype'] == 2) {
				if ($timeSince > 1440) {
					security::liftBan();
				}else{
					security::returnLiftError();
				}
			}
			if ($GLOBALS['userTable']['bantype'] == 3) {
				if ($timeSince > 10080) {
					security::liftBan();
				}else{
					security::returnLiftError();
				}
			}
			if ($GLOBALS['userTable']['bantype'] == 4) {
				if ($timeSince > 43200) {
					security::liftBan();
				}else{
					security::returnLiftError();
				}
			}
		}else{
			die("error");
		}
	}else{
		die("error");
	}
?>