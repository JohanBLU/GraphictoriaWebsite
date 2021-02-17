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
		<title><?php echo config::getName();?> | Unban User</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<?php html::getNavigation();?>
		<div class="container">
			<div class="col-xs-12 col-sm-8 col-md-8 col-sm-offset-2 col-md-offset-2">
				<script src="/core/func/js/admin/unbanUser.js"></script>
				<div id="bStatus"></div>
				<h4>Unban User</h4>
				<input id="username" type="text" class="form-control" placeholder="Username"></input>
				<button id="unbanUser" class="btn btn-primary fullWidth">Unban user</button>
			</div>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>