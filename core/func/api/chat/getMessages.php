<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	if ($GLOBALS['loggedIn'] && isset($_GET['id']) && isset($_GET['timestamp'])) {
		$id = $_GET['id'];
		$timestamp = $_GET['timestamp'];
		// Check if current user belongs to the chat.
		$stmt = $GLOBALS['dbcon']->prepare('SELECT id FROM chat_members WHERE userId = :userId AND chat_id = :id');
		$stmt->bindParam(':userId', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		if ($stmt->rowCount() == 0) api::returnString("error");
		
		$stmtChat = $GLOBALS['dbcon']->prepare('SELECT * FROM chat_messages WHERE chat_id = :chatID AND date > :timestamp');
		$stmtChat->bindParam(':chatID', $id, PDO::PARAM_INT);
		$stmtChat->bindParam(':timestamp', $timestamp, PDO::PARAM_INT);
		$stmtChat->execute();
		$rows = array();
		foreach($stmtChat as $resultChat) {
			$stmt = $GLOBALS['dbcon']->prepare('SELECT id, username, rank FROM users WHERE id = :userId');
			$stmt->bindParam(':userId', $resultChat['userId'], PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$setRight = false;
			if ($result['username'] == $GLOBALS['userTable']['username']) $setRight = true;
			if ($result['rank'] > 0) {
				$color = "red";
				$rank = 1;
			}else{
				$color = "black";
				$rank = 0;
			}
			
			$message = context::secureString($resultChat['message']);
			$message = context::parseEmoticon($message);
			
			$rows[] = array('messageId' => $resultChat['id'],
				'userId' => $resultChat['userId'],
				'username' => $result['username'],
				'staff' => $rank,
				'setRight' => $setRight,
				'userColor' => $color,
				'date' => $resultChat['date'],
				'userID' => $result['id'],
				'message' => $message);
		}
		// Get all chat messages
		die(json_encode($rows));
	}else{
		die("error");
	}
?>