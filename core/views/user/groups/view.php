<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo config::getName();?> | Group</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<?php html::getNavigation();?>
		<div class="container">
			<?php
				if (isset($_GET['id'])) {
					if (is_array($_GET['id']) or strlen($_GET['id']) == 0) {
						echo 'No group ID specified';
						exit;
					}
					$id = $_GET['id'];
				}else{
					echo 'No group ID specified';
					exit;
				}
				
				$stmt = $dbcon->prepare("SELECT * FROM groups WHERE id = :id");
				$stmt->bindParam(':id', $id, PDO::PARAM_INT);
				$stmt->execute();
				if ($stmt->rowCount() == 0) {
					echo 'Group has not been found';
					exit;
				}
				$result = $stmt->fetch(PDO::FETCH_ASSOC);
				$creator_uid = $result['cuid'];
				$id = $result['id'];
			?>
			<?php html::buildAds();?>
			<script src="/core/func/js/viewGroup.js"></script>
			<script>
				$(document).ready(function() {
					getMembers(<?php echo $id;?>, 0);
				});
			</script>
			<div id="gStatus"></div>
			<div class="well profileCard">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-4">
						<h4><?php echo context::secureString($result['name']);?></h4>
						<?php
							$stmt = $GLOBALS['dbcon']->prepare("SELECT imgTime, id, username FROM users WHERE id = :id");
							$stmt->bindParam(':id', $creator_uid, PDO::PARAM_INT);
							$stmt->execute();
							$resultuser = $stmt->fetch(PDO::FETCH_ASSOC);
						?>
						<img height="150" width="150" class="img-responsive" style="display:inline;" src="<?php echo context::getUserImage($resultuser);?>">
						<p><b>Creator</b>: <a href="/user/profile/<?php echo $resultuser['username'];?>"><?php echo $resultuser['username'];?></a></p>
						<p><b>Date Created</b>: <?php echo date('M j Y g:i:s A', strtotime($result['creationDate'])); ?></p>
						<?php
							if ($GLOBALS['loggedIn'] == true) {
								if ($GLOBALS['userTable']['id'] == $result['cuid']) {
									echo '<button class="btn btn-danger FullWidth" id="leaveDelete" onclick="leaveDelete('.$id.');">Leave and delete Group</button>';
								}else{
									$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM group_members WHERE uid = :uid AND gid = :id");
									$stmt->bindParam(':id', $id, PDO::PARAM_INT);
									$stmt->bindParam(':uid', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
									$stmt->execute();
									if ($stmt->rowCount() == 0) {
										echo '<button class="btn btn-success FullWidth" id="joinGroup" onclick="joinGroup('.$id.');">Join Group</button>';
									}else{
										echo '<button class="btn btn-danger FullWidth" id="leaveGroup" onclick="leaveGroup('.$id.');">Leave Group</button>';
									}
								}
								if ($GLOBALS['userTable']['id'] == $result['cuid'] or $GLOBALS['userTable']['rank'] > 0) {
									echo '<a class="btn btn-success FullWidth" href="/groups/admin/'.$id.'">Group Admin</a>';
								}
							}else{
								echo '<button class="btn btn-success FullWidth disabled">Join Group</button>';
							}
						?>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-8" style="word-wrap:break-word;">
						<h4>Description</h4>
						<?php
							if (strlen($result['description']) == 0) {
								echo '<i>No Description</i>';
							}else{
								echo '<p>'.nl2br(context::secureString($result['description'])).'</p>';
							}
						?>
					</div>
				</div>
			</div>
			<div class="well profileCard">
				<h4 id="memberCount">Members</h4>
				<div id="memberField"></div>
			</div>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>