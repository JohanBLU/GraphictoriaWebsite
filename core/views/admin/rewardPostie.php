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
		<title><?php echo config::getName();?> | Reward postie</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<?php html::getNavigation();?>
		<div class="container">
			<div class="col-xs-12 col-sm-8 col-md-8 col-sm-offset-2 col-md-offset-2">
				<script src="/core/func/js/admin/rPostie.js?3"></script>
				<div id="bStatus"></div>
				<h4>Reward Postie</h4>
				<p>Have you found great content? You can now reward users with posties! With posties, you can buy exclusive items on Graphictoria</p>
				<input id="username" type="text" class="form-control" placeholder="Username"></input>
				<button id="rewardPostie" class="btn btn-primary fullWidth">Reward postie</button>
			</div>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>