<?php
	if (isset($_FILES['file']) and isset($_POST['itemName']) and isset($_POST['itemDescription']) and isset($_POST['itemType']) and isset($_POST['itemPrice']) and isset($_POST['csrf_token'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf_token'];
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false) die("error");
		
		// Variables
		$uploadOk = false;
		$itemType = $_POST['itemType'];
		$itemPrice = $_POST['itemPrice'];
		$itemName = $_POST['itemName'];
		$description = $_POST['itemDescription'];
		$itemName = preg_replace("/[^ \w]+/", "", $itemName);
		$itemName = preg_replace('!\s+!', ' ', $itemName);
		$description = preg_replace('!\s+!', ' ', $description);
		
		// itemType check
		if ($itemType == 0) {
			$typeString = "shirts";
		}elseif($itemType == 1) {
			$typeString = "pants";
		}elseif($itemType == 2) {
			$typeString = "tshirts";
		}elseif($itemType == 3) {
			$typeString = "decals";
		}else{
			echo 'error';
			exit;
		}
		
		// Error handling
		if (strlen($itemName) > 32) {
			echo 'name-too-long';
			exit;
		}
		
		if (!preg_match("/^[\w*?!\/@#$%\^&*\(\) -]+$/", $itemName) == 1) {
			die("name-too-short");
		}
		
		if (strlen($itemName) < 5) {
			echo 'name-too-short';
			exit;
		}
		
		if (strlen($description) > 128) {
			echo 'description-too-long';
			exit;
		}
		
		if (is_numeric($itemPrice) == false) die("price-too-low");
		
		if ($itemPrice < 1 && $typeString != "decals") {
			echo 'price-too-low';
			exit;
		}
		
		if ($typeString == "decals") {
			$itemPrice = 0;
		}
		
		// Check last upload date, if less than a minute return 'rate-limit'
		$timeSince = round(abs(strtotime(context::getCurrentTime()) - strtotime($GLOBALS['userTable']['lastUpload'])) / 60,2);
		if ($timeSince < 1) {
			echo 'rate-limit';
			exit;
		}
		
		// Get the latest assetID, if nothing, default to 1
		$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM catalog WHERE type=:dbtype ORDER BY id DESC LIMIT 1;");
		$stmt->bindParam(':dbtype', $typeString, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($stmt->rowCount() == 0) {
			$assetId = 1;
		}else{
			$assetId = $result['assetid']+1;
		}
		
		$uploadDirectory = $_SERVER['DOCUMENT_ROOT'].'/data/assets/uploads/';
		
		// Check the file
		$check = @getimagesize($_FILES["file"]["tmp_name"]);
		list($width, $height) = @getimagesize($_FILES["file"]["tmp_name"]);
		if ($width != 585 && $height != 559) {
			if ($typeString == "shirts" or $typeString == "pants") {
				echo 'incorrect-size';
				exit;
			}
		}
		
		if (!$check) {
			echo 'no-image';
			exit;
		}
		
		if ($_FILES["file"]["size"] > 1000000) {
			echo 'file-too-large';
			exit;
		}
		
		$imageFileType = pathinfo($_FILES['file']["name"], PATHINFO_EXTENSION);
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($finfo, $_FILES['file']['tmp_name']);
		if ($imageFileType != "jpg" && $imageFileType != "JPG" && $imageFileType != "png" && $imageFileType != "PNG" && $imageFileType != "jpeg" && $imageFileType != "JPEG" && $mime != "image/png" && $mime != "image/jpeg") {
			echo 'incorrect-extension';
			exit;
		}
		
		if (exif_imagetype($_FILES['file']['tmp_name']) != IMAGETYPE_PNG && exif_imagetype($_FILES['file']['tmp_name']) != IMAGETYPE_JPEG) {
			echo 'incorrect-extension';
			exit;
		}
			
		
		// Check user balance, should be easy
		if ($GLOBALS['userTable']['coins'] < 5) {
			echo 'not-enough-coins';
			exit;
		}
		
		// Check if the file hash is not in badHashes
		$fileHash = hash_file('sha512', $_FILES["file"]["tmp_name"]);
		$stmt = $GLOBALS['dbcon']->prepare("SELECT id FROM badHashes WHERE hash=:fileHash");
		$stmt->bindParam(':fileHash', $fileHash, PDO::PARAM_STR);
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			echo 'bad-hash';
			exit;
		}
		
		// Move the file to the right directory. Upload complete!
		// Only move if it doesn't exist already.
		if (!file_exists($uploadDirectory.$fileHash)) {
			if (!move_uploaded_file($_FILES["file"]["tmp_name"], $uploadDirectory.$fileHash)) {
				echo 'file-upload-error';
				exit;
			}
		}
		
		$newCoins = $GLOBALS['userTable']['coins']-5;
		$stmt = $dbcon->prepare("UPDATE users SET coins = :coins WHERE id = :user;");
		$stmt->bindParam(':coins', $newCoins, PDO::PARAM_INT);
		$stmt->bindParam(':user', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
			
		// Set last upload to now, for security purposes.
		$stmt = $dbcon->prepare("UPDATE users SET lastUpload = NOW() WHERE id = :user;");
		$stmt->bindParam(':user', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
			
		// Add to catalog. But keep un-approved until approved, of course, unless the hash is already approved.
		// Check if any asset with the same hash is approved or not. Why approve the same file again?
		$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM catalog WHERE fileHash=:fileHash ORDER BY id DESC LIMIT 1;");
		$stmt->bindParam(':fileHash', $fileHash, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$approved = 0;
		if ($result['approved'] == 1 && $result['fileHash'] == $fileHash && $result['deleted'] == 0) {
			$approved = 1;
		}
		
		$stmt = $dbcon->prepare("INSERT INTO catalog (`price`, `creator_uid`, `assetid`, `name`, `description`, `type`, `approved`, `fileHash`) VALUES (:price, :user, :assetid, :name, :description, :type, :approved, :fileHash);");
		$stmt->bindParam(':user', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':price', $itemPrice, PDO::PARAM_INT);
		$stmt->bindParam(':type', $typeString, PDO::PARAM_STR);
		$stmt->bindParam(':assetid', $assetId, PDO::PARAM_INT);
		$stmt->bindParam(':name', $itemName, PDO::PARAM_STR);
		$stmt->bindParam(':description', $description, PDO::PARAM_STR);
		$stmt->bindParam(':approved', $approved, PDO::PARAM_INT);
		$stmt->bindParam(':fileHash', $fileHash, PDO::PARAM_STR);
		$stmt->execute();
			
		if ($approved == 0) {
			// Send the uploader a message so they can keep track of the progress of approval.
			if ($typeString != "decals") {
				$message = 'Your asset named <b>'.$itemName.'</b> is pending approval. You will receive another message after approval. Once approved, you will receive the item.';
			}else{
				$message = 'Your asset named <b>'.$itemName.'</b> is pending approval. You will receive another message after approval. Once approved, the decal will be visible in the catalog.';
			}
		}else{
			$message = 'Your asset named <b>'.$itemName.'</b> has already been approved in the past. So, you can already make use of it and it is visible on the catalog.';
		}
		$title = 'Asset Approval for '.$itemName;
		$query = "INSERT INTO messages (`recv_uid`, `sender_uid`, `title`, `content`) VALUES (:userId2, 10370, :title, :msg);";
		$stmt = $dbcon->prepare($query);
		$stmt->bindParam(':userId2', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':msg', $message, PDO::PARAM_STR);
		$stmt->bindParam(':title', $title, PDO::PARAM_STR);
		$stmt->execute();
		
		if ($approved == 1) {
			if ($typeString == "shirts" or $typeString == "pants" or $typeString == "tshirts") {
				$query = "INSERT INTO renders (`render_id`, `type`) VALUES (:id, :dbtype);";
				$stmt = $GLOBALS['dbcon']->prepare($query);
				$stmt->bindParam(':id', $assetId, PDO::PARAM_INT);
				$stmt->bindParam(':dbtype', $typeString, PDO::PARAM_STR);
				$stmt->execute();
			}
			
			// Get latest asset by this user
			$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM catalog WHERE creator_uid = :uid ORDER BY id DESC LIMIT 1;");
			$stmt->bindParam(':uid', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$catId = $result['id'];
			
			if ($typeString != "decals") {
				$query = "INSERT INTO ownedItems (`uid`, `catalogid`, `type`) VALUES (:uid, :catid, :type);";
				$stmt = $GLOBALS['dbcon']->prepare($query);
				$stmt->bindParam(':uid', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
				$stmt->bindParam(':catid', $catId, PDO::PARAM_INT);
				$stmt->bindParam(':type', $typeString, PDO::PARAM_STR);
				$stmt->execute();
			}
		}
		
		// We're done! Yahoo!
		echo $newCoins;
	}else{
		echo 'no-file';
	}
?>