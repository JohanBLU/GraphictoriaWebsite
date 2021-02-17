<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo config::getName();?> | Friends</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<?php html::getNavigation();?>
		<div class="container">
			<?php
				if (isset($_GET['id'])) {
					$id = $_GET['id'];
					if (is_array($id)) {
						echo 'No ID specified.</div>';
						html::buildFooter();
						exit;
					}
				}else{
					echo 'No ID specified.</div>';
					html::buildFooter();
					exit;
				}
				$stmt = $GLOBALS['dbcon']->prepare("SELECT username, id FROM users WHERE username = :username");
				$stmt->bindParam(':username', $id, PDO::PARAM_INT);
				$stmt->execute();
				if ($stmt->rowCount() == 0) {
					echo 'User not found</div>';
					html::buildFooter();
					exit;
				}
				$result = $stmt->fetch(PDO::FETCH_ASSOC);
				$id = $result['id'];
			?>
			<script src="/core/func/js/friends/show.js"></script>
			<script>
				$(document).ready(function() {
					loadFriends(<?php echo $id;?>, 0);
				});
			</script>
			<h4><?php echo $result['username']; ?>'s friends</h4>
			<div class="col-xs-12 col-sm-12 col-md-10">
				<div class="well profileCard">
					<div id="friendsContainer">
						<div class="center"><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i></div>
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