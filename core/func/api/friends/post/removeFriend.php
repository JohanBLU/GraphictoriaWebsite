<?php
	if (isset($_POST['csrf']) and isset($_POST['userID'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf'];
		$userID = $_POST['userID'];
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false or strlen($userID) == 0) {
			echo 'error';
			exit;
		}
		
		$query = "DELETE FROM `friends` WHERE `userId1` = :sid AND `userId2` = :id;";
		$stmt = $dbcon->prepare($query);
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':sid', $userID, PDO::PARAM_INT);
		$stmt->execute();
					
		$stmt = $dbcon->prepare($query);
		$stmt->bindParam(':id', $userID, PDO::PARAM_INT);
		$stmt->bindParam(':sid', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		
		echo 'success';
	}else{
		echo 'error';
	}
?>