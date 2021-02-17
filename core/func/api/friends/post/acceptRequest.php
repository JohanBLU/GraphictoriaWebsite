<?php
	if (isset($_POST['csrf']) and isset($_POST['userID'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf'];
		$userID = $_POST['userID'];
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false or strlen($userID) == 0) {
			echo 'error';
			exit;
		}
		
		if ($userID == $GLOBALS['userTable']['id']) {
			echo 'error';
			exit;
		}
		
		$query = "SELECT * FROM `friendRequests` WHERE `recvuid` = :id AND `senduid` = :sid";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':sid', $userID, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($stmt->rowCount() == 0) {
			echo 'error';
			exit;
		}
		
		if ($result['senduid'] == $GLOBALS['userTable']['id'] and $stmt->rowCount() > 0) {
			$query = "DELETE FROM `friendRequests` WHERE `senduid` = :sid AND `recvuid` = :id;";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
			$stmt->bindParam(':sid', $userID, PDO::PARAM_INT);
			$stmt->execute();
			echo 'error';
			exit;
		}
		
		$query = "SELECT * FROM `friends` WHERE `userId1` = :id AND `userId2` = :sid";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':sid', $userID, PDO::PARAM_INT);
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			$query = "DELETE FROM `friendRequests` WHERE `senduid` = :sid AND `recvuid` = :id;";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
			$stmt->bindParam(':sid', $userID, PDO::PARAM_INT);
			$stmt->execute();
			echo 'error';
			exit;
		}
		
		$query = "SELECT * FROM `friends` WHERE `userId1` = :sid AND `userId2` = :id";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':sid', $userID, PDO::PARAM_INT);
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			$query = "DELETE FROM `friendRequests` WHERE `senduid` = :id AND `recvuid` = :sid;";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
			$stmt->bindParam(':sid', $userID, PDO::PARAM_INT);
			$stmt->execute();
			echo 'error';
			exit;
		}
					
		$query = "INSERT INTO friends (`userId1`, `userId2`) VALUES (:userId1, :userId2);";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':userId1', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':userId2', $userID, PDO::PARAM_INT);
		$stmt->execute();
						
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':userId1', $userID, PDO::PARAM_INT);
		$stmt->bindParam(':userId2', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		
		$query = "DELETE FROM `friendRequests` WHERE `senduid` = :sid AND `recvuid` = :id;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':sid', $userID, PDO::PARAM_INT);
		$stmt->execute();
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM users WHERE id = :id");
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		$resultuinfo = $stmt->fetch(PDO::FETCH_ASSOC);
		$message = '<b><a href="/profile.php?id='.$resultuinfo['id'].'">'.htmlentities($resultuinfo['username'], ENT_QUOTES, "UTF-8").'</a></b> has accepted your friend request. Start a conversation by replying!';
		$query = "INSERT INTO messages (`recv_uid`, `sender_uid`, `title`, `content`) VALUES (:userId1, :userId2, 'Friend Request Accepted', :msg);";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':userId1', $userID, PDO::PARAM_INT);
		$stmt->bindParam(':userId2', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':msg', $message, PDO::PARAM_STR);
		$stmt->execute();
		
		echo 'success';
	}else{
		echo 'error';
	}
?>