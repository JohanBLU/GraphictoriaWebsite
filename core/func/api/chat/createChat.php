<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	if (isset($_POST['chatName']) && isset($_POST['csrfToken']) && $GLOBALS['loggedIn']) {
		$chatName = $_POST['chatName'];
		$csrfToken = $_POST['csrfToken'];
		if ($csrfToken != $GLOBALS['csrf_token']) {
			die("error");
		}
		
		if (strlen($chatName) == 0) die("no-name");
		if (strlen($chatName) > 64) die("chat-name-too-long");
		
		if (context::getTimeSince($GLOBALS['userTable']['lastUpload']) < 5) {
			die("rate-limit");
		}
		
		$stmt = $GLOBALS['dbcon']->prepare('UPDATE users SET lastUpload = NOW() WHERE id = :uid;');
		$stmt->bindParam(':uid', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		
		$chatKey = context::random_str(32);
		$chatJoinKey = context::random_str(8);
		$stmt = $GLOBALS['dbcon']->prepare("INSERT INTO chat_sessions (`chatName`, `chatKey`, `chatJoinKey`) VALUES (:chatName, :chatKey, :chatJoinKey);");
		$stmt->bindParam(':chatName', $chatName, PDO::PARAM_STR);
		$stmt->bindParam(':chatKey', $chatKey, PDO::PARAM_STR);
		$stmt->bindParam(':chatJoinKey', $chatJoinKey, PDO::PARAM_STR);
		$stmt->execute();
		
		$query = "SELECT id, chatName, chatKey FROM chat_sessions WHERE chatKey = :chatKey";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':chatKey', $chatKey, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$chatID = $result['id'];
		
		$stmt = $GLOBALS['dbcon']->prepare("INSERT INTO chat_members (`chat_id`, `userId`, `rank`) VALUES (:chatId, :userId, 1);");
		$stmt->bindParam(':chatId', $chatID, PDO::PARAM_STR);
		$stmt->bindParam(':userId', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		
		$rows[] = array('chat_id' => $result['id'],
					'chatName' => context::secureString($result['chatName']),
					'chatKey' => context::secureString($result['chatKey']));
		
		die(json_encode($rows));
	}else{
		die("error");
	}
?>