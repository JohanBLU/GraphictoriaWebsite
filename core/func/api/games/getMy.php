<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	function getOnline($ping) {
		$currentTime = date('Y-m-d H:i:s');
		$from_time = strtotime($ping);
		$to_time = strtotime($currentTime);
		$timeSince =  round(abs($to_time - $from_time) / 60,2);
		if ($timeSince > 5){
			return '<font color="grey">Offline</font>';
		}else{
			return '<font color="green">Online</font>';
		}
	}
			
	function getDescription($description) {
		if (strlen($description) > 0) {
			return htmlentities($description, ENT_QUOTES, "UTF-8");
		}else{
			return '<font color="grey">No description.</font>';
		}
	}
	
	function getOnline2($ping) {
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
			if (getOnline2($result['lastSeen']) == true and $result['inGame'] == 1) {
				$count++;
			}
		}
		return $count;
	}
	
	if ($GLOBALS['loggedIn']) {
		$stmt = $dbcon->prepare("SELECT * FROM gameKeys WHERE userid = :id;");
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		$count = 0;
		foreach($stmt as $result) {
			if (isset($_GET['version'])) {
				$version = $_GET['version'];
				if (is_array($version) == true) exit;
				if ($version != 1 && $version != 0 && $version != 2) exit;
				$gameId = $result['key'];
				$stmt = $dbcon->prepare("SELECT * FROM games WHERE `key` = :key AND `version` = :version;");
				$stmt->bindParam(':version', $version, PDO::PARAM_INT);
				$stmt->bindParam(':key', $gameId, PDO::PARAM_STR);
			}else{
				$stmt = $dbcon->prepare("SELECT * FROM games WHERE `key` = :key;");
				$stmt->bindParam(':key', $gameId, PDO::PARAM_STR);
			}
			$stmt->execute();
			if ($stmt->rowCount() > 0) {
				$count++;
			}
			
			foreach($stmt as $result) {
				$creator = $result['creator_uid'];
				$stmt = $dbcon->prepare("SELECT * FROM users WHERE id = :id");
				$stmt->bindParam(':id', $creator, PDO::PARAM_INT);
				$stmt->execute();
				$result2 = $stmt->fetch(PDO::FETCH_ASSOC);
				echo '<div class="col-xs-12 col-sm-12 col-md-4 center" style="word-wrap:break-word;"><div class="well profileCard">';
				echo '<h4>'.htmlentities(user::filter($result['name']), ENT_QUOTES, "UTF-8").'</h4><b>Creator</b> : <a href="/user/profile/'.$result2['username'].'">'.$result2['username'].'</a><br><img width="75" src="'.context::getUserImage($result2).'"><br><b>Status :</b> '.getOnline($result['lastPing']).'<br><b>Online Players :</b> '.getPlayerCount($result['id'], $dbcon).'<br><a href="/games/view/'.$result['id'].'" class="btn btn-success">View</a></div>';
				echo '</div>';
			}
		}
		if ($count == 0) {
			echo '<div class="well profileCard"><h4><span class="fa fa-frown-o"></span> Nothing found</h4><p>Looks like there is nothing here</p></div>';
		}
	}else{
		echo '<div class="well profileCard"><h4><span class="fa fa-frown-o"></span> You need to be logged in</h4><p>Please login and try again.</p></div>';
	}
?>