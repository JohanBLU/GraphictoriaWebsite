<?php
	if (strpos($_SERVER['SCRIPT_NAME'], "config.php")) {
		header("Location: /");
		exit;
	}
	
	$db_host = "127.0.0.1";
	$db_port = 3306;
	$db_name = "";
	$db_user = "";
	$db_passwd = "";
?>
