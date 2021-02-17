<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo config::getName();?> | Item</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<script src="/core/func/js/item.js?v=2"></script>
				<?php
					if (isset($_GET['id'])) {
						$itemId = $_GET['id'];
						if (is_array($itemId) or strlen($itemId) == 0) {
							html::getNavigation();
							echo '<div class="container">';
							echo 'Incorrect itemId</div>';
							html::buildFooter();
							exit;
						}
					}else{
						html::getNavigation();
						echo '<div class="container">';
						echo 'No ID specified</div>';
						html::buildFooter();
						exit;
					}
					$stmt = $dbcon->prepare("SELECT * FROM catalog WHERE id=:id");
					$stmt->bindParam(':id', $itemId, PDO::PARAM_INT);
					$stmt->execute();
					if ($stmt->rowCount() == 0) {
						html::getNavigation();
						echo '<div class="container">';
						echo 'Item not found</div>';
						html::buildFooter();
						exit;
					}
					$result = $stmt->fetch(PDO::FETCH_ASSOC);
					$itemId = $result['id'];
					if ($result['deleted'] == 1 or $result['declined'] == 1 or $result['approved'] == 0) {
						html::getNavigation();
						echo '<div class="container">';
						echo 'Item not found</div>';
						html::buildFooter();
						exit;
					}
					
					if ($GLOBALS['loggedIn'] == false && $result['rbxasset'] == 1) {
						header("Location: /catalog");
						exit;
					}
					
					$stmt = $dbcon->prepare("SELECT * FROM users WHERE id=:id");
					$stmt->bindParam(':id', $result['creator_uid'], PDO::PARAM_INT);
					$stmt->execute();
					$result_user = $stmt->fetch(PDO::FETCH_ASSOC);
					
					if ($GLOBALS['loggedIn'] == true) {
						$stmt = $dbcon->prepare("SELECT * FROM ownedItems WHERE uid=:id AND catalogid = :catid");
						$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
						$stmt->bindParam(':catid', $itemId, PDO::PARAM_INT);
						$stmt->execute();
						if ($stmt->rowCount() > 0) {
							$owned = true;
						}else{
							$owned = false;
						}
					}
					
					$stmt = $dbcon->prepare("SELECT * FROM ownedItems WHERE catalogid = :catid");
					$stmt->bindParam(':catid', $itemId, PDO::PARAM_INT);
					$stmt->execute();
					$boughtTimes = $stmt->rowCount();
					html::getNavigation();
				?>
				<div class="container">
				<div class="col-xs-12 col-sm-4 col-md-8 col-sm-offset-2 col-md-offset-2">
				<?php
					if ($result['currencyType'] == 0) echo '<script>var cType = "coins";</script>';
					if ($result['currencyType'] == 1) echo '<script>var cType = "posties";</script>';
				?>
				<?php html::buildAds();?>
				<div id="iStatus"></div>
				<div class="well profileCard" style="margin-top:5px">
					<div class="row">
						<div class="col-xs-6">
							<h4><?php echo context::secureString($result['name']);?></h4>
							<img class="img-responsive" src="<?php echo context::getItemThumbnailC($result['type'], $result['assetid'], $result['datafile'], $result['fileHash'], $result['imgTime']);?>">
						</div>
						<div class="col-xs-6">
							<h4>Details</h4>
							<a href="/user/profile/<?php echo $result_user['username'];?>"><img height="150" width="150" src="<?php echo context::getUserImage($result_user);?>"></a>
							<?php
								echo '<p><b>Creator: </b><a href="/user/profile/'.$result_user['username'].'">'.$result_user['username'].'</a></p>';
								if ($result['type'] != "decals") {
									if ($result['currencyType'] == 0) echo '<p><b>Price: </b>'.$result['price'].' coins</p><p><b>Bought: </b>'.$boughtTimes.' times</p>';
									if ($result['currencyType'] == 1) echo '<p><b>Price: </b>'.$result['price'].' posties</p><p><b>Bought: </b>'.$boughtTimes.' times</p>';
								}
							?>
							<p><b>Date Created: </b><?php echo date('M j Y g:i:s A', strtotime($result['createDate'])); ?></p>
							<?php
								if ($GLOBALS['loggedIn'] && $result['type'] != "decals") {
									if ($owned) {
										echo '<button class="btn btn-primary fullWidth disabled">Already owned</button>';
									}else{
										if ($result['buyable'] == 1) {
											if ($result['currencyType'] == 0) {
												if ($GLOBALS['userTable']['coins'] > $result['price'] or $GLOBALS['userTable']['coins'] == $result['price']) {
													echo '<button class="btn btn-primary fullWidth" id="buyItem" onclick="buyItem('.$result['id'].')">Buy Item</button>';
												}else{
													echo '<button class="btn btn-primary fullWidth disabled">Insufficient Coins</button>';
												}
											}else{
												if ($GLOBALS['userTable']['posties'] > $result['price'] or $GLOBALS['userTable']['posties'] == $result['price']) {
													echo '<button class="btn btn-primary fullWidth" id="buyItem" onclick="buyItem('.$result['id'].')">Buy Item</button>';
												}else{
													echo '<button class="btn btn-primary fullWidth disabled">Insufficient Posties</button>';
												}
											}
										}else{
											echo '<button class="btn btn-primary fullWidth disabled">Buy Item</button>';
										}
									}
								}
								if ($loggedIn) {
									if ($GLOBALS['userTable']['rank'] > 0) {
										if ($result['type'] == "shirts" or $result['type'] == "tshirts" or $result['type'] == "pants" or $result['type'] == "decals") {
											echo '<button id="deleteItem" class="btn btn-danger fullWidth" style="margin-top:5px;" onclick="deleteItem('.$result['id'].')">Delete Item</button>';
										}
									}
								}
							?>
						</div>
					</div>
					<?php
						if ($result['type'] == "decals") {
							echo '<b>Use this link to use this decal in-game</b><br>';
							echo '<code style="word-wrap: break-word;white-space: pre-line;">http://xdiscuss.net/data/assets/uploads/'.$result['fileHash'].'</code><br>';
						}
					?>
					<b>Description</b><br>
					<?php
						if (strlen($result['description']) > 0) {
							echo context::secureString($result['description']);
						}else{
							echo '<p style="color:grey">No Description</p>';
						}
					?>
				</div>
			</div>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>