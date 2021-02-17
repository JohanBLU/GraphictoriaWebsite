<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	if ($GLOBALS['loggedIn'] == false) {
		exit;
	}
	if ($GLOBALS['userTable']['rank'] == 0) {
		exit;
	}
	
	$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM catalog WHERE approved = 0 AND declined = 0;");
	$stmt->execute();
	if ($stmt->rowCount() == 0) {
		echo '<p>There are no pending assets to approve at this moment.</p>';
	}
	$count = 0;
	foreach($stmt as $result) {
		$itemName = htmlentities($result['name'], ENT_QUOTES, "UTF-8");
		if (strlen($itemName) > 16) {
			$itemName = substr($itemName, 0, 7) . '...';
		}
		echo '<div class="col-md-4 col-sm-4 col-xs-12 Center"><div class="well" style="box-shadow:none;"><span class="content">'.$itemName.'</span><br>';
		$creator = $result['creator_uid'];
		$stmt = $dbcon->prepare("SELECT username FROM users WHERE id=:id;");
		$stmt->bindParam(':id', $creator, PDO::PARAM_INT);
		$stmt->execute();
		$result2 = $stmt->fetch(PDO::FETCH_ASSOC);
		$username = $result2['username'];
		echo '<img width="150" src="https://xdiscuss.net/data/assets/uploads/'.$result['fileHash'].'"><br><b>Type : '.$result['type'].'</b><br><b>Uploaded by <a href="/user/profile/'.$username.'">'.$username.'</a></b><br><button type="submit" name="acceptAsset" class="btn btn-success" onclick="approveAsset('.$result['id'].');">Accept</button><button type="submit" name="denyAsset" class="btn btn-danger" onclick="denyAsset('.$result['id'].');">Decline</button></div></div>';
	}
?>