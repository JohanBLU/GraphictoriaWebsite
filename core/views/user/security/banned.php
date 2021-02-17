<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	if (!$GLOBALS['loggedIn'] or $GLOBALS['userTable']['banned'] == 0) {
		header("Location: /");
		exit;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo config::getName();?> | Suspended</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<?php html::getNavigation();?>
		<script src="/core/func/js/account/suspended.js?v=3"></script>
		<div class="container">
			<div class="col-xs-12 col-sm-8 col-md-8 col-sm-offset-2 col-md-offset-2">
				<div id="sStatus"></div>
				<div class="well profileCard">
					<?php
						if ($GLOBALS['userTable']['bantype'] == 0 or $GLOBALS['userTable']['bantype'] == 5) {
							$type = "Account Deleted";
						}elseif ($GLOBALS['userTable']['bantype'] == 1) {
							$type = "Warning";
						}elseif ($GLOBALS['userTable']['bantype'] == 2) {
							$type = "Suspended for 1 day";
						}elseif ($GLOBALS['userTable']['bantype'] == 3) {
							$type = "Suspended for 1 week";
						}else{
							$type = "Suspended for 1 month";
						}
						
						echo '<h4>'.$type.'</h4>';
						if ($type == "Account Deleted") {
							$message = "You will not be able to re-activate your account";
						}elseif ($type == "Warning") {
							$message = "You will be able to activate your account now";
						}else{
							$message = "You will be able to re-activate your account once the suspension has been expired";
						}
						echo '<p>Suspended on : '.date('M j Y g:i A', strtotime($GLOBALS['userTable']['bantime'])).'</p>';
						echo '<p style="color:grey">'.$message.'</p>';
						echo '<p><b>Reason for suspension</b>: '.context::secureString($GLOBALS['userTable']['banreason']).'</p>';
						
						if ($type != "Account Deleted") {
							echo '<button class="btn btn-default" id="liftBan">Re-activate my account</button>';
						}
					?>
				</div>
			</div>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>