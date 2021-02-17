<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	if (!$GLOBALS['loggedIn']) {
		header("Location: /");
		exit;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo config::getName();?> | Dashboard</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<?php html::getNavigation();?>
		<div class="container">
			<div class="col-xs-12 col-sm-12 col-md-3">
				<div class="well profileCard center">
					<h4>Hello, <?php echo $GLOBALS['userTable']['username']; ?></h4>
					<img class="img-responsive" style="display:inline;" src="<?php echo context::getUserImage($GLOBALS['userTable']);?>">
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-9">
				<h4>Wall posts will appear here</h4>
				<p>We will work on this sooner or later</p>
			</div>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>