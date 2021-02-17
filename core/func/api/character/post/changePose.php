<?php
	if (isset($_POST['csrf']) and isset($_POST['pose'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf'];
		$pose = $_POST['pose'];
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false or strlen($pose) == 0) die("error");
		
		$poseID = 0;
		if ($pose == "walking") $poseID = 1;
		if ($pose == "sitting") $poseID = 2;
		if ($pose == "overlord") $poseID = 3;
		if ($pose == "normal") $poseID = 0;
		
		$query = "UPDATE users SET charap = :pose WHERE id = :uid";
		$stmt = $dbcon->prepare($query);
		$stmt->bindParam(':uid', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':pose', $poseID, PDO::PARAM_INT);
		$stmt->execute();
		
		context::requestImage($GLOBALS['userTable']['id'], "character");
	}else{
		echo 'error';
	}
?>