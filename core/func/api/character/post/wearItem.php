<?php
	if (isset($_POST['csrf']) and isset($_POST['itemId']) and isset($_POST['type'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		if ($_POST['csrf'] != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false) {
			exit;
		}
		
		$type = $_POST['type'];
		if (is_array($type)) exit;
		if (strlen($type) == 0) exit;
		
		$catalogId = $_POST['itemId'];
		if (is_array($catalogId)) exit;
		if (strlen($catalogId) == 0) exit;
		if (is_numeric($catalogId) == false) exit;
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT id FROM wearing WHERE uid = :uid AND catalogid = :id");
		$stmt->bindParam(':id', $catalogId, PDO::PARAM_INT);
		$stmt->bindParam(':uid', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			exit;
		}
		
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT id FROM ownedItems WHERE catalogId = :id AND uid = :uid");
		$stmt->bindParam(':id', $catalogId, PDO::PARAM_INT);
		$stmt->bindParam(':uid', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		if ($stmt->rowCount() == 0) {
			exit;
		}
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT id, deleted, assetid, datafile FROM catalog WHERE id = :id");
		$stmt->bindParam(':id', $catalogId, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($result['deleted'] == 1) {
			exit;
		}
		$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM wearing WHERE uid = :uid AND type = :type");
		$stmt->bindParam(':type', $type, PDO::PARAM_STR);
		$stmt->bindParam(':uid', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		$resultcheck = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($type == "hats") {
			if ($stmt->rowCount() == 5) {
				exit;
			}
		}else{
			if ($stmt->rowCount() > 0) {
				$stmt = $GLOBALS['dbcon']->prepare("DELETE FROM wearing WHERE catalogId=:id AND uid=:user");
				$stmt->bindParam(':user', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
				$stmt->bindParam(':id', $resultcheck['catalogId'], PDO::PARAM_INT);
				$stmt->execute();
			}
		}
		if ($type == "hats" or $type == "gear" or $type == "faces" or $type == "heads") {
			$aprString = "http://xdiscuss.net/data/assets/".$type."/models/".$result['datafile'];
		}
		if ($type == "shirts" or $type == "pants" or $type == "tshirts") {
			$aprString = "http://xdiscuss.net/data/assets/".$type."/models/get.php?id=".$result['assetid'];
		}
		if ($type == "torso" or $type == "leftarm" or $type == "leftleg" or $type == "rightarm" or $type == "rightleg") {
			$aprString = "http://xdiscuss.net/data/assets/package/models/".$result['datafile'];
		}
		$stmt = $GLOBALS['dbcon']->prepare("INSERT INTO wearing (`uid`, `catalogid`, `type`, `aprString`) VALUES (:user, :itemid, :type, :aprString);");
		$stmt->bindParam(':user', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':itemid', $catalogId, PDO::PARAM_INT);
		$stmt->bindParam(':type', $type, PDO::PARAM_STR);
		$stmt->bindParam(':aprString', $aprString, PDO::PARAM_STR);
		$stmt->execute();
		
		$query = "INSERT INTO renders (`render_id`, `type`) VALUES (:uid, 'character');";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':uid', $_COOKIE['auth_uid'], PDO::PARAM_INT);
		$stmt->execute();
		
		exit;
	}
?>