<?php
	// Ping and maintenance checker
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	$maintenanceOn = false;
	if ($maintenanceOn == false) {
		echo 'no-maintenance';
	}else{
		echo 'Graphictoria is under maintenance.';
	}
?>
