<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	if ($GLOBALS['loggedIn']) {
		header("Location: /user/dashboard");
		exit;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo config::getName();?></title>
		<?php html::buildHead();?>
		<script src="/core/html/js/particles.min.js"></script>
		<script>
			particlesJS.load('particles-js', 'core/html/js/particles.json', function() {
			  console.log('particles.js loaded - callback');
			});
		</script>
		<style>
			#particles-js{
			  width: 100%;
			  height: 100%;
			  background-image: url('');
			  background-size: cover;
			  background-position: 50% 50%;
			  background-repeat: no-repeat;
			}
			canvas {
				position: absolute;
				left: 0;
				top: 0;
				z-index: -999
			}
		</style>
	</head>
	<body>
		<?php html::getNavigation();?>
		<script src="https://www.google.com/recaptcha/api.js"></script>
		<div class="container">
			<div id="particles-js">
				<div class="col-xs-12 col-sm-12 col-md-7">
					<h3 class="wtext">Welcome to <?php echo config::getName();?>!</h3>
					<p class="wtext">Forum, chat and play games with other players and make friends</p>
					<p class="wtext">Customize your character with items found in our catalog</p>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-5">
					<script src="/core/func/js/auth/register.js?v=20"></script>
					<h3 class="wtext">Create an account</h3>
					<p class="wtext">If you are new to <?php echo config::getName();?>, you can sign up by filling in the form below</p>
					<div id="rStatus"></div>
					<input class="form-control" type="text" id="rUsername" placeholder="Username"></input>
					<input class="form-control" type="email" id="rEmail" placeholder="E-Mail (will be verified)"></input>
					<input class="form-control" type="password" id="rPassword1" placeholder="Password"></input>
					<input class="form-control" type="password" id="rPassword2" placeholder="Password (again)"></input>
					<div class="g-recaptcha" data-sitekey=""></div>
					<button class="btn btn-primary fullWidth" id="rSubmit">Register</button>
				</div>
			</div>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>
