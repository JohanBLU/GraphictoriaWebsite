<?php
	if (isset($_GET['gid'])) {
		$groupId = $_GET['gid'];
		if (is_array($groupId)) {
			exit;
		}
	}
	if (isset($_GET['page'])) {
		$page = $_GET['page'];
	}else{
		$page = 0;
	}
	if (is_array($page)) {
		exit;
	}
	if (is_numeric($page) == false) {
		exit;
	}
	if (is_numeric($groupId) == false) {
		exit;
	}
	$offset = $page*9;
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	
	$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM group_members WHERE gid = :id ORDER BY id DESC LIMIT 9 OFFSET :offset;");
	$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
	$stmt->bindParam(':id', $groupId, PDO::PARAM_INT);
	$stmt->execute();
	
	$stmtc = $GLOBALS['dbcon']->prepare("SELECT id FROM group_members WHERE gid = :id");
	$stmtc->bindParam(':id', $groupId, PDO::PARAM_INT);
	$stmtc->execute();
	echo '<script>$("#memberCount").html("Members ('.$stmtc->rowCount().')");</script>';
	
	$count = 0;
	if ($stmt->rowCount() == 0) {
		echo 'No members found';
	}
	echo '<div class="row">';
	foreach($stmt as $result) {
		$count++;
		if ($count < 9) {
			$userId = $result['uid'];
			$stmt = $GLOBALS['dbcon']->prepare("SELECT username, imgTime, lastSeen, id FROM users WHERE id = :id");
			$stmt->bindParam(':id', $userId, PDO::PARAM_INT);
			$stmt->execute();
			$resultuser = $stmt->fetch(PDO::FETCH_ASSOC);
			$username = $resultuser['username'];
			if (strlen($username) > 10) {
				$username = substr($username, 0, 7) . '...';
			}
			echo '<div class="col-xs-12 col-sm-12 col-md-3 center"><br>';
			echo '<a href="/user/profile/'.$resultuser['username'].'"><img width="120" src="'.context::getUserImage($resultuser).'"></a><br>';
			echo context::getOnline($resultuser);
			echo '<a href="/user/profile/'.$resultuser['username'].'"><b>'.htmlentities($username, ENT_QUOTES, "UTF-8").'</b></a><br><br></div>';
		}
	}
	echo '<div style="margin-left:15px;margin-right:15px;"><div class="btn-group btn-group-justified">';
		if ($page > 0) {
			echo '<a class="btn fullWidth" onclick="getMembers(\''.$groupId.'\', '.($page-1).')">&laquo; Previous</a>';
		}
		if ($count > 6) {
			echo '<a class="btn fullWidth" onclick="getMembers(\''.$groupId.'\', '.($page+1).')">Next &raquo;</a>';
		}
		if ($count == 0 and $page > 0) {
			exit;
		}
	echo '</div></div></div>';
?>