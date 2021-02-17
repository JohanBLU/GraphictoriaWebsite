<?php
	if (isset($_GET['id'])) {
		$username = $_GET['id'];
		if (is_array($username)) {
			exit;
		}
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$stmt = $GLOBALS['dbcon']->prepare('SELECT id, imgTime, username, banned, lastSeen, rank, posts FROM users WHERE username = :username;');
		$stmt->bindParam(':username', $username, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($stmt->rowCount() == 0 or $result['banned'] == 1) {
			echo 'User not found or suspended';
			echo '<script>$(".modalUsername").html("")</script>';
			exit;
		}
		echo '<script>$(".modalUsername").html(\''.context::getOnline($result).' '.context::secureString($result['username']).'\')</script>';
		echo '<img width="150 height="150" class="img-responsive" style="display:inline" src="'.context::getUserImage($result).'">';
		if ($result['rank'] == 1) {
			echo '<p style="color:#158cba;margin:0 0 0px"><span class="fa fa-bookmark"></span> <b>Administrator</b></p>';
		}
		if ($result['rank'] == 2) {
			echo '<p style="color:#28b62c;margin:0 0 0px"><span class="fa fa-gavel"></span> <b>Moderator</b></p>';
		}
		echo '<p><b>Posts</b>: '.$result['posts'].'</p>';
		echo '<div class="btn-group btn-group-justified"><a class="btn" href="/user/profile/'.context::secureString($result['username']).'">Full Profile</a>';
		if ($GLOBALS['loggedIn'] == true && $GLOBALS['userTable']['username'] != $result['username']) {
			echo '<a class="btn" href="/user/messages+'.context::secureString($result['username']).'">Send Message</a>';
		}
		echo '</div>';
	}else{
		echo 'An error occurred';
	}
?>
