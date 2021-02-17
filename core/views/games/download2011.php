<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	$msie = strpos($_SERVER["HTTP_USER_AGENT"], 'MSIE') ? true : false;
	$edge = strpos($_SERVER["HTTP_USER_AGENT"], 'Edge') ? true : false;
	if (!$msie && !$edge) {
		if ($GLOBALS['loggedIn'] == true) {
			header("Location: https://gtdownloadhandler:publicpasswordfordownload@api.xdiscuss.net/downloads/Graphictoria2011_v2_3.exe");
			exit;
		}else{
			header("Location: /error/404/");
			exit;
		}
	}
	if (!$GLOBALS['loggedIn']) {
		header("Location: /error/404/");
		exit;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo config::getName();?> | Download</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<?php html::getNavigation();?>
		<div class="container">
			<h3>Browser is not supported</h3>
			<p>Use another browser such as Chrome to download. This is because this browser does not support the way of downloading.</p>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>