<?php
	if (!$GLOBALS['loggedIn']) {
		exit;
	}
?>
<li><a data-toggle="tooltip" data-placement="bottom" data-original-title="Posties, used to buy exclusive items, rewarded to you by Graphictoria staff"><span class="fa fa-gg-circle"></span> <span id="userPosties"><?php echo $GLOBALS['userTable']['posties'];?></span></a></li>
<li><a data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo context::humanTiming(strtotime($GLOBALS['userTable']['lastAward'])); ?> until next reward"><span class="fa fa-money"></span> <span id="userCoins"><?php echo $GLOBALS['userTable']['coins'];?></span></a></li>
<?php
	$query = "SELECT id FROM `friendRequests` WHERE `recvuid` = :id;";
	$stmt = $GLOBALS['dbcon']->prepare($query);
	$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
	$stmt->execute();
	$numRequests = $stmt->rowCount();
	
	if ($numRequests == 0) {
		echo '<li><a data-toggle="tooltip" data-placement="bottom" data-original-title="Friends" href="/friends"><span class="fa fa-users"></span></a></li>';
	}else{
		echo '<li><a data-toggle="tooltip" data-placement="bottom" data-original-title="Friends" href="/friends"><span class="fa fa-users"></span><span class="badge" style="background-color: #f44336;font-size:10px;padding: 6px 6px;"> '.$numRequests.'</span></a></li>';
	}
	
	$query = "SELECT id FROM `messages` WHERE `recv_uid` = :id AND `read` = 0";
	$stmt = $GLOBALS['dbcon']->prepare($query);
	$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
	$stmt->execute();
	$numMessages = $stmt->rowCount();
	
	if ($numMessages == 0) {
		echo '<li><a data-toggle="tooltip" data-placement="bottom" data-original-title="Messages" href="/user/messages"><span class="fa fa-envelope-open-o"></span></a></li>';
	}else{
		echo '<li><a data-toggle="tooltip" data-placement="bottom" data-original-title="Messages" href="/user/messages"><span class="fa fa-envelope-open-o"></span><span class="badge" style="background-color: #f44336;font-size:10px;padding: 6px 6px;"> '.$numMessages.'</span></a></li>';
	}
?>
<li><div id="charImg" style="position: relative;border:solid 1px #e7e7e7;height:50px;width:50px;height:50px;border-radius:50%;overflow: hidden;margin-top:3px;left:10px" class="img-circle"><a href="/user/profile/<?php echo $GLOBALS['userTable']['username'];?>"><img style="position: absolute;clip: rect(0px, 75px, 50px, 0);left:-18px" src="<?php echo context::getUserImage($GLOBALS['userTable']);?>" height="100"></div></a></li>
<li style="margin:3px 0px 0px;" class="dropdown">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> <?php echo $GLOBALS['userTable']['username']; ?><span class="caret"></span></a>
		<?php
			$addS2 = "margin-top:1px;";
			if ($GLOBALS['loggedIn'] && $GLOBALS['userTable']['themeChoice'] == 1) $addS2 = "margin-top:1px;color: #fff;border: 1px solid rgb(40, 40, 40);background-color: #333";
		?>
	<ul class="dropdown-menu" role="menu" style="<?php echo $addS2;?>">
		<li><a href="/user/profile/<?php echo $GLOBALS['userTable']['username']; ?>">Profile</a></li>
		<li><a href="/user/settings">Settings</a></li>
		<li><a href="/user/character">Character</a></li>
		<li class="divider"></li>
		<li><a href="/user/logout">Sign out</a></li>
	</ul>
</li>