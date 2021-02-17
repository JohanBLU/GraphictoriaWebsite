<?php
	if (isset($_GET['page'])) {
		$page = $_GET['page'];
		if (is_numeric($page) == false) exit;
		if (is_array($page)) {
			exit;
		}
		$offset = $page*10;
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
	
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	if ($GLOBALS['loggedIn'] == false) {
		exit;
	}
	$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM `friends` WHERE `userId1` = :id ORDER BY id DESC LIMIT 10 OFFSET :offset;");
	$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
	$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
	$stmt->execute();
	if ($stmt->rowCount() == 0 && $page == 0) {
		echo 'You have no Graphictoria friends. Why not make some?';
	}
	
	echo '<div class="row center">';
	$count = 0;
	foreach($stmt as $result) {
		$count++;
		if ($count < 10) {
			$userId = $result['userId2'];
			$stmt = $dbcon->prepare("SELECT username, imgTime, id, lastSeen FROM users WHERE id = :id;");
			$stmt->bindParam(':id', $userId, PDO::PARAM_INT);
			$stmt->execute();
			$resultuser = $stmt->fetch(PDO::FETCH_ASSOC);
			$username = $resultuser['username'];
			if (strlen($username) > 10) {
				$username = substr($username, 0, 7) . '...';
			}
			echo '<div class="col-xs-4"><a href="/user/profile/'.$resultuser['username'].'"><img width="100" src="'.context::getUserImage($resultuser).'"></a><br>';
			echo context::getOnline($resultuser);
			echo '<a href="/user/profile/'.$resultuser['username'].'"><b>'.context::secureString($username).'</b></a>
			<br><button value="'.$resultuser['id'].'" onclick="removeFriend('.$resultuser['id'].', '.$page.');" class="btn btn-danger btn-xs rmFr">Remove friend</button><br><br></div>';
		}
	}
	echo '</div>';
	echo '<div style="margin-left:15px;margin-right:15px;"><div class="btn-group btn-group-justified">';
		if ($page > 0) {
			echo '<a class="btn fullWidth" onclick="loadFriends('.($page-1).')">&laquo; Previous</a>';
		}
		if ($count > 9) {
			echo '<a class="btn fullWidth" onclick="loadFriends('.($page+1).')">Next &raquo;</a>';
		}
		if ($count == 0 and $page > 0) {
			exit;
		}
	echo '</div></div>';
?>