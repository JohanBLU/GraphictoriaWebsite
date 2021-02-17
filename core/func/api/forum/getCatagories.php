<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	$stmt = $dbcon->prepare("SELECT name, id FROM catagories WHERE developer = 0");
	$stmt->execute();
	foreach($stmt as $result) {
		echo '<h3 style="font-size:18px">'.context::secureString($result['name']).'</h3><ul>';
		$stmt = $dbcon->prepare("SELECT id, name FROM forums WHERE catid = :id");
		$stmt->bindParam(':id', $result['id'], PDO::PARAM_INT);
		$stmt->execute();
		foreach($stmt as $result) {
			echo '<li><p><a onclick="loadForum('.$result['id'].')">'.context::secureString($result['name']).'</a></p></li>';
		}
		echo '</ul>';
	}
?>