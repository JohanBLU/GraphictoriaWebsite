<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	if ($GLOBALS['loggedIn']) {
		$stmt = $GLOBALS['dbcon']->prepare('SELECT chat_id FROM chat_members WHERE userId = :userId');
		$stmt->bindParam(':userId', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		$rows = array();
		foreach($stmt as $result) {
			$chatID = $result['chat_id'];
			$stmtChat = $GLOBALS['dbcon']->prepare('SELECT id, chatName, chatKey FROM chat_sessions WHERE id = :chatID');
			$stmtChat->bindParam(':chatID', $chatID, PDO::PARAM_INT);
			$stmtChat->execute();
			foreach($stmtChat as $resultChat) {
				$rows[] = array('chat_id' => $resultChat['id'],
					'chatName' => context::secureString($resultChat['chatName']),
					'chatKey' => context::secureString($resultChat['chatKey']));
			}
		}
		die(json_encode($rows));
	}else{
		die("error");
	}
?>