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
		<title><?php echo config::getName();?> | Ban User</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<?php html::getNavigation();?>
		<div class="container">
			<div class="col-xs-12 col-sm-8 col-md-8 col-sm-offset-2 col-md-offset-2">
				<script src="/core/func/js/admin/banUser.js"></script>
				<div id="bStatus"></div>
				<h4>Ban User</h4>
				<input id="username" type="text" class="form-control" placeholder="Username"></input>
				<textarea id="banReason" type="text" rows="5" placeholder="Ban reason" class="form-control"></textarea>
				<label>Ban Type</label>
				<select id="duration" name="duration">
					<option value="1">Warning</option>
					<option value="2">1 Day</option>
					<option value="3">1 Week</option>
					<option value="4">1 Month</option>
					<option value="5">Forever</option>
				</select>
				<button id="banUser" class="btn btn-primary fullWidth">Ban user</button>
			</div>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>