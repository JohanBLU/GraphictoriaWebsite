<?php
	// This file contains all configuration.
	class config {
		public static function getName() {
			return "GWebsite";
		}
		
		public static function getDatabaseConfiguration() {
			include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/config/db.php';
		}
	}
?>
