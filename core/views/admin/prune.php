<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	if (!$GLOBALS['loggedIn']) {
		header("Location: /");
		exit;
	}
	if ($GLOBALS['userTable']['rank'] != 1) {
		header("Location: /");
		exit;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo config::getName();?> | Prune Posts</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<?php html::getNavigation();?>
		<div class="container">
			<div class="col-xs-12 col-sm-8 col-md-8 col-sm-offset-2 col-md-offset-2">
				<script src="/core/func/js/admin/prunePosts.js"></script>
				<div id="bStatus"></div>
				<h4>Prune Posts</h4>
				<p>This utility will remove all posts and replies of a certain user. Quite useful for if the user is a spammer and is not stopping.</p>
				<p>Please know that this can not be undone and that this will only have to be done if no other option is possible.</p>
				<input id="username" type="text" class="form-control" placeholder="Username"></input>
				<button id="prunePosts" class="btn btn-primary fullWidth">Prune posts</button>
			</div>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>