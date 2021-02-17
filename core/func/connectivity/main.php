<?php
	class connectivity {
		public static function createDatabaseConnection() {
			$db_username = "";
			$db_password = "";
			$db_database = "";
			$db_host = "";
			$db_port = 3306;
			try{
				$GLOBALS['dbcon'] = new PDO('mysql:host='.$db_host.';port='.$db_port.';dbname='.$db_database, $db_username, $db_password);
				$GLOBALS['dbcon']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$GLOBALS['dbcon']->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				$GLOBALS['dbcon']->setAttribute(PDO::ATTR_PERSISTENT , true);
			}catch (exception $e) {
				echo 'We could not connect to the database. Our potatoes are working on it.';
				exit;
			}
		}
		
		public static function closeDatabaseConnection() {
			if (isset($GLOBALS['dbcon'])) {
				$GLOBALS['dbcon'] = null;
			}
		}
	}
?>
