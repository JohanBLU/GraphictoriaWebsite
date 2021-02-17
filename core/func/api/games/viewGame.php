<?php
	function getOnline($ping) {
		$currentTime = date('Y-m-d H:i:s');
		$from_time = strtotime($ping);
		$to_time = strtotime($currentTime);
		$timeSince =  round(abs($to_time - $from_time) / 60,2);
		if ($timeSince > 2) {
			return false;
		}else{
			return true;
		}
	}
	
	function getPlayerCount($serverID, $dbcon) {
		$count = 0;
		$stmt = $GLOBALS['dbcon']->prepare("SELECT lastSeen, inGame FROM users WHERE inGameId = :id");
		$stmt->bindParam(':id', $serverID, PDO::PARAM_INT);
		$stmt->execute();
		foreach($stmt as $result) {
			if (getOnline($result['lastSeen']) == true and $result['inGame'] == 1) {
				$count++;
			}
		}
		return $count;
	}
	
	if (isset($_GET['id'])) {
		$gameID = $_GET['id'];
		if (is_array($gameID)) {
			exit;
		}
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$stmt = $GLOBALS['dbcon']->prepare('SELECT * FROM games WHERE id= :id');
		$stmt->bindParam(':id', $gameID, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($stmt->rowCount() == 0) {
			echo 'Game not found!';
			echo '<script>$(".gameTitle").html("Error")</script>';
			exit;
		}
		echo '<script>$(".gameTitle").html(\''.context::secureString($result['name']).'\')</script>';
		$stmt = $dbcon->prepare("SELECT username, id, imgTime FROM users WHERE id = :id");
		$stmt->bindParam(':id', $result['creator_uid'], PDO::PARAM_INT);
		$stmt->execute();
		$resultuser = $stmt->fetch(PDO::FETCH_ASSOC);
		echo '<div class="col-xs-6">';
		if (file_exists("/var/www/api/imageServer/server/".$result['id'].".png") && $GLOBALS['loggedIn']) {
			echo "<img style=\"max-height:100%;max-width:100%\" src=\"https://api.xdiscuss.net/imageServer/server/".$result['id'].".png?v=".strtotime($result['imgTime'])."\">";
		}
		echo '</div>';
		echo '<div class="col-xs-6">';
		echo '<a href="/user/profile/'.$resultuser['username'].'"><img width="150" height="150" src="'.context::getUserImage($resultuser).'"></a><br>';
		echo '<b>Creator</b> : <a href="/user/profile/'.$resultuser['username'].'">'.$resultuser['username'].'</a><br>';
		echo '<b>Created</b> : '.date('M j Y g:i A', strtotime($result['date'])).'<br>';
		if (getOnline($result['lastPing'])) {
			echo '<b>Status</b> : <span style="color:green">Online</span><br>';
		}else{
			echo '<b>Status</b> : <span style="color:grey">Offline</span><br>';
		}
		if ($result['dedi'] == 0)
			echo '<b>Online Players</b> : '.getPlayerCount($result['id'], $GLOBALS['dbcon']).'<br>';
		if ($result['dedi'] == 1) {
			echo '<b>Online Players</b> : '.$result['numPlayers'].'<br>';
		}
		if ($GLOBALS['loggedIn']) {
			if ($result['version'] == 0) echo '<a class="btn btn-success fullWidth" href="GraphictoriaClient://'.$GLOBALS['userTable']['gameKey'].';'.$result['id'].';'.$GLOBALS['userTable']['id'].'">Play</a>';
			if ($result['version'] == 1) echo '<a class="btn btn-success fullWidth" href="GraphictoriaClient2://'.$GLOBALS['userTable']['gameKey'].';'.$result['id'].';'.$GLOBALS['userTable']['id'].'">Play</a>';
			if ($result['version'] == 2) echo '<a class="btn btn-success fullWidth" href="GraphictoriaClient3://'.$GLOBALS['userTable']['gameKey'].';'.$result['id'].';'.$GLOBALS['userTable']['id'].'">Play</a>';
		}else{
			echo '<a class="btn btn-success disabled fullWidth">Play</a>';
		}
		echo '<a style="margin-top:5px" class="btn btn-warning fullWidth" href="/games/view/'.$result['id'].'">Full Page</a>';
		echo '</div></div>';
	}else{
		echo 'An error occurred';
	}
?>
