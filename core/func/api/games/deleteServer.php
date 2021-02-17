<?php
	if (isset($_POST['csrf']) and isset($_POST['serverID'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf'];
		$serverID = $_POST['serverID'];
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false or strlen($serverID) == 0) {
			echo 'error';
			exit;
		}
		
		$stmt = $dbcon->prepare("SELECT * FROM games WHERE id = :id;");
		$stmt->bindParam(':id', $serverID, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		
		if ($result['creator_uid'] != $GLOBALS['userTable']['id'] && $GLOBALS['userTable']['rank'] == 0) {
			echo 'error';
		}
		
		if ($result['dedi'] == 1 && $GLOBALS['userTable']['rank'] != 1) die("error");
		
		$stmt = $dbcon->prepare("DELETE FROM games WHERE id = :id;");
		$stmt->bindParam(':id', $serverID, PDO::PARAM_INT);
		$stmt->execute();
		
		echo 'success';
	}else{
		echo 'error';
	}
?>