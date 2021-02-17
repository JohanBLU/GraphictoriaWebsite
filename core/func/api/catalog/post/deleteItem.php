<?php
	if (isset($_POST['csrf']) and isset($_POST['itemId'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf'];
		$itemId = $_POST['itemId'];
		if (is_numeric($itemId) == false) die("error");
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false or strlen($itemId) == 0 or $GLOBALS['userTable']['rank'] == 0 or is_array($itemId)) {
			echo 'error';
			exit;
		}
		
		// Get item info again.
		$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM catalog WHERE id=:id");
		$stmt->bindParam(':id', $itemId, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$fileHash = $result['fileHash'];
		if ($result['type'] != "tshirts" and $result['type'] != "shirts" and $result['type'] != "pants" and $result['type'] != "decals") {
			echo 'error';
			exit;
		}
		
		// Make deleted true
		$stmt = $GLOBALS['dbcon']->prepare("UPDATE catalog SET deleted = 1 WHERE fileHash = :id;");
		$stmt->bindParam(':id', $fileHash, PDO::PARAM_STR);
		$stmt->execute();
		
		// Make item unbuyable
		$stmt = $GLOBALS['dbcon']->prepare("UPDATE catalog SET buyable = 0 WHERE fileHash = :id;");
		$stmt->bindParam(':id', $fileHash, PDO::PARAM_STR);
		$stmt->execute();
		
		// Set deleted true in owned items with the same file hash
		$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM catalog WHERE fileHash = :id");
		$stmt->bindParam(':id', $fileHash, PDO::PARAM_INT);
		$stmt->execute();
		foreach($stmt as $result) {
			$iID = $result['id'];
			$stmt = $GLOBALS['dbcon']->prepare("UPDATE ownedItems SET deleted = 1 WHERE catalogid = :id;");
			$stmt->bindParam(':id', $iID, PDO::PARAM_INT);
			$stmt->execute();
		}
		
		// Delete the actual file
		if ($result['type'] == "tshirts" || $result['type'] == "shirts" || $result['type'] == "pants" || $result['type'] == "decals") {
			@unlink($_SERVER['DOCUMENT_ROOT'].'/data/assets/uploads/'.$result['fileHash']);
		}
		
		if ($result['type'] != "decals") {
			// Remove from wearing
			$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM catalog WHERE fileHash = :id");
			$stmt->bindParam(':id', $fileHash, PDO::PARAM_INT);
			$stmt->execute();
			foreach($stmt as $result) {
				$iID2 = $result['id'];
				$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM wearing WHERE catalogId = :id");
				$stmt->bindParam(':id', $iID2, PDO::PARAM_INT);
				$stmt->execute();
				foreach($stmt as $result) {
					// Delete and put a request up in the imageServer
					$query = "DELETE FROM `wearing` WHERE `id`=:id";
					$stmt = $GLOBALS['dbcon']->prepare($query);
					$id = $result['id'];
					$stmt->bindParam(':id', $id, PDO::PARAM_INT);
					$stmt->execute();
					
					$uid = $result['uid'];
					// Add request to imageServer
					$query = "INSERT INTO renders (`render_id`, `type`) VALUES (:id, 'character');";
					$stmt = $GLOBALS['dbcon']->prepare($query);
					$stmt->bindParam(':id', $uid, PDO::PARAM_INT);
					$stmt->execute();
				}
			}
		}
		
		echo 'success';
	}else{
		echo 'error';
	}
?>