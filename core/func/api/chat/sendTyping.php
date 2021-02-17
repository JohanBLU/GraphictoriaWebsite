<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	if (isset($_POST['csrfToken']) && $GLOBALS['loggedIn'] && isset($_POST['chatId'])) {
		$csrfToken = $_POST['csrfToken'];
		$chatId = $_POST['chatId'];
		if ($csrfToken != $GLOBALS['csrf_token']) die("error");
		$query = "UPDATE `chat_members` SET `lastType`=NOW() WHERE `chat_id`=:chatId AND `userId`=:userId;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':userId', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':chatId', $chatId, PDO::PARAM_STR);
		$stmt->execute();
		if ($stmt->rowCount() == 0) die("error");
		die("success");
	}else{
		die("error");
	}
?>