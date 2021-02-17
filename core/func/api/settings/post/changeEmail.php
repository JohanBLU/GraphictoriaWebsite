<?php
	if (isset($_POST['csrf']) and isset($_POST['newEmail']) and isset($_POST['currentPassword'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf'];
		$newEmail = $_POST['newEmail'];
		$currentPassword = $_POST['currentPassword'];
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false) {
			echo 'error';
			exit;
		}
		
		if (strlen($newEmail) == 0 or strlen($currentPassword) == 0) {
			echo 'missing-info';
			exit;
		}
		
		$auth_hash = crypt($currentPassword, $GLOBALS['userTable']['password_salt']);
		if ($auth_hash != $GLOBALS['userTable']['password_hash']) {
			echo 'wrong-password';
			exit;
		}
		
		$from_time = strtotime($GLOBALS['userTable']['emailcodeTime']);
		$to_time = strtotime(context::getCurrentTime());
		$timeSince = round(abs($to_time - $from_time) / 60,2);
		if ($timeSince < 5) die("rate-limit");
		
		// Email domain whitelist, to stop disposable and fake emails.
		$good_emails = array('@outlook', '@protonmail.com', '@xdiscuss.net', '@roblox.com', '@icloud.com', '@protonmail.ch', '@google.com',
							"@yahoo.com.br", "@hotmail.com.br", "@outlook.com.br", "@uol.com.br", "@bol.com.br", "@terra.com.br", "@ig.com.br", "@itelefonica.com.br", "@r7.com", "@zipmail.com.br", "@globo.com", "@globomail.com", "@oi.com.br",
							"@yahoo.com.mx", "@live.com.mx", "@hotmail.es", "@hotmail.com.mx", "@prodigy.net.mx",
							"@hotmail.com.ar", "@live.com.ar", "@yahoo.com.ar", "@fibertel.com.ar", "@speedy.com.ar", "@arnet.com.ar",
							"@hotmail.be", "@live.be", "@skynet.be", "@voo.be", "@tvcablenet.be", "@telenet.be",
							"@mail.ru", "@rambler.ru", "@yandex.ru", "@ya.ru", "@list.ru",
							"@gmx.de", "@hotmail.de", "@live.de", "@online.de", "@t-online.de", "@web.de", "@yahoo.de",
							"@hotmail.fr", "@live.fr", "@laposte.net", "@yahoo.fr", "@wanadoo.fr", "@orange.fr", "@gmx.fr", "@sfr.fr", "@neuf.fr", "@free.fr",
							"@sina.com", "@qq.com", "@naver.com", "@hanmail.net", "@daum.net", "@nate.com", "@yahoo.co.jp", "@yahoo.co.kr", "@yahoo.co.id", "@yahoo.co.in", "@yahoo.com.sg", "@yahoo.com.ph",
							"@btinternet.com", "@virginmedia.com", "@blueyonder.co.uk", "@freeserve.co.uk", "@live.co.uk",
							"@ntlworld.com", "@o2.co.uk", "@orange.net", "@sky.com", "@talktalk.co.uk", "@tiscali.co.uk",
							"@virgin.net", "@wanadoo.co.uk", "@bt.com", "@bellsouth.net", "@charter.net", "@cox.net", "@earthlink.net", "@juno.com",
							"@email.com", "@games.com", "@gmx.net", "@hush.com", "@hushmail.com", "@icloud.com", "@inbox.com",
							"@lavabit.com", "@love.com", "@outlook.com", "@pobox.com", "@rocketmail.com",
							"@safe-mail.net", "@wow.com", "@ygm.com", "@ymail.com", "@zoho.com", "@fastmail.fm",
							"@yandex.com","@iname.com", "@aol.com", "@att.net", "@comcast.net", "@facebook.com", "@gmail.com", "@gmx.com", "@googlemail.com",
							"@google.com", "@hotmail.com", "@hotmail.co.uk", "@mac.com", "@me.com", "@mail.com", "@msn.com",
							"@live.com", "@sbcglobal.net", "@verizon.net", "@yahoo.com", "@yahoo.co.uk"
		);
							
		if (!context::contains(strtolower($newEmail), $good_emails)) die("unknown-email");
		
		$stmt = $dbcon->prepare("SELECT email FROM users WHERE email = :email;");
		$stmt->bindParam(':email', $newEmail, PDO::PARAM_STR); 
		$stmt->execute();
		if ($stmt->rowCount() > 0) die("email-in-use");
		
		$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET email = :email WHERE id = :id;");
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':email', $newEmail, PDO::PARAM_STR);
		$stmt->execute();
		
		$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET lastUpload = NOW() WHERE id = :id;");
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		
		$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET emailverified = 0 WHERE id = :id;");
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		
		$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET emailcodeTime = NULL WHERE id = :id;");
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->execute();
		
		echo 'success';
	}else{
		echo 'error';
	}
?>
