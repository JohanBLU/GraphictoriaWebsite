<?php
	if (isset($_POST['csrf']) and isset($_POST['aboutContent'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$csrf = $_POST['csrf'];
		$aboutContent = $_POST['aboutContent'];
		if ($csrf != $GLOBALS['csrf_token'] or $GLOBALS['loggedIn'] == false) die("error");
		if (strlen($aboutContent) > 256) die("error");
		
		// Apparently, we'll need filters here too since users can't just shut their mouths.
		$badwords = array("fucking", "gay", "rape", "incest", "beastiality", "cum", "maggot", "bullshit", "fuck", "penis",
						"dick", "vagina", "vag", "faggot", "fag", "nigger", "asshole", "shit", "bitch", "anal", "stfu",
						"cunt", "pussy", "hump", "meatspin", "redtube", "porn", "kys", "xvideos", "hentai", "gangbang", "milf",
						"n*", "nobelium", "whore", "wtf", "horny", "raping", "s3x", "boob", "nigga", "nlgga", "gt2008",
						"cock", "dicc", "idiot", "nibba", "nibber", "nude", "kesner", "brickopolis", "nobe", "diemauer");
						
		$badwords2 = array("sex", "porn");
		$contentCheck = preg_replace('!\s+!', ' ', $aboutContent);
		$contentCheck = strip_tags($contentCheck);
		$contentCheck = preg_replace("/&#?[a-z0-9]+;/i","", $contentCheck);
		$contentCheck = preg_replace('!\s+!', ' ', $contentCheck);
		$contentCheck = strtolower(preg_replace('|[[\/\!]*?[^\[\]]*?]|si', '', $contentCheck));
		$contentCheck = preg_replace('/\s+/', '', $contentCheck);
		
		if (context::contains($contentCheck, $badwords2)) die("filtered");
		
		// Check without special characters removed
		if (context::contains($contentCheck, $badwords)) die("filtered");
		
		if(!preg_match("/^[\w*?!\/@',#$%\"'_.=\[\]\^&*\(\)\r\n -]+$/", $aboutContent) == 1 && strlen($aboutContent) != 0) die("filtered");
		
		$query = "UPDATE `users` SET `about`=:about WHERE `id`=:id;";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':about', $aboutContent, PDO::PARAM_STR);
		$stmt->execute();
		echo 'success';
	}else{
		echo 'error';
	}
?>