<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	if (isset($_GET['version'])) {
		$version = $_GET['version'];
		if (is_array($version) == true) exit;
		if ($version == 0) {
			$version = 0;
		}elseif ($version == 1) {
			$version = 1;
		}elseif ($version == 2) {
			$version = 2;
		}else{
			$version = 4; // All
		}
	}else{
		$version = 4; // All
	}
	$GLOBALS['gameVersion'] = $version;
	if ($version == 0) {
		$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM games WHERE public = 1 AND version = 0 ORDER BY id DESC");
	}elseif($version == 1) {
		$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM games WHERE public = 1 AND version = 1 ORDER BY id DESC");
	}elseif ($version == 2) {
		$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM games WHERE public = 1 AND version = 2 ORDER BY id DESC");
	}else{
		$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM games WHERE public = 1 ORDER BY id DESC");
	}
	$stmt->execute();
	if ($stmt->rowCount() == 0) {
		echo '<div class="well profileCard"><h4><span class="fa fa-frown-o"></span> Looks like there are no public games for this version!</h4><p>You could try adding your own server and setting it to public.</p></div>';
		exit;
	}
	
	function getOnline($ping) {
		$currentTime = date('Y-m-d H:i:s');
		$from_time = strtotime($ping);
		$to_time = strtotime($currentTime);
		$timeSince =  round(abs($to_time - $from_time) / 60,2);
		if ($timeSince > 2){
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
	
	function getDedicated($dedi) {
		if ($dedi == 1) return '<span class="fa fa-server" data-toggle="tooltip" data-placement="bottom" data-original-title="Dedicated Server"></span> ';
		return '';
	}
	
	function getPlayerCount($serverID, $dbcon, $dedicated, $pCount) {
		if ($dedicated == 0) {
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
		}else{
			return $pCount;
		}
	}
	
	function getDescription($description) {
		if (strlen($description) > 0) {
			return htmlentities($description, ENT_QUOTES, "UTF-8");
		}else{
			return '<font color="grey">No description.</font>';
		}
	}
	
	function getImage($result2, $serverID, $imgTime) {
		if (file_exists("/var/www/api/imageServer/server/".$serverID.".png") && $GLOBALS['loggedIn']) {
			return "https://api.xdiscuss.net/imageServer/server/".$serverID.".png?v=".strtotime($imgTime);
		}else{
			return context::getUserImage($result2);
		}
	}
	
	function getVersion($gVersion) {
		if ($GLOBALS['gameVersion'] == 4) {
			if ($gVersion == 0) $versionString = "2009";
			if ($gVersion == 1) $versionString = "2008";
			if ($gVersion == 2) $versionString = "2011";
			if ($gVersion == 3) $versionString = "2010";
			return '<b>Version : </b>'.$versionString.'<br>';
		}
	}
	
	$count = 0;
	if ($stmt->rowCount() > 0) {
		echo '<div class="row">';
	}
	foreach($stmt as $result) {
		if (getOnline2($result['lastPing']) == true) {
			$count++;
			$creator = $result['creator_uid'];
			$stmt = $GLOBALS['dbcon']->prepare("SELECT username, id, imgTime FROM users WHERE id = :id");
			$stmt->bindParam(':id', $creator, PDO::PARAM_INT);
			$stmt->execute();
			$result2 = $stmt->fetch(PDO::FETCH_ASSOC);
			$gameName = context::secureString($result['name']);
			if (strlen($gameName) >= 20) {
				$gameName = substr($gameName, 0, 17). " ... ";
			}
			echo '<div class="col-xs-12 col-sm-12 col-md-4 center" style="word-wrap:break-word;height:250px;max-height:250px;min-height:250px;margin-bottom:5px"><div class="well profileCard" style="height:250px;max-height:250px;min-height:250px">';
			echo '<h4>'.getDedicated($result['dedi']).$gameName.'</h4><b>Creator</b> : <a href="/user/profile/'.$result2['username'].'">'.$result2['username'].'</a><br><img width="75" src="'.getImage($result2, $result['id'], $result['imgTime']).'"><br><b>Status :</b> '.getOnline($result['lastPing']).'<br><b>Online Players :</b> '.getPlayerCount($result['id'], $dbcon, $result['dedi'], $result['numPlayers']).'<br>'.getVersion($result['version']).'<a onclick="viewGame('.$result['id'].');" class="btn btn-success">View</a></div>';
			echo '</div>';
		}
	}
	if ($stmt->rowCount() > 0) {
		echo '</div>';
	}
	
	if ($count == 0) {
		echo '<div class="well profileCard"><h4><span class="fa fa-frown-o"></span> Looks like there are no online games for this version!</h4><p>You could try adding your own server and setting it to public.</p></div>';
	}
	
	echo '<script>$("[data-toggle=\'tooltip\']").tooltip();</script>';
?>