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
		<title><?php echo config::getName();?> | Settings</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<?php html::getNavigation();?>
		<?php html::buildAds();?>
		<script src="/core/func/js/settings.js?v=10"></script>
		<div class="container">
			<h3>Settings</h3>
			<div id="sStatus"></div>
			<ul class="nav nav-tabs">
				<li class="active"><a href="#account" data-toggle="tab">Account</a></li>
				<li><a href="#security" data-toggle="tab">Security</a></li>
				<li><a href="#appearance" data-toggle="tab">Appearance</a></li>
			</ul>
			<div id="settingsContent" class="tab-content">
				<div class="tab-pane active fade in" id="account">
					<div class="well profileCard">
						<h4 style="display:inline">About Me</h4> <p style="display:inline;color:grey">You can set your text here which will appear on your profile.</p>
						<textarea id="aboutValue" rows="10" maxlength="256" class="form-control" placeholder="Tell us about yourself!"><?php echo context::secureString($GLOBALS['userTable']['about']);?></textarea>
						<button id="updateAbout" class="btn btn-info fullWidth">Save</button>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-6">
							<div id="cPassStatus"></div>
							<div class="well profileCard">
								<h4 style="display:inline">Change Password</h4> <p style="display:inline;color:grey">You can change your password here</p>
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-6">
										<input type="password" id="nPassword1" placeholder="New Password" class="form-control"></input>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-6">
										<input type="password" id="nPassword2" placeholder="Confirm new Password" class="form-control"></input>
									</div>
								</div>
								<input type="password" id="curPassword" placeholder="Current Password" class="form-control"></input>
								<button id="changePassword" class="btn btn-info fullWidth">Change Password</button>
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-6">
							<div id="cEmailStatus"></div>
							<div class="well profileCard">
								<h4 style="display:inline">Change E-Mail</h4> <p style="display:inline;color:grey">You can change your e-mail here</p>
								<input id="email" type="email" placeholder="New E-Mail" class="form-control"></input>
								<input id="password" type="password" placeholder="Current Password" class="form-control"></input>
								<button id="changeEmail" class="btn btn-info fullWidth">Change E-Mail</button>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade in" id="security">
					<div class="well profileCard">
						<h4 style="display:inline">Two Step Authentication</h4> <p style="display:inline;color:grey">This feature requires you to enter a secondary code in order to login.</p>
						<div id="twocontainer">
							<?php
								include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/api/settings/get/twoStep.php';
							?>
						</div>
					</div>
				</div>
				<div class="tab-pane fade in" id="appearance">
					<div class="well profileCard">
						<h4 style="display:inline">Appearance</h4> <p style="display:inline;color:grey">How the website looks</p>
						<br>
						<button id="enableRegular" class="btn btn-primary">Regular theme</button>
						<button id="enableDark" class="btn btn-primary">Dark theme</button>
					</div>
				</div>
			</div>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>