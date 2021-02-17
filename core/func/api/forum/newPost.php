<?php
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		if (is_array($id)) {
			exit;
		}
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		if (!$GLOBALS['loggedIn']) {
			echo 'Something went wrong';
			exit;
		}
		$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM forums WHERE id = :fId AND developer = 0");
		$stmt->bindParam(':fId', $id, PDO::PARAM_INT);
		$stmt->execute();
		if ($stmt->rowCount() == 0) die("Forum not found");
		
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		echo '<h3>Posting in '.context::secureString($result['name']).'</h3>';
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/api/forum/views/newPost.php';
	}else{
		echo 'An error occurred';
	}
?>