<?php
	if (isset($_POST['csrf'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf'];
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false) {
			echo 'error';
			exit;
		}
		
		if ($GLOBALS['userTable']['2faInit'] == 0 and $GLOBALS['userTable']['2faEnabled'] == 0) {
			echo 'error';
			exit;
		}else{
			if ($GLOBALS['userTable']['rank'] == 0) {
				$query = "UPDATE `users` SET `2faEnabled`=0 WHERE `id`=:id;";
				$stmt = $GLOBALS['dbcon']->prepare($query);
				$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
				$stmt->execute();
				
				$query = "UPDATE `users` SET `2faInit`=0 WHERE `id`=:id;";
				$stmt = $GLOBALS['dbcon']->prepare($query);
				$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
				$stmt->execute();
				
				echo 'success';
			}else{
				echo 'staff-block';
				exit;
			}
		}
	}else{
		echo 'error';
	}
?>