<?php
	if (isset($_POST['csrf']) and isset($_POST['descriptionValue']) and isset($_POST['groupId'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf'];
		$descriptionValue = $_POST['descriptionValue'];
		$groupId = $_POST['groupId'];
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false) {
			echo 'error';
			exit;
		}
		$descriptionCheck = preg_replace("/[^ \w]+/", "", $descriptionValue);
		$descriptionCheck = preg_replace('/\s+/', '', $descriptionCheck);
		if (strlen($descriptionCheck) > 256 or strlen($descriptionValue) > 256) {
			echo 'description-too-long';
			exit;
		}
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM groups WHERE id = :id");
		$stmt->bindParam(':id', $groupId, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($result['cuid'] != $GLOBALS['userTable']['id'] and $GLOBALS['userTable']['rank'] == 0) {
			echo 'error';
			exit;
		}
		
		$query = "UPDATE `groups` SET `description`=:description WHERE `id`=:id;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':id', $groupId, PDO::PARAM_INT);
		$stmt->bindParam(':description', $descriptionValue, PDO::PARAM_STR);
		$stmt->execute();
		echo 'success';
	}else{
		echo 'error';
	}
?>