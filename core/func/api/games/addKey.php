<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	if ($GLOBALS['loggedIn']) {
		if (isset($_POST['key'])) {
			$key = $_POST['key'];
			$err = false;
			if (strlen($key) == 0) {
				echo '<div class="alert" style="background-color:red;margin-bottom:0px;border-radius:0px;padding:5px;;color:white">Please enter a key.</div>';
				$err = true;
			}
			
			if ($err == false) {
				$stmt = $dbcon->prepare("SELECT * FROM games WHERE `key` = :key");
				$stmt->bindParam(':key', $key, PDO::PARAM_STR);
				$stmt->execute();
				
				if ($stmt->rowCount() == 0) {
					echo '<div class="alert" style="background-color:red;margin-bottom:0px;border-radius:0px;padding:5px;;color:white">Invalid key.</div>';
				}else{
					// Check if already submitted.
					$stmt = $dbcon->prepare("SELECT * FROM gameKeys WHERE `key` = :key AND userid = :uid");
					$stmt->bindParam(':key', $key, PDO::PARAM_STR);
					$stmt->bindParam(':uid', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
					$stmt->execute();
					
					if ($stmt->rowCount() == 0) {
						$stmt = $dbcon->prepare("INSERT INTO `gameKeys` (`userid`, `key`) VALUES (:uid, :key);");
						$stmt->bindParam(':key', $key, PDO::PARAM_STR);
						$stmt->bindParam(':uid', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
						$stmt->execute();
						
						echo '<div class="alert" style="background-color:green;margin-bottom:0px;border-radius:0px;padding:5px;color:white">Key added!</div>';
					}else{
						echo '<div class="alert" style="background-color:red;margin-bottom:0px;border-radius:0px;padding:5px;;color:white">You have already submitted this key.</div>';
					}
				}
			}
		}else{
			echo '<div class="alert" style="background-color:red;margin-bottom:0px;border-radius:0px;padding:5px;;color:white">Something happened.</div>';
		}
	}else{
		echo '<div class="alert" style="background-color:red;margin-bottom:0px;border-radius:0px;padding:5px;;color:white">You need to be signed in to add a server to your list.</div>';
	}
?>