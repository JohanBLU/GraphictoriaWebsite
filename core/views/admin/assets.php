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
		<title><?php echo config::getName();?> | Asset Approval</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<?php html::getNavigation();?>
		<script src="/core/func/js/admin/assetApproval.js?v=2"></script>
		<div class="container">
			<h4>Asset Approval</h4>
			<b>Things to not approve</b>
			<ul>
				<li>Pornographic content such as naked human beings.</li>
				<li>Photos of staff members or members. Only allow known people such as Bill Gates.</li>
				<li>Deny all copyrighted content, such as logos, etc.</li>
				<li>Punish uploaders if they break obvious rules.</li>
			</ul>
			<div id="assetContainer">
				<div class="center">
					<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
				</div>
			</div>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>