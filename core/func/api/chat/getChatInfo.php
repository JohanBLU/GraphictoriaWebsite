<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	if ($GLOBALS['loggedIn'] && isset($_GET['id'])) {
		$id = $_GET['id'];
		// Check if current user belongs to the chat.
		$stmt = $GLOBALS['dbcon']->prepare('SELECT id FROM chat_members WHERE userId = :userId AND chat_id = :id');
		$stmt->bindParam(':userId', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		if ($stmt->rowCount() == 0) die("error");
		
		$stmt = $GLOBALS['dbcon']->prepare('SELECT chatName, chatJoinKey FROM chat_sessions WHERE id = :chatID');
		$stmt->bindParam(':chatID', $id, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$stmt = $GLOBALS['dbcon']->prepare('SELECT id FROM chat_members WHERE chat_id = :chatID');
		$stmt->bindParam(':chatID', $id, PDO::PARAM_INT);
		$stmt->execute();
		$chatMemberCount = $stmt->rowCount();
		
		$rows[] = array('chatMembers' => $chatMemberCount, 'chatName' => context::secureString($result['chatName']), 'joinKey' => context::secureString($result['chatJoinKey']));
		die(json_encode($rows));
	}else{
		die("error");
	}
?>