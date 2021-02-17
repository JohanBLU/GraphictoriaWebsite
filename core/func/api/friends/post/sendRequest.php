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
		
		$query = "SELECT * FROM `friends` WHERE `userId1` = :id AND `userId2` = :sid";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':sid', $userID, PDO::PARAM_INT);
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			echo 'error';
			exit;
		}
		
		$query = "SELECT * FROM `friendRequests` WHERE `senduid` = :id AND `recvuid` = :sid";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':sid', $userID, PDO::PARAM_INT);
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			echo 'error';
			exit;
		}
		
		$currentTime = context::getCurrentTime();
		$from_time = strtotime($GLOBALS['userTable']['lastFR']);
		$to_time = strtotime($currentTime);
		$timeSince =  round(abs($to_time - $from_time) / 60,2);
		if ($timeSince < 1) {
			echo 'rate-limit';
			exit;
		}else{
			$query = "UPDATE users SET lastFR = NOW() WHERE id=:id";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
			$stmt->execute();
		}
		
		$query = "INSERT INTO friendRequests (`senduid`, `recvuid`) VALUES (:userId1, :userId2);";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':userId1', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':userId2', $userID, PDO::PARAM_INT);
		$stmt->execute();
		
		echo 'success';
	}else{
		echo 'error';
	}
?>