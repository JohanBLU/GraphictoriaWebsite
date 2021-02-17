<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo config::getName();?> | Profile</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<?php html::getNavigation();?>
		<div class="container">
			<?php
				if (isset($_GET['username'])) {
					$username = $_GET['username'];
					if (is_array($username)) {
						echo 'No username specified</div>';
						html::buildFooter();
						exit;
					}
				}else{
					echo 'No username specified</div>';
					html::buildFooter();
					exit;
				}
				
				$stmt = $GLOBALS['dbcon']->prepare('SELECT * FROM users WHERE username = :username;');
				$stmt->bindParam(':username', $username, PDO::PARAM_STR);
				$stmt->execute();
				if ($stmt->rowCount() == 0) {
					echo 'User not found</div>';
					html::buildFooter();
					exit;
				}
				$result = $stmt->fetch(PDO::FETCH_ASSOC);
				$userID = $result['id'];
				$p_username = $result['username'];
				if ($result['banned'] == 1) {
					echo 'This user has been suspended</div>';
					html::buildFooter();
					exit;
				}
				echo '<script>var userId="'.$result['id'].'";</script>';
				
				$stmtr = $GLOBALS['dbcon']->prepare("SELECT id FROM `profile_views` WHERE `viewer` = :id AND `profile` = :pid;");
				$stmtr->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
				$stmtr->bindParam(':pid', $userID, PDO::PARAM_INT);
				$stmtr->execute();
				if ($stmtr->rowCount() == 0) {
					$found = false;
				}else{
					$found = true;
				}
				if ($found == false and $loggedIn == true) {
					$result['profileviews'] = $result['profileviews']+1;
					$query = "INSERT INTO `profile_views` (`viewer`, `profile`) VALUES (:userId, :profile);";
					$stmt = $GLOBALS['dbcon']->prepare($query);
					$stmt->bindParam(':profile', $userID, PDO::PARAM_INT);
					$stmt->bindParam(':userId', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
					$stmt->execute();
					
					$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET profileviews = profileviews + 1 WHERE id = :id");
					$stmt->bindParam(':id', $userID, PDO::PARAM_INT);
					$stmt->execute();
				}
			?>
			<div class="col-xs-12 col-sm-12 col-md-3">
				<script src="/core/func/js/profile.js?v=5"></script>
				<div id="pStatus"></div>
				<div class="well profileCard center">
					<p style="margin:0 0 2px"><?php echo context::getOnline($result).' '.$result['username']; ?></p>
					<?php
						if ($result['inGame'] == 1 and context::getOnline($result) == '<font color="green">&#x25CF; </font>') {
							if ($GLOBALS['loggedIn']) {
								$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM gameJoins WHERE uid = :id");
								$stmt->bindParam(':id', $result['id'], PDO::PARAM_INT);
								$stmt->execute();
								$resultGame = $stmt->fetch(PDO::FETCH_ASSOC);
								echo '<a href="GraphictoriaClient://'.$GLOBALS['userTable']['gameKey'].';'.$resultGame['gameId'].';'.$GLOBALS['userTable']['id'].'" class="btn btn-info"><span class="fa fa-play"></span> In Game || Follow</a><br>';
							}else{
								echo '<a class="btn btn-info disabled"><span class="fa fa-play"></span> In Game</a><br>';
							}
						}
					?>
					<img class="img-responsive" style="display:inline;" src="<?php echo context::getUserImage($result);?>">
					<?php
						if ($GLOBALS['loggedIn']) {
							if ($GLOBALS['userTable']['username'] != $result['username']) {
								echo '<div class="btn-group btn-group-justified">
								<a class="btn btn-primary" href="/user/messages+'.$result['username'].'"><span class="fa fa-envelope-open-o"></span> Send Message</a>';
								context::buildFriendButton($result['id']);
								echo '</div>';
							}
						}
					?>
					<p>
					<?php
						if (strlen($result['about']) == 0) {
							echo 'This user has not configured anything to display here.';
						}else{
							echo '<span style="word-wrap:break-word;">'.context::secureString($result['about']).'</span>';
						}
					?>
					</p>
				</div>
				<div class="well profileCard">
					<h4 style="margin:0 0 2px">Statistics</h4>
					<p style="margin:0px 0 5px;display:inline;"><b>Join Date</b>: <div style="float:right;display:inline;"><?php echo date('n/j/Y', strtotime($result['joinDate'])); ?></div></p>
					<p style="margin:0px 0 5px;display:inline;"><b>Last Seen</b>: <div style="float:right;display:inline;">
					<?php	if ($result['lastSeen'] !== null) {
								echo date('n/j/Y', strtotime($result['lastSeen'])); 
							}else{
								echo 'Never';
							}
						?>										
					</div></p>
					<p style="margin:0px 0 5px;display:inline;"><b>Forum Posts</b>: <div style="float:right;display:inline;"><?php echo $result['posts'];?></div></p>
					<p style="margin:0px 0 5px;display:inline;"><b>Profile views</b>: <div style="float:right;display:inline;"><?php echo $result['profileviews'];?></div></p>
				</div>
				<div class="well profileCard">
					<h4 style="margin:0 0 2px">Groups</h4>
					<?php
						$count = 0;
						$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM groups WHERE cuid = :id;");
						$stmt->bindParam(':id', $userID, PDO::PARAM_INT);
						$stmt->execute();
						foreach($stmt as $result) {
							$count++;
							echo '<a href="/groups/view/'.$result['id'].'">'.context::secureString($result['name']).'</a> ';
						}
						
						$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM group_members WHERE uid = :id;");
						$stmt->bindParam(':id', $userID, PDO::PARAM_INT);
						$stmt->execute();
						foreach($stmt as $result) {
							$count++;
							$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM groups WHERE id = :id");
							$gId = $result['gid'];
							$stmt->bindParam(':id', $gId, PDO::PARAM_INT);
							$stmt->execute();
							$resultGroupM = $stmt->fetch(PDO::FETCH_ASSOC);
							echo '<a href="/groups/view/'.$gId.'">'.context::secureString($resultGroupM['name']).'</a> ';
						}
						if ($count == 0) {
							echo '<div class="Center">This user is not in any group.</div>';
						}
					?>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-7">
				<div class="well profileCard">
					<h4 style="margin-top:4px">Friends</h4>
						<?php
							$stmtc = $GLOBALS['dbcon']->prepare("SELECT * FROM friends WHERE userId1 = :id;");
							$stmtc->bindParam(':id', $userID, PDO::PARAM_INT);
							$stmtc->execute();
								
							$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM friends WHERE userId1 = :id ORDER BY id DESC LIMIT 8;");
							$stmt->bindParam(':id', $userID, PDO::PARAM_INT);
							$stmt->execute();
							if ($stmt->rowCount() == 0) {
								echo 'This user has no friends.';
							}else{
								echo '<div class="row">';
							}
							echo '<script>$("#friendCount").html("Friends ('.$stmtc->rowCount().')");</script>';
							foreach($stmt as $result) {
								$userId = $result['userId2'];
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
							if ($stmt->rowCount() > 0) {
								echo '</div>';
							}
							if ($stmtc->rowCount() > 8) {
								echo '<a class="btn btn-primary" href="/friends/show/'.$p_username.'">Show all friends</a>';
							}
						?>
				</div>
				<div class="well profileCard">
					<h4 style="margin-top:4px">Badges</h4>
					<div class="row">
					<?php
						$stmt = $GLOBALS['dbcon']->prepare("SELECT badgeId FROM badges WHERE uid = :id");
						$stmt->bindParam(':id', $userID, PDO::PARAM_INT);
						$stmt->execute();
						if ($stmt->rowCount() == 0) {
							echo 'This user has no badges.';
						}
						$name = "";
						$description = "";
						foreach($stmt as $resultBadge) {
							if ($resultBadge['badgeId'] == 1) {
								$name = "Administrator";
								$description = "Owned by the administrators of Graphictoria. Owners of this badge control the whole entire website and can change everything. Users who do not have this badge are not administrators.";
							}
							if ($resultBadge['badgeId'] == 2) {
								$name = "Administrator";
								$description = "Owned by the administrators of Graphictoria. Owners of this badge control the whole entire website and can change everything. Users who do not have this badge are not administrators.";
							}
							if ($resultBadge['badgeId'] == 3) {
								$name = "Moderator";
								$description = "Owned by the moderators of Graphictoria. Owners of this badge moderate the website. Users who do not have this badge are not moderators.";
							}
							if ($resultBadge['badgeId'] == 4) {
								$name = "Forumer";
								$description = "Owned by the active forumers who managed to get 1000 posts!";
							}
							if ($resultBadge['badgeId'] == 5) {
								$name = "Member";
								$description = "This badge is owned by every single user of Graphictoria and proves that you have played Graphictoria.";
							}
							if ($resultBadge['badgeId'] == 6) {
								$name = "Roblox Staff";
								$description = "This badge is only awarded to verified, legitimate Roblox staff members.";
							}
							if ($resultBadge['badgeId'] == 7) {
								$name = "Before 100";
								$description = "Owned by the very first 100 users of Graphictoria. Users with this badge know the story of how we came to be.";
							}
							if ($resultBadge['badgeId'] == 8) {
								$name = "Gamer";
								$description = "This user has successfully joined a game at least once";
							}
							echo '<div data-toggle="tooltip" data-placement="top" data-original-title="'.$description.'" class="col-xs-12 col-sm-12 col-md-3 center"><img width="100" src="https://xdiscuss.net/html/img/badges/'.$resultBadge['badgeId'].'.png?v=5"><br><b>'.$name.'</b><br><br></div>';
							}
						?>
					</div>
				</div>
				<div class="well profileCard">
					<h4 style="margin-top:4px">Inventory</h4>
					<div class="btn-group btn-group-justified">
						<a class="btn" id="showHats">Hats</a>
						<a class="btn" id="showHeads">Heads</a>
						<a class="btn" id="showFaces">Faces</a>
						<a class="btn" id="showTshirts">T-Shirts</a>
						<a class="btn" id="showShirts">Shirts</a>
						<a class="btn" id="showPants">Pants</a>
						<a class="btn" id="showGear">Gear</a>
					</div>
					<div id="inventoryItems" class="row center">
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-2">
				<?php html::buildAds();?>
			</div>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>