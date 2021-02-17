<?php
	if (isset($_POST['csrf']) and isset($_POST['itemId'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		if ($_POST['csrf'] != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false) exit;
		
		$catalogId = $_POST['itemId'];
		if (is_array($catalogId)) exit;
		if (strlen($catalogId) == 0) exit;
		if (is_numeric($catalogId) == false) exit;
		
		$stmt = $GLOBALS['dbcon']->prepare("DELETE FROM wearing WHERE catalogId=:id AND uid=:user");
		$stmt->bindParam(':user', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':id', $catalogId, PDO::PARAM_INT);
		$stmt->execute();
		
		$query = "INSERT INTO renders (`render_id`, `type`) VALUES (:uid, 'character');";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':uid', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
	}
?>