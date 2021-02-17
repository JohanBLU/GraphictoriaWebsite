<?php
	if (isset($_POST['csrf_token']) and isset($_POST['serverName']) and isset($_POST['serverDescription']) and isset($_POST['versionType']) and isset($_POST['privacyType'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf_token'];
		$serverName = $_POST['serverName'];
		$serverDescription = $_POST['serverDescription'];
		$serverName = str_replace("-", "", $serverName);
		$serverDescription = str_replace("-", "", $serverDescription);
		$version = $_POST['versionType'];
		$privacyType = $_POST['privacyType'];
		if ($version != 0 && $version != 2 && $version != 1) die("error");
		
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false) {
			echo 'error';
			exit;
		}
		
		$timeSince = round(abs(strtotime(context::getCurrentTime()) - strtotime($GLOBALS['userTable']['lastUpload'])) / 60,2);
		if ($timeSince < 5 && $GLOBALS['userTable']['rank'] != 1) {
			echo 'rate-limit';
			exit;
		}
		
		$genPlace = 0;
		if (isset($_POST['genPlace'])) $genPlace = $_POST['genPlace'];
		if (is_array($genPlace)) die("error");
		
		// Do never use generic places if a place file is present.
		if (isset($_FILES['placeFile'])) {
			$genPlace = 0;
		}
		
		// Check if genplace exists
		if ($genPlace != 0 && $genPlace != 1) die("error");
		
		$nameCheck = preg_replace("/[^ \w]+/", "", $serverName);
		$nameCheck = preg_replace('!\s+!', ' ', $nameCheck);
		$descriptionCheck = preg_replace("/[^ \w]+/", "", $serverDescription);
		$descriptionCheck = preg_replace('/\s+/', '', $descriptionCheck);
		
		if (strlen($serverName) > 32) {
			echo 'server-name-too-long';
			exit;
		}
		
		if (!preg_match("/^[\w*?!\/@',:#$%\^&*\(\) -]+$/", $serverName) == 1) {
			die("server-name-too-short");
		}
		
		if (strlen($serverName) < 4) {
			echo 'server-name-too-short';
			exit;
		}
		
		if ($privacyType != 0 && $privacyType != 1) {
			echo 'invalid-privacy';
			exit;
		}
		
		if (strlen($serverDescription) > 128) {
			echo 'server-description-too-long';
			exit;
		}
		
		if (isset($_FILES['placeFile'])) {
			// Upload the place file properly.
			$fileContent = @file_get_contents($_FILES['placeFile']['tmp_name']);
			if (strpos($fileContent, 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"') == false) {
				die("invalid-placefile");
			}
			
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mime = finfo_file($finfo, $_FILES['placeFile']['tmp_name']);
			if ($mime != "text/plain") {
				die("invalid-placefile");
			}
			
			$imageFileType = pathinfo($_FILES['placeFile']["name"], PATHINFO_EXTENSION);
			if ($imageFileType != "rbxl" && $imageFileType != "RBXL") die("invalid-placefile");
			
			// Still alive? Proceed to upload the place file.
			$uploadDirectory = $_SERVER['DOCUMENT_ROOT'].'/data/assets/uploads/';
			$fileHash = hash_file('sha512', $_FILES["placeFile"]["tmp_name"]);
			if (!file_exists($uploadDirectory.$fileHash)) {
				if (!move_uploaded_file($_FILES["placeFile"]["tmp_name"], $uploadDirectory.$fileHash)) {
					die("file-move-error");
				}
			}
			
			$webDirectory = "http://xdiscuss.net/data/assets/uploads/".$fileHash;
		}else{
			if ($genPlace == 0) die("error");
			if ($genPlace == 1) $webDirectory = "http://api.xdiscuss.net/places/baseplate.rbxl";
		}
		
		// If we're still here, we can continue to request the server.
		$stmt = $dbcon->prepare("INSERT INTO serverRequests (`placeLocation`, `serverName`, `serverDescription`, `serverVersion`, `userID`, `serverPrivacy`) VALUES (:placeLocation, :serverName, :serverDescription, :version, :userID, :privacy);");
		$stmt->bindParam(':placeLocation', $webDirectory, PDO::PARAM_STR);
		$stmt->bindParam(':serverName', $serverName, PDO::PARAM_STR);
		$stmt->bindParam(':userID', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':version', $version, PDO::PARAM_INT);
		$stmt->bindParam(':privacy', $privacyType, PDO::PARAM_INT);
		$stmt->bindParam(':serverDescription', $serverDescription, PDO::PARAM_STR);
		$stmt->execute();
		
		$stmt = $dbcon->prepare("UPDATE users SET lastUpload = NOW() WHERE id = :user;");
		$stmt->bindParam(':user', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		
		echo 'success';
	}else{
		echo 'error';
	}
?>