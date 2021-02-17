<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	if ($GLOBALS['loggedIn']) {
		header("Location: /");
		exit;
	}
	if (isset($_GET['key']) and isset($_GET['userid'])) {
		$userID = $_GET['userid'];
		$key = $_GET['key'];
		if (is_array($userID) or is_array($key)) {
			header("Location: /");
			exit;
		}
	}else{
		header("Location: /");
		exit;
	}
	
	$query = "SELECT * FROM passwordresets WHERE userid = :uid AND `key` = :key";
	$stmt = $GLOBALS['dbcon']->prepare($query);
	$stmt->bindParam(':uid', $userID, PDO::PARAM_INT);
	$stmt->bindParam(':key', $key, PDO::PARAM_STR); 
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($stmt->rowCount() == 0 or $result['used'] == 1) {
		header("Location: /");
		exit;
	}
	
	$currentTime = date('Y-m-d H:i:s');
	$to_time = strtotime($currentTime);
	$from_time = strtotime($result['date']);
	$timeSince =  round(abs($to_time - $from_time) / 60,2);
	if ($timeSince > 5) {
		header("Location: /");
		exit;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo config::getName();?> | Reset Password</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<?php html::getNavigation();?>
		<div class="container">
			<script src="/core/func/js/auth/resetPassword.js"></script>
			<div class="col-xs-12 col-sm-8 col-md-8 col-sm-offset-2 col-md-offset-2">
				<div id="rStatus"></div>
				<div class="well profileCard">
					<h4>Reset Password</h4>
					<p style="color:grey">If you have lost your password, you can reset it here</p>
					<input class="form-control" type="password" placeholder="New Password" id="password1"></input>
					<input class="form-control" type="password" placeholder="Repeat New Password" id="password2"></input>
					<button id="changePassword" onclick="resetPassword('<?php echo $result['key'];?>', <?php echo $result['userId'];?>);" class="btn btn-primary fullWidth">Change Password</button>
				</div>
			</div>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>