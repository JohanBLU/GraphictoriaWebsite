<?php
	$GLOBALS['loggedIn'] = false;
	$GLOBALS['csrf_token'] = null;
	if (isset($_COOKIE['auth_uid']) && isset($_COOKIE['a_id'])) {
		$stmt = $GLOBALS['dbcon']->prepare('SELECT lastUsed, id, csrfToken, factorFinish, location, userId, useragent FROM sessions WHERE userId = :userId AND sessionId = :sessionId LIMIT 1;');
		$stmt->bindParam(':userId', $_COOKIE['auth_uid'], PDO::PARAM_INT);
		$stmt->bindParam(':sessionId', $_COOKIE['a_id'], PDO::PARAM_STR);
		$stmt->execute();
		$resultSession = $stmt->fetch(PDO::FETCH_ASSOC);
		$removeSession = false;
		$sesexpired = false;
		if ($stmt->rowCount() > 0) {
			$from_time = strtotime($resultSession['lastUsed']);
			$sessionId = $resultSession['id'];
			$to_time = strtotime(context::getCurrentTime());
			$timeSince =  round(abs($to_time - $from_time) / 60,2);
			if ($timeSince > 1440 || $removeSession == true) {
				$sesexpired = true;
				$stmt = $GLOBALS['dbcon']->prepare('DELETE FROM sessions WHERE id=:id;');
				$stmt->bindParam(':id', $sessionId, PDO::PARAM_INT);
				$stmt->execute();
			}
		}
		
		
		if ($stmt->rowCount() > 0 && $sesexpired == false) {
			$GLOBALS['loggedIn'] = true;
			$query = "SELECT * FROM users WHERE id = :id LIMIT 1;";
			$stmt = $dbcon->prepare($query);
			$stmt->bindParam(':id', $_COOKIE['auth_uid'], PDO::PARAM_STR); 
			$stmt->execute(); 
			$GLOBALS['userTable'] = $stmt->fetch(PDO::FETCH_ASSOC);
			$GLOBALS['sessionTable'] = $resultSession;
			$GLOBALS['csrf_token'] = $resultSession['csrfToken'];
			
			$IP = auth::getIP();
			if ($GLOBALS['userTable']['lastIP'] != $IP) {
				$stmt = $dbcon->prepare("UPDATE users SET lastIP = :ip WHERE username = :user;");
				$stmt->bindParam(':user', $GLOBALS['userTable']['username'], PDO::PARAM_STR);
				$stmt->bindParam(':ip', $IP, PDO::PARAM_STR);
				$stmt->execute();
			}
			
			if ($GLOBALS['userTable']['banned'] == 1 && strpos($_SERVER['SCRIPT_NAME'], "banned.php") == false) {
				if (!isset($GLOBALS['bypassRedirect'])) {
					header("Location: /account/suspended");
					exit;
				}
			}
			
			if (security::getUserEmailVerified() == false && $GLOBALS['userTable']['banned'] == 0) {
				$timeSince = round(abs(strtotime(context::getCurrentTime()) - strtotime($GLOBALS['userTable']['emailcodeTime'])) / 60,2);
				if ($timeSince > 15) {
					security::sendEmailVerificationMessage();
				}
				if (strpos($_SERVER['SCRIPT_NAME'], "verifyEmail.php") == false) {
					if (!isset($GLOBALS['bypassRedirect'])) {
						header("Location: /account/verification/email");
						exit;
					}
				}
			}
			
			if ($GLOBALS['sessionTable']['factorFinish'] == 0 && $GLOBALS['userTable']['banned'] == 0 && $GLOBALS['userTable']['2faEnabled'] == 1 && security::getUserEmailVerified() == true && strpos($_SERVER['SCRIPT_NAME'], "twostepauth.php") == false) {
				if (!isset($GLOBALS['bypassRedirect'])) {
					header("Location: /account/verification/twostepauth");
					exit;
				}
			}
			
			$from_time = strtotime($GLOBALS['userTable']['lastAward']);
			$to_time = strtotime(context::getCurrentTime());
			$timeSince =  round(abs($to_time - $from_time) / 60,2);
			if ($timeSince > 1440) {
				$newCoins = $GLOBALS['userTable']['coins']+15;
				$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET coins = :coins WHERE id = :user;");
				$stmt->bindParam(':coins', $newCoins, PDO::PARAM_INT);
				$stmt->bindParam(':user', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
				$stmt->execute();
				$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET lastAward = NOW() WHERE id = :user;");
				$stmt->bindParam(':user', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
				$stmt->execute();
			}
			
			$from_time = strtotime($GLOBALS['sessionTable']['lastUsed']);
			$to_time = strtotime(context::getCurrentTime());
			$timeSince =  round(abs($to_time - $from_time) / 60,2);
			if ($timeSince > 3) {
				$stmt = $GLOBALS['dbcon']->prepare("UPDATE sessions SET lastUsed = NOW() WHERE id = :sid;");
				$stmt->bindParam(':sid', $GLOBALS['sessionTable']['id'], PDO::PARAM_STR);
				$stmt->execute();
			}
			
			$from_time = strtotime($GLOBALS['userTable']['lastSeen']);
			$to_time = strtotime(context::getCurrentTime());
			$timeSince =  round(abs($to_time - $from_time) / 60,2);
			if ($timeSince > 3) {
				$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET lastSeen = NOW() WHERE id = :id;");
				$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
				$stmt->execute();
			}
			
			if ($GLOBALS['userTable']['inGame'] == 1 and !isset($GLOBALS['ignoreGame'])) {
				$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET inGame = 0 WHERE id = :id;");
				$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
				$stmt->execute();
			}
		}
		
		if ($GLOBALS['loggedIn'] == false) {
			$GLOBALS['csrf_token'] = sha1(auth::getIP());
			if (isset($_COOKIE['auth_uid']) || isset($_COOKIE['a_id'])) {
				setcookie('auth_uid', "", time() - 3600);
				setcookie('a_id', "", time() - 3600);
			}
		}
	}
?>
