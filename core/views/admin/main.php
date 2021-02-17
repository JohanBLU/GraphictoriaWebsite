<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	if (!$GLOBALS['loggedIn']) {
		header("Location: /");
		exit;
	}
	if ($GLOBALS['userTable']['rank'] == 0) {
		header("Location: /");
		exit;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo config::getName();?> | Admin Panel</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<?php html::getNavigation();?>
		<div class="container">
			<h4>Admin Panel</h4>
			<p><b>Moderators and Administrators</b></p>
			<p><a href="/admin/ban">Ban User</a></p>
			<p><a href="/admin/unban">Unban User</a></p>
			<p><a href="/admin/reports">Reports</a></p>
			<p><a href="/admin/statistics">Statistics</a></p>
			<p><a href="/admin/rewardPostie">Reward Postie</a></p>
			<?php
				$stmt = $GLOBALS['dbcon']->prepare("SELECT id FROM catalog WHERE approved = 0 AND declined = 0;");
				$stmt->execute();
			?>
			<p><a href="/admin/assets">Asset Approval (<?php echo $stmt->rowCount();?>)</a></p>
			<br>
			<p><b>Administrators only</b></p>
			<p><a href="/admin/prune">Prune Posts</a></p>
			<p><a href="/admin/newHat">New Hat</a></p>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>