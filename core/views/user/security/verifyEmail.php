<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	if (!$GLOBALS['loggedIn'] or security::getUserEmailVerified() == true) {
		header("Location: /");
		exit;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo config::getName();?> | Verify Email</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<?php html::getNavigation();?>
		<script src="/core/func/js/auth/verifyEmail.js?v=7"></script>
		<div class="container">
			<div class="col-xs-12 col-sm-8 col-md-8 col-sm-offset-2 col-md-offset-2">
				<div id="vStatus"></div>
				<div class="well profileCard">
					<h4>Verify your email</h4>
					<p style="color:grey;margin-bottom:2px">An email has been sent to <b><?php echo $GLOBALS['userTable']['email'];?></b> which contains your verification code. Enter that code below.</p>
					<p style="color:grey;margin-bottom:2px">All emails will be automatically resent after 15 minutes in case you did not receive it.</p>
					<p style="color:grey;margin-bottom:2px">If you are unable to find your email, check your spam or junk folder.</p>
					<p style="color:grey;margin-bottom:2px">If you made a mistake entering your email address, <font color="#158cba" style="cursor:pointer" data-toggle="modal" data-target="#emailModal">click here</font></p>
					<input class="form-control" type="text" id="emailCode" placeholder="Enter verification code here"></input>
					<button id="verifyEmailCode" class="btn btn-primary fullWidth">Verify</button>
				</div>
				<p style="color:grey;text-align:center">If you need help, please contact <a href="mailto:support@xdiscuss.net">support@xdiscuss.net</a> and we will gladly help you</p>
			</div>
		</div>
		<?php html::buildFooter();?>
		<div class="modal" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="globalModal" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="gModalErrorContainer"></div>
					<div class="modal-body">
						<h3 style="margin-top:0px">Change Email</h3>
						<p>Did you enter your email incorrectly? No worries! You can change it here.</p>
						<b>Note</b>: You can change your email once an hour using this tool. Please enter it correctly to avoid waiting
						<input id="newEmail" type="email" class="form-control authField" placeholder="New Email"></input>
						<input id="currentPassword" type="password" class="form-control authField" placeholder="Enter your password"></input>
						<button id="confirmChange" class="btn btn-success">Change Email</button>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>