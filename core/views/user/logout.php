<?php
	$GLOBALS['bypassRedirect'] = true;
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	if (!$GLOBALS['loggedIn']) {
		header("Location: /");
		exit;
	}
	// Destroy session
	$stmt = $dbcon->prepare('SELECT * FROM sessions WHERE userId = :userId AND sessionId = :sessionId;');
	$stmt->bindParam(':userId', $_COOKIE['auth_uid'], PDO::PARAM_INT);
	$stmt->bindParam(':sessionId', $_COOKIE['a_id'], PDO::PARAM_STR);
	$stmt->execute();
	$resultSession = $stmt->fetch(PDO::FETCH_ASSOC);
	$sessionId = $resultSession['id'];
	
	$stmt = $dbcon->prepare('DELETE FROM sessions WHERE id=:id;');
	$stmt->bindParam(':id', $sessionId, PDO::PARAM_INT);
	$stmt->execute();
	
	unset($_COOKIE['auth_uid']);
	unset($_COOKIE['a_id']);
	setcookie('auth_uid', "", time() - 3600);
	setcookie('a_id', "", time() - 3600);
	
	if (isset($_SERVER['HTTP_REFERER'])) {
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit;
	}else{
		header("Location: /");
		exit;
	}
?>