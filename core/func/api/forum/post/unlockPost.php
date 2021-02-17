<?php
	if (isset($_POST['csrf']) and isset($_POST['postId'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf'];
		$postId = $_POST['postId'];
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false or strlen($postId) == 0 or $GLOBALS['userTable']['rank'] == 0) {
			echo 'error';
			exit;
		}
		$query = "UPDATE `topics` SET `locked`=0 WHERE `id`=:id AND `developer`=0;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':id', $postId, PDO::PARAM_INT);
		$stmt->execute();
		
		$query = "UPDATE `topics` SET `lockedByStaff`=0 WHERE `id`=:id AND `developer`=0;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':id', $postId, PDO::PARAM_INT);
		$stmt->execute();
		echo 'success';
	}else{
		echo 'error';
	}
?>