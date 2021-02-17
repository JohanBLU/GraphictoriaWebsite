<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	if ($GLOBALS['loggedIn'] == false) {
		exit;
	}
	function checkColor($color) {
		$colors = array(1, 208, 194, 199, 26, 21, 24, 226, 23, 107, 102, 11, 45, 135, 105, 141, 37, 119, 29, 119, 29, 151, 38, 192, 104, 9, 101, 5, 153, 217, 18, 125);
		if (!in_array($color, $colors)) {
			return false;
		}else{
			return true;
		}
	}
	
	function setColor($color, $type, $dbcon) {
		$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM characterColors WHERE uid = :uid AND type = :type");
		$stmt->bindParam(':uid', $_COOKIE['auth_uid'], PDO::PARAM_INT);
		$stmt->bindParam(':type', $type, PDO::PARAM_STR);
		$stmt->execute();
		if ($stmt->rowCount() == 0) {
			$query = "INSERT INTO characterColors (`uid`, `color`, `type`) VALUES (:uid, :color, :type);";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->bindParam(':uid', $_COOKIE['auth_uid'], PDO::PARAM_INT);
			$stmt->bindParam(':color', $color, PDO::PARAM_INT);
			$stmt->bindParam(':type', $type, PDO::PARAM_STR);
			$stmt->execute();
		}else{
			$query = "UPDATE `characterColors` SET `color`=:color WHERE `uid`=:uid AND `type`=:type ";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->bindParam(':uid', $_COOKIE['auth_uid'], PDO::PARAM_INT);
			$stmt->bindParam(':color', $color, PDO::PARAM_INT);
			$stmt->bindParam(':type', $type, PDO::PARAM_STR);
			$stmt->execute();
		}
		
		$query = "INSERT INTO renders (`render_id`, `type`) VALUES (:uid, 'character');";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':uid', $_COOKIE['auth_uid'], PDO::PARAM_INT);
		$stmt->execute();
		
		$query = "INSERT INTO renders (`render_id`, `type`, `version`) VALUES (:uid, 'character', 2);";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':uid', $_COOKIE['auth_uid'], PDO::PARAM_INT);
		$stmt->execute();
	}
	
	if (isset($_GET['updateHead'])) {
		$color = $_GET['updateHead'];
		if (checkColor($color) == true) {
			setColor($color, "head", $dbcon);
			echo '<div class="alert alert-dismissible alert-success">Updated Head Color!</div>';
		}else{
			echo '<div class="alert alert-dismissible alert-danger">This color can not be applied.</div>';
		}
	}
	
	if (isset($_GET['updateTorso'])) {
		$color = $_GET['updateTorso'];
		if (checkColor($color) == true) {
			setColor($color, "torso", $dbcon);
			echo '<div class="alert alert-dismissible alert-success">Updated Torso Color!</div>';
		}else{
			echo '<div class="alert alert-dismissible alert-danger">This color can not be applied.</div>';
		}
	}
	
	if (isset($_GET['updateRightArm'])) {
		$color = $_GET['updateRightArm'];
		if (checkColor($color) == true) {
			setColor($color, "rightarm", $dbcon);
			echo '<div class="alert alert-dismissible alert-success">Updated Right Arm Color!</div>';
		}else{
			echo '<div class="alert alert-dismissible alert-danger">This color can not be applied.</div>';
		}
	}
	
	if (isset($_GET['updateRightLeg'])) {
		$color = $_GET['updateRightLeg'];
		if (checkColor($color) == true) {
			setColor($color, "rightleg", $dbcon);
			echo '<div class="alert alert-dismissible alert-success">Updated Right Leg Color!</div>';
		}else{
			echo '<div class="alert alert-dismissible alert-danger">This color can not be applied.</div>';
		}
	}
	
	if (isset($_GET['updateLeftArm'])) {
		$color = $_GET['updateLeftArm'];
		if (checkColor($color) == true) {
			setColor($color, "leftarm", $dbcon);
			echo '<div class="alert alert-dismissible alert-success">Updated Left Arm Color!</div>';
		}else{
			echo '<div class="alert alert-dismissible alert-danger">This color can not be applied.</div>';
		}
	}
	
	if (isset($_GET['updateLeftLeg'])) {
		$color = $_GET['updateLeftLeg'];
		if (checkColor($color) == true) {
			echo '<div class="alert alert-dismissible alert-success">Updated Left Leg Color!</div>';
			setColor($color, "leftleg", $dbcon);
		}else{
			echo '<div class="alert alert-dismissible alert-danger">This color can not be applied.</div>';
		}
	}
?>