<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	if ($GLOBALS['loggedIn'] == false) {
		exit;
	}
	
	if (isset($_GET['type'])) {
		$type = $_GET['type'];
	}else{
		exit;
	}
	
	if ($type == "hats") {
		$title = "Hats";
	}
	
	if ($type == "shirts") {
		$title = "Shirts";
	}
	
	if ($type == "pants") {
		$title = "Pants";
	}
	
	if ($type == "gear") {
		$title = "Gear";
	}
	if ($type == "tshirts"){
		$title = "T-Shirts";
	}
	if ($type == "faces") {
		$title = "Faces";
	}
	if ($type == "torso") {
		$title = "Torso";
	}
	if ($type == "leftleg") {
		$title = "Left Leg";
	}
	if ($type == "leftarm") {
		$title = "Left Arm";
	}
	if ($type == "rightleg") {
		$title = "Right Leg";
	}
	if ($type == "rightarm") {
		$title = "Right Arm";
	}
	if ($type == "heads") {
		$title = "Heads";
	}
	if (isset($_GET['page'])) {
		$page = $_GET['page'];
		if (is_numeric($page) == false) exit;
		$offset = $page*6;
		if ($page == 0){
			$page = 0;
			$offset = 0;
		}
	}else{
		$page = 0;
		$offset = 0;
	}
	if ($page < 0) {
		exit;
	}
	
	if (strlen($title) == 0) die("error");
	
	$stmt = $GLOBALS['dbcon']->prepare("SELECT catalogId FROM wearing WHERE uid = :uid AND type = :type");
	$stmt->bindParam(':type', $type, PDO::PARAM_STR);
	$stmt->bindParam(':uid', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
	$stmt->execute();
	if ($stmt->rowCount() == 0) {
		echo 'You are not wearing any item.';
	}
	foreach($stmt as $resultWearing) {
		$stmt = $GLOBALS['dbcon']->prepare("SELECT id, deleted, datafile, type, assetid, name, fileHash, imgTime FROM catalog WHERE id = :id;");
		$stmt->bindParam(':id', $resultWearing['catalogId'], PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($result['deleted'] == 0) {
			$itemName = $result['name'];
			if (strlen($itemName) > 16) {
				$itemName = substr($itemName, 0, 13) . '...';
			}
			echo '<div class="col-md-4 col-sm-4 col-xs-12 Center"><div style="box-shadow:none;height:170px;">'.htmlentities($itemName, ENT_QUOTES, "UTF-8").'<br>';
			echo '<img style="max-height:100px;display:inline" class="img-responsive" src="'.context::getItemThumbnailC($type, $result['assetid'], $result['datafile'], $result['fileHash'], $result['imgTime']).'">';
			echo '<br><button class="btn btn-primary" name="unwear" onclick="removeItem('.$result['id'].', \''.$result['type'].'\', '.$page.');">Unwear</button>';
			echo '</div></div>';
		}
	}
?>