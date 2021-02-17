<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	if ($GLOBALS['loggedIn'] && isset($_GET['chatId'])) {
		$chatId = $_GET['chatId'];
		if (is_array($chatId)) exit;
		
		$stmt = $GLOBALS['dbcon']->prepare('SELECT lastType, userId FROM chat_members WHERE chat_id = :chatID');
		$stmt->bindParam(':chatID', $chatId, PDO::PARAM_INT);
		$stmt->execute();
		$rows_typing = array();
		$usernames = array();
		$count = 0;
		foreach($stmt as $result) {
			if (context::getTimeSince($result['lastType']) < 0.06 && $result['userId'] != $GLOBALS['userTable']['id']) {
				$count++;
				$username = context::IDToUsername($result['userId']);
				$usernames[] = $username;
			}
		}
		
		if ($count == 0) {
			$mode = "none";
		}else if ($count < 3) {
			$mode = "showTyping";
		}else {
			$mode = "severalTyping";
		}
		$rows_typing[] = array('usernames' => $usernames, 'mode' => $mode);
		$json = context::jsonToSingle(json_encode($rows_typing));
		die($json);
	}else{
		die("error");
	}
?>