<?php
	if (isset($_POST['csrf']) and isset($_POST['groupName']) and isset($_POST['groupDescription'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf'];
		$groupName = $_POST['groupName'];
		$groupDescription = $_POST['groupDescription'];
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false) {
			echo 'error';
			exit;
		}
		
		$nameCheck = preg_replace("/[^ \w]+/", "", $groupName);
		$nameCheck = preg_replace('!\s+!', ' ', $nameCheck);
		if (strlen($nameCheck) == 0) {
			echo 'no-name';
			exit;
		}
		
		if (!preg_match("/^[\w*?!\/@#$%\^&*\(\) -]+$/", $groupName) == 1) {
			die("group-name-too-short");
		}
		
		if (strlen($nameCheck) < 5) {
			echo 'group-name-too-short';
			exit;
		}
		
		if (strlen($nameCheck) > 32 or strlen($groupName) > 32) {
			echo 'group-name-too-long';
			exit;
		}
		
		$descriptionCheck = preg_replace("/[^ \w]+/", "", $groupDescription);
		$descriptionCheck = preg_replace('/\s+/', '', $descriptionCheck);
		if (strlen($descriptionCheck) > 256 or strlen($groupDescription) > 256) {
			echo 'description-too-long';
			exit;
		}
		
		if ($GLOBALS['userTable']['coins'] < 50) {
			echo 'no-coins';
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
		
		$newCoins = $GLOBALS['userTable']['coins']-50;
		$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET coins = :coins WHERE id = :user;");
		$stmt->bindParam(':coins', $newCoins, PDO::PARAM_INT);
		$stmt->bindParam(':user', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		
		$query = "INSERT INTO groups (`cuid`, `name`, `description`) VALUES (:cuid, :name, :description);";
		$stmt = $dbcon->prepare($query);
		$stmt->bindParam(':cuid', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':name', $groupName, PDO::PARAM_STR);
		$stmt->bindParam(':description', $groupDescription, PDO::PARAM_STR);
		$stmt->execute();
		
		$stmt = $dbcon->prepare("SELECT * FROM groups WHERE cuid = :id ORDER BY id DESC LIMIT 1;");
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		echo $result['id'];
	}else{
		echo 'error';
	}
?>