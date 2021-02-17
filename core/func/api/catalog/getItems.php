<?php
	if (isset($_GET['type'])) {
		$type = $_GET['type'];
		if (is_array($type)) die("Something is wrong");
		if ($type != "hats" and $type != "pants" and $type != "shirts" and $type != "decals" and $type != "heads" and $type != "faces" and $type != "tshirts" and $type != "gear") exit;
		if (isset($_GET['page'])) {
			$page = $_GET['page'];
			if (is_numeric($page) == false) exit;
		}else{
			$page = 0;
		}
		if (isset($_GET['term'])) {
			$term = $_GET['term'];
			if (is_array($term)) {
				exit;
			}
		}
		
		if (is_array($page)) {
			echo 'Something went wrong.';
			exit;
		}
		$offset = $page*15;
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		echo '<script>type = "'.$type.'";</script>';
		
		if (isset($term) and strlen($term) > 0) {
			$searchTermSQL = '%'.$term.'%';
			if ($GLOBALS['loggedIn']) $stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM catalog WHERE name LIKE :term AND approved = 1 AND type = :type ORDER BY id DESC LIMIT 16 OFFSET :offset");
			if (!$GLOBALS['loggedIn']) $stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM catalog WHERE name LIKE :term AND approved = 1 AND type = :type AND rbxasset = 0 ORDER BY id DESC LIMIT 16 OFFSET :offset");
			$stmt->bindParam(':term', $searchTermSQL, PDO::PARAM_STR);
		}else{
			if ($GLOBALS['loggedIn']) $stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM catalog WHERE type = :type AND buyable = 1 AND approved = 1 ORDER BY id DESC LIMIT 16 OFFSET :offset");
			if (!$GLOBALS['loggedIn']) $stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM catalog WHERE type = :type AND buyable = 1 AND approved = 1 AND rbxasset = 0 ORDER BY id DESC LIMIT 16 OFFSET :offset");
		}
		$stmt->bindParam(':type', $type, PDO::PARAM_STR);
		$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
		$stmt->execute();
		$count = 0;
		echo '<div class="row center">';
		foreach($stmt as $result) {
			$count++;
			if ($count < 16) {
				echo '<div class="col-xs-12 col-sm-12 col-md-4"><div class="well profileCard" style="height:150px;margin-bottom:0px;border:0px;">';
				echo '<img class="img-responsive" style="max-height:140px;max-width:140px;display:inline;" src="'.context::getItemThumbnailC($type, $result['assetid'], $result['datafile'], $result['fileHash'], $result['imgTime']).'">';
				echo '</div>';
				$itemName = context::secureString($result['name']);
				if (strlen($itemName) >= 40) {
					$itemName = substr($itemName, 0, 37). " ... ";
				}
				echo '<h5 style="float:left;">'.$itemName.'</h5>';
				if ($result['type'] != "decals") {
					if ($result['currencyType'] == 0) {
						echo '<h5 style="color:green;float:right;"><span class="fa fa-money"></span> '.$result['price'].'</h5><br>';
					}else{
						echo '<h5 style="color:#158cba;float:right;"><span class="fa fa-gg-circle"></span> '.$result['price'].'</h5><br>';
					}
				}
				echo '<a href="/catalog/item/'.$result['id'].'" class="btn btn-primary fullWidth" style="margin-bottom:10px;">Details</a>';
				echo '</div>';
			}
		}
		if ($count == 0) {
			echo '<p>Nothing found</p>';
		}
		if ($count > 15) {
			echo '<button class="btn btn-primary loadMore" onclick="loadMoreItems(\''.$type.'\', '.($page+1).'); page++;">Load More</button>';
		}
		echo '</div>';
	}else{
		echo 'An error occurred';
	}
?>