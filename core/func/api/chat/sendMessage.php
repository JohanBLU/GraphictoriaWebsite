<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	if (isset($_POST['message']) && isset($_POST['chatId']) && isset($_POST['csrfToken']) && $GLOBALS['loggedIn']) {
		$csrfToken = $_POST['csrfToken'];
		$message = $_POST['message'];
		$chatId = $_POST['chatId'];
		if ($csrfToken != $GLOBALS['csrf_token']) die("error");
		
		// Check if the chatId is valid and if the chat exists, also obtain decryption/encryption key
		$stmt = $GLOBALS['dbcon']->prepare('SELECT id, chatKey FROM chat_sessions WHERE id = :chatId');
		$stmt->bindParam(':chatId', $_POST['chatId'], PDO::PARAM_INT);
		$stmt->execute();
		if ($stmt->rowCount() == 0) die("error");
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		
		// Check if everything matches up
		if (strlen($message) > 128) die("message-too-long");
		
		if (strlen($message) < 1) die("message-too-short");
		
		// Also check if the current user is indeed in the chat.
		$stmt = $GLOBALS['dbcon']->prepare('SELECT id FROM chat_members WHERE userId = :userId AND chat_id = :id');
		$stmt->bindParam(':userId', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':id', $chatId, PDO::PARAM_INT);
		$stmt->execute();
		if ($stmt->rowCount() == 0) die("error");
		
		// If we're still here, we can go and add the message, encrypt message again
		$time = time();
		$stmt = $GLOBALS['dbcon']->prepare("INSERT INTO chat_messages (`chat_id`, `userId`, `message`, `date`) VALUES (:chatId, :userId, :message, :timestamp);");
		$stmt->bindParam(':chatId', $chatId, PDO::PARAM_INT);
		$stmt->bindParam(':userId', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':message', $message, PDO::PARAM_STR);
		$stmt->bindParam(':timestamp', $time, PDO::PARAM_INT);
		$stmt->execute();
		
		die("success");
	}else{
		die("error");
	}
?>