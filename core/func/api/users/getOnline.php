<div class="panel panel-primary">
<div class="panel-heading" id="count"><span class="fa fa-user"></span> Users currently online</div>
<div class="panel-body">
<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	
	$currentTime = date('Y-m-d H:i:s');
	$to_time = strtotime($currentTime);
	$stmt = $GLOBALS['dbcon']->prepare("SELECT lastSeen, id, username, inGame, rank FROM users WHERE banned = 0 AND hideStatus = 0 ORDER BY id ASC;");
	$stmt->execute();
	$count = 0;
	foreach($stmt as $result) {
		$from_time = strtotime($result['lastSeen']);
		$timeSince =  round(abs($to_time - $from_time) / 60,2);
		if ($timeSince < 5){
			$count++;
			if ($result['inGame'] == 1) {
				if ($result['rank'] == 0) {
					echo '<a style="color:inherit;color:#e601ff;text-decoration: none;" href="/user/profile/'.$result['username'].'">'.$result['username'].' </a>';
				}else{
					echo '<b><a style="color:inherit;color:#e601ff;text-decoration: none;" href="/user/profile/'.$result['username'].'">'.$result['username'].' </a></b>';
				}
			}elseif ($result['rank'] > 0) {
				echo '<b><a style="color:inherit;text-decoration: none;" href="/user/profile/'.$result['username'].'">'.$result['username'].' </a></b>';
			}else{
				echo '<a style="color:inherit;text-decoration: none;" href="/user/profile/'.$result['username'].'">'.$result['username'].' </a>';
			}
		}
	}
	if ($count == 0) {
		echo '<font color="grey">There are no users online at this moment.</font>';
	}
	echo '<script>$("#count").html("<span class=\"fa fa-user\"></span> Users currently online ('.$count.')");</script>';
?>
</div>
</div>