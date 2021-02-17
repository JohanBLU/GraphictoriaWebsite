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
		if ($stmt->rowCount() == 0) {
			echo 'error';
			exit;
		}
		
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$assetID = $result['assetid'];
		$type = $result['type'];
		$creatorID = $result['creator_uid'];
		$assetName = $result['name'];
		
		if ($result['approved'] == 0 and $result['declined'] == 0) {
			$stmt = $GLOBALS['dbcon']->prepare("UPDATE catalog SET approved = 1 WHERE id=:id");
			$stmt->bindParam(':id', $itemID, PDO::PARAM_INT);
			$stmt->execute();
			
			if ($type == "shirts" or $type == "pants" or $type == "tshirts") {
				$query = "INSERT INTO renders (`render_id`, `type`) VALUES (:id, :dbtype);";
				$stmt = $GLOBALS['dbcon']->prepare($query);
				$stmt->bindParam(':id', $assetID, PDO::PARAM_INT);
				$stmt->bindParam(':dbtype', $type, PDO::PARAM_STR);
				$stmt->execute();
			}
			
			if ($type != "decals") {
				$query = "INSERT INTO ownedItems (`uid`, `catalogid`, `type`) VALUES (:uid, :catid, :type);";
				$stmt = $GLOBALS['dbcon']->prepare($query);
				$stmt->bindParam(':uid', $creatorID, PDO::PARAM_INT);
				$stmt->bindParam(':catid', $itemID, PDO::PARAM_INT);
				$stmt->bindParam(':type', $type, PDO::PARAM_STR);
				$stmt->execute();
			}
			
			if ($type != "decals") {
				$message = 'Your asset named '.$assetName.' has been approved and can be seen in the catalog. You also have received the item in your inventory. Your item can be found at https://xdiscuss.net/catalog/item/'.$itemID;
			}else{
				$message = 'Your asset named '.$assetName.' has been approved and can be seen in the catalog. Your item can be found at https://xdiscuss.net/catalog/item/'.$itemID;
			}
			
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