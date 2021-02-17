<?php
	// This file contains all code for authentication such as getting an IP.
	class auth {
		public static function getIP() {
			if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
				$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
			}
			return $_SERVER['REMOTE_ADDR'];
		}
	}
?>