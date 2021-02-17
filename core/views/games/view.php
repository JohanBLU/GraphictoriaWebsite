<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo config::getName();?> | View Server</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<?php html::getNavigation();?>
		<div class="container">
			<?php
				if (isset($_GET['id'])) {
					$id = $_GET['id'];
					if (is_array($id)) {
						echo 'ID not specified';
						exit;
					}
				}else{
					echo 'ID not specified';
					exit;
				}
				$stmt = $dbcon->prepare("SELECT * FROM games WHERE id = :id;");
				$stmt->bindParam(':id', $id, PDO::PARAM_INT);
				$stmt->execute();
				if ($stmt->rowCount() == 0) {
					echo 'Server not found';
					exit;
				}
				$result = $stmt->fetch(PDO::FETCH_ASSOC);
				$id = $result['id'];
				if ($result['public'] == 0) {
					$gameKey = $result['key'];
					$stmtU = $dbcon->prepare("SELECT * FROM gameKeys WHERE userid=:id AND `key` = :key;");
					$stmtU->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
					$stmtU->bindParam(':key', $gameKey, PDO::PARAM_STR);
					$stmtU->execute();
					if ($GLOBALS['loggedIn']) {
						if ($stmtU->rowCount() == 0 and $result['creator_uid'] != $GLOBALS['userTable']['id'] and $GLOBALS['userTable']['rank'] == 0) {
							echo 'Server not found';
							exit;
						}
					}else{
						echo 'Server not found';
						exit;
					}
				}
				$stmt = $dbcon->prepare("SELECT username, id, imgTime FROM users WHERE id = :id");
				$stmt->bindParam(':id', $result['creator_uid'], PDO::PARAM_INT);
				$stmt->execute();
				$resultuser = $stmt->fetch(PDO::FETCH_ASSOC);
			?>
			<?php html::buildAds();?>
			<script src="/core/func/js/viewServer.js"></script>
			<div class="col-xs-12 col-sm-4 col-md-6 col-sm-offset-2 col-md-offset-3col-xs-12 col-sm-4 col-md-6 col-sm-offset-2 col-md-offset-3">
				<div id="vStatus"></div>
				<div class="well profileCard">
					<div class="row">
						<div class="col-md-4 col-sm-12 col-xs-12">
							<a href="/user/profile/<?php echo $resultuser['username'];?>"><img width="150" height="150" src="<?php echo context::getUserImage($resultuser);?>"></a>
						</div>
						<div class="col-md-8 col-sm-12 col-xs-12" style="word-wrap:break-word;">
							<?php
								if ($result['version'] == 0) $vString = "2009";
								if ($result['version'] == 1) $vString = "2008";
								if ($result['version'] == 2) $vString = "2011";
							?>
							<h4><b><?php echo $vString; ?></b> <?php echo context::secureString($result['name']);?></h4>
							<p style="margin:0 0 0px"><b>Creator</b>: <a href="/user/profile/<?php echo $resultuser['username'];?>"><?php echo $resultuser['username'];?></a></p>
							<p style="margin:0 0 0px"><b>Date Created</b>: <?php echo date('M j Y g:i A', strtotime($result['date']));?></p>
							<?php
								if (strlen($result['description']) == 0) {
									echo '<p style="margin:0 0 0px"><b>Description:</b> <i>None</i></p>';
								}else{
									echo '<p style="margin:0 0 0px"><b>Description:</b> '.user::filter(context::secureString($result['description'])).'</p>';
								}
								
								if ($GLOBALS['loggedIn']) {
									if ($result['version'] == 0) echo '<a class="btn btn-success fullWidth" href="GraphictoriaClient://'.$GLOBALS['userTable']['gameKey'].';'.$result['id'].';'.$GLOBALS['userTable']['id'].'">Play</a>';
									if ($result['version'] == 1) echo '<a class="btn btn-success fullWidth" href="GraphictoriaClient2://'.$GLOBALS['userTable']['gameKey'].';'.$result['id'].';'.$GLOBALS['userTable']['id'].'">Play</a>';
									if ($result['version'] == 2) echo '<a class="btn btn-success fullWidth" href="GraphictoriaClient3://'.$GLOBALS['userTable']['gameKey'].';'.$result['id'].';'.$GLOBALS['userTable']['id'].'">Play</a>';
								}else{
									echo '<a class="btn btn-success disabled fullWidth">Play</a>';
								}
								
								if ($GLOBALS['loggedIn']) {
									if ($GLOBALS['userTable']['rank'] > 0 or $GLOBALS['userTable']['id'] == $result['creator_uid']) {
										if ($result['dedi'] == 0) echo '<button id="deleteServer" onclick="deleteServer('.$id.')" class="btn btn-danger fullWidth">Delete Server</button>';
									}
									if ($result['dedi'] == 1) {
										if ($GLOBALS['userTable']['rank'] == 1 || $GLOBALS['userTable']['id'] == $result['creator_uid']) echo '<button id="deleteServer" onclick="deleteServer('.$id.')" class="btn btn-danger fullWidth">Shutdown Server</button>';
									}
								}
							?>
						</div>
					</div>
				</div>
				<div class="well profileCard">
					<h4 id="playerCount">Online Players</h4>
					<div class="row center">
						<?php
							$stmt = $dbcon->prepare("SELECT lastSeen, inGame, username, imgTime, id FROM users WHERE inGameId = :id ORDER BY id");
							$stmt->bindParam(':id', $id, PDO::PARAM_INT);
							$stmt->execute();
							$count = 0;
							foreach($stmt as $resultk) {
								$from_time = strtotime($resultk['lastSeen']);
								$to_time = strtotime(context::getCurrentTime());
								$timeSince =  round(abs($to_time - $from_time) / 60,2);
								if ($timeSince < 2 and $resultk['inGame'] == 1) {
									$count++;
									$username = $resultk['username'];
									if (strlen($username) > 10) {
										$username = substr($username, 0, 7) . '...';
									}
									echo '<div class="col-xs-4"><br>';
									echo '<a href="/user/profile/'.$resultk['username'].'"><img width="120" src="'.context::getUserImage($resultk).'"></a><br>';
									echo '<a href="/user/profile/'.$resultk['username'].'"><b>'.context::secureString($username).'</b></a><br><br></div>';
								}
							}
							if ($count == 0) {
								echo 'There is nobody online.';
							}
							echo '<script>$("#playerCount").html("Online Players ('.$count.')");</script>';
						?>
					</div>
				</div>
				<?php
					if ($GLOBALS['loggedIn']) {
						if ($GLOBALS['userTable']['id'] == $result['creator_uid'] && $result['dedi'] == 0) {
							echo '<div class="well"><h4>Command</h4><p>Use this command to start your server</p><code>dofile("http://api.xdiscuss.net/serverscripts/server.php?key='.$result['privatekey'].'")</code></div>';
						}
						if ($result['public'] == 0) {
							echo '<div class="well"><h4>Invites</h4><p>Use this key to invite people to your server</p><code>'.$result['key'].'</code></div>';
						}
					}
				?>
			</div>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>