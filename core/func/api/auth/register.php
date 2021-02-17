<?php
	if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['passwd1']) && isset($_POST['passwd2']) && isset($_POST['csrf']) && isset($_POST['captcha'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$username = $_POST['username'];
		$email = $_POST['email'];
		$password1 = $_POST['passwd1'];
		$password2 = $_POST['passwd2'];
		$csrf_token = $_POST['csrf'];
		$IP = auth::getIP();
		if ($csrf_token != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == true) {
			echo 'error';
			exit;
		}
		
		if (strlen($username) == 0 && strlen($email) == 0 && strlen($password1) == 0 && strlen($password2) == 0) {
			echo 'missing-info';
			exit;
		}
		
		$response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=&response=".$_POST['captcha']."&remoteip=".auth::getIP()), true);
		if ($response['success'] == false) {
			echo 'incorrect-captcha';
			exit;
		}
		
		
		$bad_words = array('nlgga', 'nigga', 'sex', 'raping', 'tits', 'wtf', 'vag', 'diemauer', 'brickopolis', '.com', '.cf', 'dicc', 'nude', 'kesner', 'nobe', 'idiot', 'dildo', 'cheeks', 'anal', 'boob', 'horny', 'tit', 'fucking', 'gay', 'rape', 'rapist', 'incest', 'beastiality', 'cum', 'maggot', 'bloxcity', 'bullshit', 'fuck', 'penis', 'dick', 'vagina', 'faggot', 'fag', 'nigger', 'asshole', 'shit', 'bitch', 'anal', 'stfu', 'cunt', 'pussy', 'hump', 'meatspin', 'redtube', 'porn', 'kys', 'xvideos', 'hentai', 'gangbang', 'milf', 'whore', 'cock', 'masturbate');
		$username_check = strtolower($username);
		if (context::contains($username_check, $bad_words)) {
			echo 'invalid-username';
			exit;
		}
		
		if (strlen($username) == 0) {
			echo 'no-username';
			exit;
		}
		
		if (strlen($username) < 3) {
			echo 'username-too-short';
			exit;
		}
		
		if (strlen($username) > 20) {
			echo 'username-too-long';
			exit;
		}
		
		if(!preg_match("/^[a-zA-Z0-9][\w\.]+[a-zA-Z0-9]$/", $username) == 1) {
			echo 'illegal-username';
			exit;
		}
		
		if (strlen($email) == 0) {
			echo 'no-email';
			exit;
		}
		
		if (strlen($email) > 128) {
			echo 'email-too-long';
			exit;
		}
		
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			echo 'illegal-email';
			exit;
		}
		
		$domain = substr($email, strpos($email, '@') + 1);
		if (checkdnsrr($domain) == false) die("illegal-email");
		
		// Email domain whitelist, to stop disposable and fake emails. Will only be checked at initial register.
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
							
		if (!context::contains(strtolower($email), $good_emails)) die("unknown-email");
		
		if (strlen($password1) == 0) {
			echo 'no-password';
			exit;
		}
		
		if (strlen($password2) == 0) {
			echo 'no-password';
			exit;
		}
		
		if ($password1 != $password2) {
			echo 'passwords-mismatch';
			exit;
		}
		
		if (strlen($password1) < 6) {
			echo 'password-too-short';
			exit;
		}
		
		if (strlen($password1) > 40) {
			echo 'password-too-long';
			exit;
		}
		
		$stmt = $dbcon->prepare("SELECT * FROM users WHERE email = :email;");
		$stmt->bindParam(':email', $email, PDO::PARAM_STR); 
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			echo 'email-already-used';
			exit;
		}
		
		$stmt = $dbcon->prepare("SELECT * FROM users WHERE username = :username;");
		$stmt->bindParam(':username', $username, PDO::PARAM_STR); 
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			echo 'username-already-used';
			exit;
		}
		
		$stmt = $GLOBALS['dbcon']->prepare("SELECT joinDate FROM users WHERE registerIP = :ip ORDER BY id DESC LIMIT 1;");
		$stmt->bindParam(':ip', $IP, PDO::PARAM_STR); 
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$currentTime = context::getCurrentTime();
			$to_time = strtotime($currentTime);
			$from_time = strtotime($result['joinDate']);
			$timeSince =  round(abs($to_time - $from_time) / 60,2);
			if ($timeSince < 1440) {
				echo 'rate-limit';
				exit;
			}
		}
		
		// Still here? Continue. Please use password_hash...
		$salt = '$2a$07$'.uniqid(mt_rand(), true).'$';
		$hash = crypt($password1, $salt);
		$stmt = $dbcon->prepare("INSERT INTO users (`username`, `password_hash`, `password_salt`, `email`, `registerIP`, `passwordVersion`) VALUES (:user, :hash, :salt, :email, :ip, 2);");
		$stmt->bindParam(':ip', $IP, PDO::PARAM_STR);
		$stmt->bindParam(':user', $username, PDO::PARAM_STR);
		$stmt->bindParam(':hash', $hash, PDO::PARAM_STR);
		$stmt->bindParam(':salt', $salt, PDO::PARAM_STR);
		$stmt->bindParam(':email', $email, PDO::PARAM_STR);
		$stmt->execute();
		
		context::sendDiscordMessage("**New user registered!** | ".$username);
		
		echo 'success';
	}else{
		echo 'error';
		exit;
	}
?>
