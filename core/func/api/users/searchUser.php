<?php
	if (isset($_GET['term'])) {
		$searchTerm = $_GET['term'];
	}else{
		exit;
	}
	if (isset($_GET['page'])) {
		$page = $_GET['page'];
		if (is_numeric($page) == false) exit;
		if (is_array($page)) {
			exit;
		}
		$offset = $page*12;
	}else{
		$page = 0;
		$offset = 0;
	}
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	$searchTermSQL = '%'.$searchTerm.'%';
	if (strlen($searchTerm) == 0) {
		$stmt = $dbcon->prepare("SELECT * FROM users WHERE username LIKE :term AND banned = 0 ORDER BY lastSeen DESC LIMIT 11 OFFSET :offset;");
	}else{
		$stmt = $dbcon->prepare("SELECT * FROM users WHERE username LIKE :term AND banned = 0 ORDER BY username ASC LIMIT 11 OFFSET :offset;");
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
			echo '<a href="/user/profile/'.$result['username'].'"><img height="150" width="150" src="'.context::getUserImage($result).'"></a>';
			echo '</div>';
			echo '<div class="col-xs-9">';
			if ($result['lastSeen'] == NULL) {
				$lastSeen = "Never";
			}else{
				$lastSeen = date('M j Y g:i A', strtotime($result['lastSeen']));
			}
			
			if ($result['about'] == NULL) {
				$about = "<i>This user has not configured anything to display here</i>";
			}else{
				$about = '<span style="word-wrap:break-word;">'.context::secureString($result['about']).'</span>';
			}
			
			echo '<h4>'.context::getOnline($result).' <a href="/user/profile/'.$result['username'].'">'.$result['username'].'</h4></a><b>Last seen:</b> '.$lastSeen.'<br><b style="display:inline">About: </b><p style="display:inline">'.$about.'</p>';
			echo '</div></div></div>';
		}
	}
	if ($count > 10) {
		echo '<button class="btn btn-primary fullWidth searchUser" onclick="loadMore('.($page+1).', \''.context::secureString($searchTerm).'\');">Load More</button>';
	}
?>