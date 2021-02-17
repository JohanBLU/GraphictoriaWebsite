<?php
	if (isset($_POST['csrf']) and isset($_POST['groupId'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf'];
		$groupId = $_POST['groupId'];
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false or strlen($groupId) == 0) {
			echo 'error';
			exit;
		}
		
		// Check if not already a member.
		$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM group_members WHERE uid = :uid AND gid = :id");
		$stmt->bindParam(':id', $groupId, PDO::PARAM_INT);
		$stmt->bindParam(':uid', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			echo 'error';
			exit;
		}
		
		// Get all group information.
		$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM groups WHERE id = :id");
		$stmt->bindParam(':id', $groupId, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		
		// Check if owned by this user.
		if ($GLOBALS['userTable']['id'] == $result['cuid']) {
			echo 'error';
			exit;
		}
		
		$count = 0;
		$stmt = $GLOBALS['dbcon']->prepare("SELECT id FROM group_members WHERE uid = :id;");
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		$count = $count + $stmt->rowCount();
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT id FROM groups WHERE cuid = :id;");
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		$count = $count + $stmt->rowCount();
		
		if ($count > 9) {
			echo 'in-too-many-groups';
			exit;
		}
		
		// Join group
		$query = "INSERT INTO group_members (`uid`, `gid`) VALUES (:uid, :gid);";
		$stmt = $dbcon->prepare($query);
		$stmt->bindParam(':uid', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':gid', $groupId, PDO::PARAM_STR);
		$stmt->execute();
	}else{
		echo 'error';
	}
?>