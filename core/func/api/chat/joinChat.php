<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/libs/aes/GibberishAES.php';
	if (isset($_POST['chatCode']) && isset($_POST['csrfToken']) && $GLOBALS['loggedIn']) {
		$chatCode = $_POST['chatCode'];
		$csrfToken = $_POST['csrfToken'];
		if ($csrfToken != $GLOBALS['csrf_token']) die("error");
		if (strlen($chatCode) == 0) die("no-code");
		if (strlen($chatCode) > 64) die("chat-code-too-long");
		
		$query = "SELECT id, chatJoinKey, chatName, chatKey FROM chat_sessions WHERE chatJoinKey = :chatKey";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':chatKey', $chatCode, PDO::PARAM_STR);
		$stmt->execute();
		if ($stmt->rowCount() == 0) die("invalid-code");
		
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$chatID = $result['id'];
		$chatKey = $result['chatKey'];
		
		$query = "SELECT id FROM chat_members WHERE chat_id = :chatId AND userId = :userId";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':chatId', $chatID, PDO::PARAM_INT);
		$stmt->bindParam(':userId', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		if ($stmt->rowCount() > 0) die("already-in");
		
		$stmt = $GLOBALS['dbcon']->prepare("INSERT INTO chat_members (`chat_id`, `userId`, `rank`) VALUES (:chatId, :userId, 0);");
		$stmt->bindParam(':chatId', $chatID, PDO::PARAM_INT);
		$stmt->bindParam(':userId', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		
		$time = time();
		$message = $GLOBALS['userTable']['username'].' has joined';
		$stmt = $GLOBALS['dbcon']->prepare("INSERT INTO chat_messages (`chat_id`, `userId`, `message`, `bot`, `date`) VALUES (:chatId, 0, :message, 1, :time);");
		$stmt->bindParam(':chatId', $chatID, PDO::PARAM_INT);
		$stmt->bindParam(':message', $message, PDO::PARAM_STR);
		$stmt->bindParam(':time', $time, PDO::PARAM_INT);
		$stmt->execute();
		
		$rows[] = array('chat_id' => $result['id'],
					'chatName' => context::secureString($result['chatName']),
					'chatKey' => context::secureString($result['chatKey']));
		
		die(json_encode($rows));
	}else{
		die("error");
	}
?>