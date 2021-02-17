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
	
	function getDescription($description) {
		if (strlen($description) > 0) {
			return htmlentities($description, ENT_QUOTES, "UTF-8");
		}else{
			return '<font color="grey">No description.</font>';
		}
	}
	
	if ($GLOBALS['loggedIn']) {
		$stmt = $dbcon->prepare("SELECT * FROM games WHERE `creator_uid` = :id ORDER BY id DESC LIMIT 1;");
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		if ($stmt->rowCount() == 0) {
			echo '<div class="well profileCard"><h4><span class="fa fa-frown-o"></span> Nothing found</h4><p>Looks like there is nothing here</p></div>';
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
	}else{
		echo '<div class="well profileCard"><h4><span class="fa fa-frown-o"></span> You need to be logged in</h4><p>Please login and try again.</p></div>';
	}
?>