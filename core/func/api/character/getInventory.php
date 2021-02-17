<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	if ($GLOBALS['loggedIn'] == false) {
		exit;
	}
	
	if (isset($_GET['type'])) $type = $_GET['type'];
	if (!isset($_GET['type'])) exit;
	if (is_array($_GET['type'])) exit;
	
	$title = "";
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
	
	if (strlen($title) == 0) die("error");
	
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
	if ($page < 0) exit;
	
	$stmt = $GLOBALS['dbcon']->prepare("SELECT catalogid FROM ownedItems WHERE type = :type AND uid = :uid AND deleted=0 ORDER BY id DESC LIMIT 7 OFFSET :offset;");
	$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
	$stmt->bindParam(':type', $type, PDO::PARAM_STR);
	$stmt->bindParam(':uid', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
	$stmt->execute();
	if ($stmt->rowCount() == 0) {
		echo 'No items found.';
	}
	$count = 0;
	foreach($stmt as $resultOwned) {
		$count++;
		if ($count < 7) {
			$wearing = false;
			$disableWear = false;
			$stmt = $GLOBALS['dbcon']->prepare("SELECT id FROM wearing WHERE uid = :uid AND catalogid = :id");
			$stmt->bindParam(':id', $resultOwned['catalogid'], PDO::PARAM_INT);
			$stmt->bindParam(':uid', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
			$stmt->execute();
			if ($stmt->rowCount() > 0) {
				$wearing = true;
			}
				
			$stmt = $GLOBALS['dbcon']->prepare("SELECT id FROM wearing WHERE uid = :uid AND type = :type");
			$stmt->bindParam(':type', $type, PDO::PARAM_STR);
			$stmt->bindParam(':uid', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
			$stmt->execute();
			if ($stmt->rowCount() > 4 and $type == "hats") {
				$disableWear = true;
			}
			
			$stmt = $GLOBALS['dbcon']->prepare("SELECT deleted, name, type, datafile, assetid, id, fileHash, imgTime FROM catalog WHERE id = :id");
			$stmt->bindParam(':id', $resultOwned['catalogid'], PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($result['deleted'] == 0) {
				$itemName = $result['name'];
				if (strlen($itemName) > 16) {
					$itemName = substr($itemName, 0, 13) . '...';
				}
				
				echo '<div class="col-md-4 col-sm-4 col-xs-12 Center"><div style="box-shadow:none;height:170px;">'.htmlentities($itemName, ENT_QUOTES, "UTF-8").'<br>';
				echo '<img style="max-height:100px;display:inline" class="img-responsive" src="'.context::getItemThumbnailC($type, $result['assetid'], $result['datafile'], $result['fileHash'], $result['imgTime']).'">';
				if ($wearing == true) {
					echo '<br><button class="btn btn-primary" name="unwear" onclick="removeItem('.$result['id'].', \''.$result['type'].'\', '.$page.');">Unwear</button>';
				}else{
					if ($disableWear == false) {
						echo '<br><button class="btn btn-primary" name="wear" onclick="wearItem('.$result['id'].', \''.$result['type'].'\', '.$page.');">Wear</button>';
					}else{
						echo '<br><a class="btn btn-primary disabled">Wear</a>';
					}
				}
				echo '</div></div>';
			}
		}
	}
	echo '<div style="margin-left:15px;margin-right:15px;"><div class="btn-group btn-group-justified">';
		if ($page > 0) {
			echo '<a class="btn fullWidth" onclick="loadPage(\''.$type.'\', '.($page-1).')">&laquo; Previous</a>';
		}
		if ($count > 6) {
			echo '<a class="btn fullWidth" onclick="loadPage(\''.$type.'\', '.($page+1).')">Next &raquo;</a>';
		}
		if ($count == 0 and $page > 0) {
			exit;
		}
	echo '</div></div>';
?>