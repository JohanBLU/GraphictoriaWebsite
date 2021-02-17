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
		<title><?php echo config::getName();?> | Messages</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<?php html::getNavigation();?>
		<?php html::buildAds();?>
		<script src="/core/func/js/messages.js?v=2"></script>
		<div class="container">
			<div class="col-xs-12 col-sm-12 col-md-2">
				<h3>Filters</h3>
				<div id="filters">
					<ul>
						<li><p><a id="allMessages">All Messages</a></p></li>
						<li><p><a id="unreadMessages">Un-read Messages</a></p></li>
						<li><p><a id="readMessages">Read Messages</a></p></li>
						<li><p><a id="sentOnly">Sent Messages</a></p></li>
					</ul>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-10">
				<div id="mStatus"></div>
				<div id="messages">
				</div>
			</div>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>