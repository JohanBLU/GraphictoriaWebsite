<?php
	if (isset($_POST['csrf']) and isset($_POST['serverName']) and isset($_POST['serverDescription']) and isset($_POST['serverIP']) and isset($_POST['serverPort']) and isset($_POST['privacyType']) and isset($_POST['gameVersion'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf'];
		$serverName = $_POST['serverName'];
		$serverDescription = $_POST['serverDescription'];
		$serverIP = $_POST['serverIP'];
		$serverPort = $_POST['serverPort'];
		$privacyType = $_POST['privacyType'];
		$gameVersion = $_POST['gameVersion'];
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false) {
			echo 'error';
			exit;
		}
		
		$nameCheck = preg_replace("/[^ \w]+/", "", $serverName);
		$nameCheck = preg_replace('!\s+!', ' ', $nameCheck);
		$descriptionCheck = preg_replace("/[^ \w]+/", "", $serverDescription);
		$descriptionCheck = preg_replace('/\s+/', '', $descriptionCheck);
		
		if (strlen($serverName) > 32) {
			echo 'server-name-too-long';
			exit;
		}
		
		if (strlen($serverName) < 4) {
			echo 'server-name-too-short';
			exit;
		}
		
		if (!preg_match("/^[\w*?!\/@',:#$%\^&*\(\) -]+$/", $serverName) == 1) {
			die("server-name-too-short");
		}
		
		if (strlen($serverName) > 128) {
			echo 'server-description-too-long';
			exit;
		}
		
		if (strlen($serverIP) == 0) {
			echo 'server-ip-too-short';
			exit;
		}
		
		if (strlen($serverIP) > 64) {
			echo 'server-ip-too-long';
			exit;
		}
		
		if (strlen($serverPort) == 0) {
			echo 'server-port-too-short';
			exit;
		}
		
		if (strlen($serverPort) > 5) {
			echo 'server-port-too-long';
			exit;
		}
		
		if (is_numeric($serverPort == false) || $serverPort > 64000) die("invalid-port");
		
		if (filter_var($serverIP, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) == false) {
			echo 'invalid-ip';
			exit;
		}
		
		if ($privacyType != 0 && $privacyType != 1) {
			echo 'invalid-privacy';
			exit;
		}
		
		if ($gameVersion != 0 && $gameVersion != 1 && $gameVersion != 2) {
			echo 'invalid-version';
			exit;
		}
		
		$key = md5(microtime().rand());
		$serverkey = md5(microtime().rand());
		$stmt = $dbcon->prepare("INSERT INTO games (`public`, `creator_uid`, `name`, `description`, `ip`, `port`, `key`, `privatekey`, `version`) VALUES (:public, :user, :name, :description, :ip, :port, :key, :serverkey, :version);");
		$stmt->bindParam(':public', $privacyType, PDO::PARAM_INT);
		$stmt->bindParam(':version', $gameVersion, PDO::PARAM_INT);
		$stmt->bindParam(':serverkey', $serverkey, PDO::PARAM_STR);
		$stmt->bindParam(':user', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':name', $serverName, PDO::PARAM_STR);
		$stmt->bindParam(':description', $serverDescription, PDO::PARAM_STR);
		$stmt->bindParam(':ip', $serverIP, PDO::PARAM_STR);
		$stmt->bindParam(':port', $serverPort, PDO::PARAM_INT);
		$stmt->bindParam(':key', $key, PDO::PARAM_STR);
		$stmt->execute();
		
		$stmt = $dbcon->prepare("SELECT * FROM games WHERE `creator_uid`=:uid ORDER BY id DESC LIMIT 1;");
		$stmt->bindParam(':uid', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$id = $result['id'];
		echo $id;
		
	}else{
		echo 'error';
	}
?>