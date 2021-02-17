<?php
	if (isset($_POST['csrf']) and isset($_POST['itemID'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf'];
		$itemID = $_POST['itemID'];
		if (is_numeric($itemID) == false) die("error");
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false or strlen($itemID) == 0 or $GLOBALS['userTable']['rank'] == 0) {
			echo 'error';
			exit;
		}
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM catalog WHERE id=:id;");
		$stmt->bindParam(':id', $itemID, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$assetID = $result['assetid'];
		$type = $result['type'];
		$creatorID = $result['creator_uid'];
		$assetName = $result['name'];
		$fileHash = $result['fileHash'];
		
		if ($result['approved'] == 0 and $result['declined'] == 0) {
			$stmt = $GLOBALS['dbcon']->prepare("UPDATE catalog SET declined = 1 WHERE id=:id");
			$stmt->bindParam(':id', $itemID, PDO::PARAM_INT);
			$stmt->execute();
			
			if ($result['type'] == "tshirts" || $result['type'] == "shirts" || $result['type'] == "pants" || $result['type'] == "decals") {
				@unlink($_SERVER['DOCUMENT_ROOT'].'/data/assets/uploads/'.$result['fileHash']);
			}
			
			$query = "INSERT INTO badHashes (`hash`) VALUES (:hash);";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->bindParam(':hash', $fileHash, PDO::PARAM_STR);
			$stmt->execute();
			
			$message = 'Your asset named <b>'.$assetName.'</b> has been denied because it violated our rules. You have not been refunded.';
			$title = 'Asset Approval result for '.$assetName;
			$query = "INSERT INTO messages (`recv_uid`, `sender_uid`, `title`, `content`) VALUES (:userId2, 10370, :title, :msg);";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->bindParam(':userId2', $creatorID, PDO::PARAM_INT);
			$stmt->bindParam(':msg', $message, PDO::PARAM_STR);
			$stmt->bindParam(':title', $title, PDO::PARAM_STR);
			$stmt->execute();
			
			echo 'success';
		}else{
			echo 'error';
		}
	}else{
		echo 'error';
	}
?>