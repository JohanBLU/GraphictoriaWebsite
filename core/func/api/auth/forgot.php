<?php
	if (isset($_POST['username']) && isset($_POST['csrf'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$IP = auth::getIP();
		$username = $_POST['username'];
		$csrf_token = $_POST['csrf'];
		
		if ($csrf_token != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == true) {
			echo 'error';
			exit;
		}
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT id, username, email FROM users WHERE username = :username OR email = :email;");
		$stmt->bindParam(':username', $username, PDO::PARAM_STR); 
		$stmt->bindParam(':email', $username, PDO::PARAM_STR); 
		$stmt->execute();
		if ($stmt->rowCount() == 0) {
			echo 'no-user';
			exit;
		}
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$userID = $result['id'];
		$username = $result['username'];
		$email = $result['email'];
		
		$query = "SELECT * FROM pwdreset WHERE ip = :ip LIMIT 1;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':ip', $IP, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($stmt->rowCount() > 0) {
			$currentTime = date('Y-m-d H:i:s');
			$to_time = strtotime($currentTime);
			$from_time = strtotime($result['date']);
			$timeSince =  round(abs($to_time - $from_time) / 60,2);
			if ($timeSince < 5) {
				echo 'rate-limit';
				exit;
			}
		}
		
		// If IP is changed.
		$query = "SELECT * FROM passwordresets WHERE userId = :uid LIMIT 1;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':uid', $userID, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($stmt->rowCount() > 0) {
			$currentTime = date('Y-m-d H:i:s');
			$to_time = strtotime($currentTime);
			$from_time = strtotime($result['date']);
			$timeSince =  round(abs($to_time - $from_time) / 60,2);
			if ($timeSince < 5) {
				echo 'rate-limit';
				exit;
			}else{
				// Delete every other request
				$stmt = $dbcon->prepare("DELETE FROM passwordresets WHERE userId = :uid");
				$stmt->bindParam(':uid', $userID, PDO::PARAM_INT);
				$stmt->execute();
			}
		}
		
		$stmt = $dbcon->prepare("DELETE FROM pwdreset WHERE ip = :ip");
		$stmt->bindParam(':ip', $IP, PDO::PARAM_STR);
		$stmt->execute();
		
		$stmt = $dbcon->prepare("INSERT INTO `pwdreset` (`ip`) VALUES (:ip);");
		$stmt->bindParam(':ip', $IP, PDO::PARAM_STR);
		$stmt->execute();
		
		$key = context::random_str(256);
		$stmt = $dbcon->prepare("INSERT INTO `passwordresets` (`userId`, `key`) VALUES (:uid, :key);");
		$stmt->bindParam(':uid', $userID, PDO::PARAM_INT);
		$stmt->bindParam(':key', $key, PDO::PARAM_STR);
		$stmt->execute();
		
		mailHandler::sendMail('Hello '.$username.'! You can reset your password at https://xdiscuss.net/account/resetpassword/'.$userID.'/'.$key.' if you did not request this, you can ignore this. <br><br><a href="http://xdiscuss.net">Graphictoria</a><br>Please know that this message was generated automatically, do not reply to this. If you need help, send a message to <a href="mailto:support@xdiscuss.net">support@xdiscuss.net</a>.', "Hello ".$username."! You can reset your password at https://xdiscuss.net/account/resetpassword/".$userID."/".$key." if you did not request this, you can ignore this.", $email, "Graphictoria Password Reset", $username);
		
		echo 'success';
	}else{
		echo 'error';
		exit;
	}
?>