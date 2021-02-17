<?php
	if (isset($_POST['csrf']) and isset($_POST['itemId'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf'];
		$itemId = $_POST['itemId'];
		if (is_numeric($itemId) == false) die("error");
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false or strlen($itemId) == 0 or is_array($itemId)) {
			echo 'error';
			exit;
		}
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM ownedItems WHERE uid=:id AND catalogid = :catid");
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':catid', $itemId, PDO::PARAM_INT);
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			$owned = true;
		}else{
			$owned = false;
		}
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM catalog WHERE id=:id");
		$stmt->bindParam(':id', $itemId, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$owneruserID = $result['creator_uid'];
		
		if ($owned == false and $result['buyable'] == 1 and $result['type'] !== "decals" and $result['approved'] == 1) {
			if ($result['id'] != $itemId) {
				echo 'error';
				exit;
			}
			$canBuy = false;
			if ($result['currencyType'] == 0) {
				if ($result['price'] < $GLOBALS['userTable']['coins'] or $result['price'] == $GLOBALS['userTable']['coins']) {
					$canBuy = true;
				}
			}
			
			if ($result['currencyType'] == 1) {
				if ($result['price'] < $GLOBALS['userTable']['posties'] or $result['price'] == $GLOBALS['userTable']['posties']) {
					$canBuy = true;
				}
			}
			
			if ($canBuy == true) {
				if ($result['currencyType'] == 0) {
					$newBalance = $GLOBALS['userTable']['coins']-$result['price'];
					$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET coins = :coins WHERE id = :user;");
					$stmt->bindParam(':coins', $newBalance, PDO::PARAM_INT);
					$stmt->bindParam(':user', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
					$stmt->execute();
					
					// If the buyer's account is over 1 week old, award the seller
					$timeSince = round(abs(strtotime(context::getCurrentTime()) - strtotime($GLOBALS['userTable']['joinDate'])) / 60,2);
					if ($timeSince > 10080) {
						$awardPrice = round($result['price']/2);
						
						// Get seller's current coins
						$stmt = $GLOBALS['dbcon']->prepare("SELECT coins FROM users WHERE id=:id");
						$stmt->bindParam(':id', $owneruserID, PDO::PARAM_INT);
						$stmt->execute();
						$resultSeller = $stmt->fetch(PDO::FETCH_ASSOC);
						
						$currentSCoins = $resultSeller['coins'];
						$newSCoins = $resultSeller['coins']+$awardPrice;
						
						// Award the seller right here
						$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET coins = :coins WHERE id = :user;");
						$stmt->bindParam(':coins', $newSCoins, PDO::PARAM_INT);
						$stmt->bindParam(':user', $owneruserID, PDO::PARAM_INT);
						$stmt->execute();
					}
				}else{
					$newBalance = $GLOBALS['userTable']['posties']-$result['price'];
					$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET posties = :coins WHERE id = :user;");
					$stmt->bindParam(':coins', $newBalance, PDO::PARAM_INT);
					$stmt->bindParam(':user', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
					$stmt->execute();
				}
				
				$stmt = $GLOBALS['dbcon']->prepare("INSERT INTO ownedItems (`uid`, `catalogid`, `type`, `rbxasset`) VALUES (:user, :itemid, :type, :rbxasset);");
				$stmt->bindParam(':user', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
				$stmt->bindParam(':itemid', $result['id'], PDO::PARAM_INT);
				$stmt->bindParam(':rbxasset', $result['rbxasset'], PDO::PARAM_INT);
				$stmt->bindParam(':type', $result['type'], PDO::PARAM_STR);
				$stmt->execute();
				
				echo $newBalance;
			}else{
				echo 'error';
			}
		}else{
			echo 'error';
		}
	}else{
		echo 'error';
	}
?>