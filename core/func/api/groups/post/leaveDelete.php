<?php
	if (isset($_POST['csrf']) and isset($_POST['groupId'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf'];
		$groupId = $_POST['groupId'];
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false or strlen($groupId) == 0) {
			echo 'error';
			exit;
		}
		
		// Get all group information.
		$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM groups WHERE id = :id");
		$stmt->bindParam(':id', $groupId, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		
		// Check if not owned by this user.
		if ($GLOBALS['userTable']['id'] != $result['cuid']) {
			echo 'error';
			exit;
		}
		
		// Check if not a member.
		$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM group_members WHERE uid = :uid AND gid = :id");
		$stmt->bindParam(':id', $groupId, PDO::PARAM_INT);
		$stmt->bindParam(':uid', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			echo 'error';
			exit;
		}
		
		// Delete group and all its members.
		$query = "DELETE FROM `group_members` WHERE `gid`=:groupId;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':groupId', $groupId, PDO::PARAM_INT);
		$stmt->execute();
						
		$query = "DELETE FROM `groups` WHERE `id`=:groupId;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':groupId', $groupId, PDO::PARAM_INT);
		$stmt->execute();
		
		echo 'success';
	}else{
		echo 'error';
	}
?>