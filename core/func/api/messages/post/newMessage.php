<?php
	if (isset($_POST['messageTitle']) and isset($_POST['messageContent']) and isset($_POST['csrf']) and isset($_POST['userID'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$messageTitle = $_POST['messageTitle'];
		$messageContent = $_POST['messageContent'];
		$csrf = $_POST['csrf'];
		$userID = $_POST['userID'];
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false or strlen($userID) == 0) {
			echo 'error';
			exit;
		}
		
		if (strlen($messageTitle) < 5) {
			echo 'title-too-short';
			exit;
		}
		
		if (strlen($messageTitle) > 128) {
			echo 'title-too-long';
			exit;
		}
		
		if (strlen($messageContent) < 5) {
			echo 'content-too-short';
			exit;
		}
		
		if (strlen($messageContent) > 30000) {
			echo 'content-too-long';
			exit;
		}
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT lastPost, joinDate, rank FROM users WHERE id = :id");
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$timeSince =  round(abs(strtotime(date('Y-m-d H:i:s')) - strtotime($result['lastPost'])) / 60,2);
		if ($timeSince < 0.2 and $result['rank'] == 0) {
			echo 'rate-limit';
			exit;
		}
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT id, banned FROM users WHERE id = :id");
		$stmt->bindParam(':id', $userID, PDO::PARAM_INT);
		$stmt->execute();		
		if ($stmt->rowCount() == 0) {
			echo 'no-user';
			exit;
		}
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($result['banned'] == 1) {
			echo 'user-banned';
			exit;
		}
		
		$query = "INSERT INTO messages (`recv_uid`, `sender_uid`, `title`, `content`) VALUES (:recv_uid, :sender_uid, :title, :content);";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':sender_uid', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':recv_uid', $userID, PDO::PARAM_INT);
		$stmt->bindParam(':title', $messageTitle, PDO::PARAM_STR);
		$stmt->bindParam(':content', $messageContent, PDO::PARAM_STR);
		$stmt->execute();
		
		$query = "UPDATE `users` SET `lastPost`=NOW() WHERE `id`=:id;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		
		echo 'success';
	}else{
		echo 'error';
	}
?>