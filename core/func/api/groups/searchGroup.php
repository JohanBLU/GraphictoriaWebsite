<?php
	if (isset($_GET['term'])) {
		$searchTerm = $_GET['term'];
	}else{
		exit;
	}
	if (isset($_GET['page'])) {
		$page = $_GET['page'];
		if (is_array($page)) {
			exit;
		}
		$offset = $page*12;
	}else{
		$page = 0;
		$offset = 0;
	}
	if (is_numeric($page) == false) exit;
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	$searchTermSQL = '%'.$searchTerm.'%';
	if (strlen($searchTerm) == 0) {
		$stmt = $dbcon->prepare("SELECT * FROM groups WHERE name LIKE :term ORDER BY id DESC LIMIT 11 OFFSET :offset;");
	}else{
		$stmt = $dbcon->prepare("SELECT * FROM groups WHERE name LIKE :term ORDER BY name ASC LIMIT 11 OFFSET :offset;");
	}
	$stmt->bindParam(':term', $searchTermSQL, PDO::PARAM_STR);
	$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
	$stmt->execute();
	if ($stmt->rowCount() == 0) {
		echo '<div class="well profileCard">Nothing found.</div>';
	}
	$count = 0;
	foreach($stmt as $result) {
		$count++;
		if ($count < 11) {
			echo '<div class="well profileCard">';
			echo '<div class="row">';
			echo '<div class="col-xs-3">';
			echo '<a href="/groups/view/'.$result['id'].'"><img height="150" width="150" src="'.context::getGroupImage($result['cuid']).'"></a>';
			echo '</div>';
			echo '<div class="col-xs-9">';
			if ($result['description'] == NULL) {
				$description = "<i>This user has not configured anything to display here</i>";
			}else{
				$description = '<span style="word-wrap:break-word;">'.context::secureString($result['description']).'</span>';
			}
			
			echo '<h4><a href="/groups/view/'.$result['id'].'">'.$result['name'].'</h4></a><b style="display:inline">Description: </b><p style="display:inline">'.$description.'</p>';
			echo '</div></div></div>';
		}
	}
	if ($count > 10) {
		echo '<button class="btn btn-primary fullWidth searchGroup" onclick="loadMore('.($page+1).', \''.context::secureString($searchTerm).'\');">Load More</button>';
	}
?>