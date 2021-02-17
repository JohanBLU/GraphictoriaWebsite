<?php
	if (isset($_POST['csrf'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf'];
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false) die("error");
		
		context::requestImage($GLOBALS['userTable']['id'], "character");
	}else{
		echo 'error';
	}
?>