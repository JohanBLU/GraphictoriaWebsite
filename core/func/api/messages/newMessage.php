<?php
	if (isset($_GET['username'])) {
		$username = $_GET['username'];
		if (is_array($username)) {
			exit;
		}
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		if (!$GLOBALS['loggedIn']) {
			echo 'Something went wrong';
			exit;
		}
		$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM users WHERE username = :fId");
		$stmt->bindParam(':fId', $username, PDO::PARAM_STR);
		$stmt->execute();
		if ($stmt->rowCount() == 0) {
			echo 'User not found';
			exit;
		}
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($result['id'] == $GLOBALS['userTable']['id']) {
			echo 'You can not send messages to yourself';
			exit;
		}
		if ($result['banned'] == 1) {
			echo 'You can not send messages to a banned user';
			exit;
		}
		echo '<h3>Sending a new message to '.context::secureString($result['username']).'</h3>';
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/api/messages/views/newMessage.php';
	}else{
		echo 'An error occurred';
	}
?>