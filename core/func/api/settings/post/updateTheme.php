<?php
	if (isset($_POST['csrf']) and isset($_POST['theme'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf'];
		$theme = $_POST['theme'];
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false) die("error");
		if ($theme != 0 && $theme != 1) die("error");
		
		$query = "UPDATE `users` SET `themeChoice`=:theme WHERE `id`=:id;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':theme', $theme, PDO::PARAM_INT);
		$stmt->execute();
		
		echo 'success';
	}else{
		echo 'error';
	}
?>