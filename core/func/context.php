<?php
	class context {
		public static function contains($str, array $arr) {
			foreach($arr as $a) {
				if (stripos($str,$a) !== false) return true;
			}
			return false;
		}
		
		public static function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
		{
			$str = '';
			$max = mb_strlen($keyspace, '8bit') - 1;
			for ($i = 0; $i < $length; ++$i) {
				$str .= $keyspace[rand(0, $max)];
			}
			return $str;
		}
		
		public static function getCurrentTime() {
			return date('Y-m-d H:i:s');
		}
		
		public static function secureString($str) {
			return user::filter(htmlentities($str, ENT_QUOTES, "UTF-8"));
		}
		
		public static function getUserSheetByID($userID) {
			$query = "SELECT * FROM users WHERE id = :id";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->bindParam(':id', $userID, PDO::PARAM_INT); 
			$stmt->execute(); 
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}
		
		public static function getUserSheetByIDForum($userID) {
			$query = "SELECT id, username, rank, imgTime FROM users WHERE id = :id";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->bindParam(':id', $userID, PDO::PARAM_INT); 
			$stmt->execute(); 
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}
		
		public static function jsonToSingle($json) {
			$jsonNew = substr($json, 1);
			$jsonNew = substr_replace($jsonNew, "", -1);
			return $jsonNew;
		}
		
		public static function getTimeSince($timeFrom) {
			$to_time = strtotime(context::getCurrentTime());
			$from_time = strtotime($timeFrom);
			return round(abs($to_time - $from_time) / 60,2);
		}
		
		public static function IDToUsername($userID) {
			$stmt = $GLOBALS['dbcon']->prepare('SELECT username FROM users WHERE id = :userId');
			$stmt->bindParam(':userId', $userID, PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			return $result['username'];
		}
		
		public static function getUserImage($userSheet) {
			if ($userSheet['imgTime'] == 0 || $userSheet['imgTime'] == null) {
				return 'https://api.xdiscuss.net/imageServer/user/def2.png?v=2';
			}else{
				return 'https://api.xdiscuss.net/imageServer/user/'.$userSheet['id'].'.png?time='.$userSheet['imgTime'];
			}
		}
		
		public static function getGroupImage($userID) {
			$query = "SELECT imgTime, id FROM users WHERE id = :id";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->bindParam(':id', $userID, PDO::PARAM_INT); 
			$stmt->execute(); 
			$userSheet = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($userSheet['imgTime'] == 0 || $userSheet['imgTime'] == null) {
				return 'https://api.xdiscuss.net/imageServer/user/def2.png';
			}else{
				return 'https://api.xdiscuss.net/imageServer/user/'.$userSheet['id'].'.png?time='.$userSheet['imgTime'];
			}
		}
		
		public static function getOnline($userSheet) {
			$from_time = strtotime($userSheet['lastSeen']);
			$to_time = strtotime(context::getCurrentTime());
			$timeSince = round(abs($to_time - $from_time) / 60,2);
			if ($timeSince > 5) {
				return '<font color="grey">&#x25CF; </font>';
			}else{
				return '<font color="green">&#x25CF; </font>';
			}
		}
		
		public static function humanTiming ($time) {
			$time = time()-$time;
			$time = 86400-$time;
			$time = ($time<1)? 1 : $time;
			$tokens = array (
				31536000 => 'year',
				2592000 => 'month',
				604800 => 'week',
				86400 => 'day',
				3600 => 'hour',
				60 => 'minute',
				1 => 'second'
			);
			foreach ($tokens as $unit => $text) {
			if ($time < $unit) continue;
				$numberOfUnits = floor($time / $unit);
				return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
			}
		}
		
		public static function humanTimingSince($time)
		{

			$time = time() - $time; // to get the time since that moment
			$time = ($time<1)? 1 : $time;
			$tokens = array (
				31536000 => 'year',
				2592000 => 'month',
				604800 => 'week',
				86400 => 'day',
				3600 => 'hour',
				60 => 'minute',
				1 => 'second'
			);

			foreach ($tokens as $unit => $text) {
				if ($time < $unit) continue;
				$numberOfUnits = floor($time / $unit);
				return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
			}

		}
		
		public static function getItemThumbnail($type, $id, $datafile, $fileHash) {
			if ($type == "hats") return 'https://api.xdiscuss.net/imageServer/hats/'.$datafile.'.png';
			if ($type == "heads") return 'https://api.xdiscuss.net/imageServer/heads/'.$datafile.'.png';
			if ($type == "faces") return '/data/assets/faces/thumbnail/'.$datafile.'.png';
			if ($type == "gear") return 'https://api.xdiscuss.net/imageServer/gear/'.$datafile.'.png';
			if ($type == "decals") return "/data/assets/uploads/".$fileHash;
			if ($type == "tshirts") return 'https://api.xdiscuss.net/imageServer/tshirts/'.$id.'.png';
			if ($type == "pants") return 'https://api.xdiscuss.net/imageServer/pants/'.$id.'.png';
			if ($type == "shirts") return 'https://api.xdiscuss.net/imageServer/shirts/'.$id.'.png';
		}
		
		public static function getItemThumbnailC($type, $id, $datafile, $fileHash, $time) {
			if ($type == "hats") return 'https://api.xdiscuss.net/imageServer/hats/'.$datafile.'.png?tick='.strtotime($time).'hat';
			if ($type == "heads") return 'https://api.xdiscuss.net/imageServer/heads/'.$datafile.'.png?tick='.strtotime($time);
			if ($type == "faces") return '/data/assets/faces/thumbnail/'.$datafile.'.png?tick='.strtotime($time);
			if ($type == "gear") return 'https://api.xdiscuss.net/imageServer/gear/'.$datafile.'.png?tick='.strtotime($time);
			if ($type == "decals") return "/data/assets/uploads/".$fileHash;
			if ($type == "tshirts") return 'https://api.xdiscuss.net/imageServer/tshirts/'.$id.'.png?tick='.strtotime($time);
			if ($type == "pants") return 'https://api.xdiscuss.net/imageServer/pants/'.$id.'.png?tick='.strtotime($time);
			if ($type == "shirts") return 'https://api.xdiscuss.net/imageServer/shirts/'.$id.'.png?tick='.strtotime($time);
		}
		
		public static function requestImage($ID, $type) {
			$query = "INSERT INTO renders (`render_id`, `type`) VALUES (:ID, :type);";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->bindParam(':ID', $ID, PDO::PARAM_INT); 
			$stmt->bindParam(':type', $type, PDO::PARAM_STR); 
			$stmt->execute(); 
		}
		
		public static function checkTopPoster($userID) {
			$query = "SELECT id, posts FROM users ORDER BY posts DESC LIMIT 15";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->execute(); 
			foreach ($stmt as $result) {
				if ($userID == $result['id']) {
					echo '<p style="color:#ff7701;margin:0 0 0px"><span class="fa fa-trophy"></span> <b>Top 15 Poster</b></p>';
				}
			}
		}
		
		public static function buildFriendButton($userID) {
			if ($GLOBALS['loggedIn'] == true) {
				echo '<script src="/core/func/js/friends/profile.js"></script>';
				$query = "SELECT * FROM `friends` WHERE `userId1` = :id AND `userId2` = :sid";
				$stmt = $GLOBALS['dbcon']->prepare($query);
				$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
				$stmt->bindParam(':sid', $userID, PDO::PARAM_INT);
				$stmt->execute();
				if ($stmt->rowCount() > 0) {
					$friend = true;
				}else{
					$friend = false;
				}
				
				$query = "SELECT * FROM `friendRequests` WHERE `senduid` = :id AND `recvuid` = :sid";
				$stmt = $GLOBALS['dbcon']->prepare($query);
				$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
				$stmt->bindParam(':sid', $userID, PDO::PARAM_INT);
				$stmt->execute();
				if ($stmt->rowCount() > 0) {
					$requestSent = true;
				}else{
					$requestSent = false;
				}
				
				$query = "SELECT * FROM `friendRequests` WHERE `senduid` = :id AND `recvuid` = :sid";
				$stmt = $GLOBALS['dbcon']->prepare($query);
				$stmt->bindParam(':id', $userID, PDO::PARAM_INT);
				$stmt->bindParam(':sid', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
				$stmt->execute();
				if ($stmt->rowCount() > 0) {
					$requestSenta = true;
				}else{
					$requestSenta = false;
				}
				
				if ($friend == false) {
					if ($requestSent == true or $requestSenta == true) {
						echo '<a class="btn btn-primary disabled friendBtn"><span class="fa fa-users"></span> Pending</a>';
					}else{
						echo '<a class="btn btn-primary friendBtn" onclick="sendRequest('.$userID.');"><span class="fa fa-users"></span> Add</a>';
					}
				}else{
					echo '<a class="btn btn-danger friendBtn" onclick="deleteFriendProfile('.$userID.');"><span class="fa fa-users"></span> Remove</a>';
				}
			}
		}
		
		public static function showBBcodes($text) {
		// BBcode array
			$find = array(
				'~\[b\](.*?)\[/b\]~s',
				'~\[i\](.*?)\[/i\]~s',
				'~\[u\](.*?)\[/u\]~s',
				'~\[quote\](.*?)\[/quote\]~s',
				'~\[red\](.*?)\[/red\]~s',
				'~\[blue\](.*?)\[/blue\]~s',
				'~\[s\](.*?)\[/s\]~s',
				'~\[code\](.*?)\[/code\]~s'
			);
			// HTML tags to replace BBcode
			$replace = array(
				'<b>$1</b>',
				'<i>$1</i>',
				'<span style="text-decoration:underline;">$1</span>',
				'<pre>$1</'.'pre>',
				'<span style="color:red">$1</'.'span>',
				'<span style="color:#158cba">$1</'.'span>',
				'<s>$1</'.'s>',
				'<code>$1</'.'code>'
			);
			// Replacing the BBcodes with corresponding HTML tags
			return preg_replace($find,$replace,$text);
		}
		
		public static function getImageRequestCount() {
			$query = "SELECT id FROM `renders`;";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->execute();
			return $stmt->rowCount();
		}
		
		public static function getUserCount() {
			$query = "SELECT COUNT(*) FROM users;";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->execute();
			$result = $stmt->fetchColumn(0);
			return $result;
		}
		
		public static function getRouterCount() {
			$query = "SELECT id FROM `serverRequests`;";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->execute();
			return $stmt->rowCount();
		}
		
		public static function sendDiscordMessage($message) {
			$url = "https://discordapp.com/api/webhooks/x/x";
			$dataArray = array('content' => $message, 
			'username' => "Graphictoria");
			
			$httpOptions = array(
				'http' => array (
					'header' => "Graphictoria-Server",
					'content-type' => 'multipart/form-data',
					'method' => "POST",
					'content' => http_build_query($dataArray)
				)
			);
			
			$context = stream_context_create($httpOptions);
			$result = @file_get_contents($url, false, $context);
		}
		
		public static function parseEmoticon($content) {
			$content = str_replace(":afro:",'<img height="25" width="25" src="/html/img/emoticons/afro.png">', $content);
			$content = str_replace(":afro-1:",'<img height="25" width="25" src="/html/img/emoticons/afro-1.png">', $content);
			$content = str_replace(":agent:",'<img height="25" width="25" src="/html/img/emoticons/agent.png">', $content);
			$content = str_replace(":alien:",'<img height="25" width="25" src="/html/img/emoticons/alien.png">', $content);
			$content = str_replace(":alien-1:",'<img height="25" width="25" src="/html/img/emoticons/alien-1.png">', $content);
			$content = str_replace(":angel:",'<img height="25" width="25" src="/html/img/emoticons/angel.png">', $content);
			$content = str_replace(":angry:",'<img height="25" width="25" src="/html/img/emoticons/angry.png">', $content);
			$content = str_replace(":angry-1:",'<img height="25" width="25" src="/html/img/emoticons/angry-1.png">', $content);
			$content = str_replace(":angry-2:",'<img height="25" width="25" src="/html/img/emoticons/angry-2.png">', $content);
			$content = str_replace(":angry-3:",'<img height="25" width="25" src="/html/img/emoticons/angry-3.png">', $content);
			$content = str_replace(":angry-4:",'<img height="25" width="25" src="/html/img/emoticons/angry-4.png">', $content);
			$content = str_replace(":angry-5:",'<img height="25" width="25" src="/html/img/emoticons/angry-5.png">', $content);
			$content = str_replace(":arguing:",'<img height="25" width="25" src="/html/img/emoticons/arguing.png">', $content);
			$content = str_replace(":arrogant:",'<img height="25" width="25" src="/html/img/emoticons/arrogant.png">', $content);
			$content = str_replace(":asian:",'<img height="25" width="25" src="/html/img/emoticons/asian.png">', $content);
			$content = str_replace(":asian-1:",'<img height="25" width="25" src="/html/img/emoticons/asian-1.png">', $content);
			$content = str_replace(":avatar:",'<img height="25" width="25" src="/html/img/emoticons/avatar.png">', $content);
			$content = str_replace(":skeleton:",'<img height="25" width="25" src="/html/img/emoticons/skeleton.png">', $content);
			$content = str_replace(":superhero:",'<img height="25" width="25" src="/html/img/emoticons/superhero-1.png">', $content);
			$content = str_replace(":vampire:",'<img height="25" width="25" src="/html/img/emoticons/vampire.png">', $content);
			$content = str_replace(":zombie:",'<img height="25" width="25" src="/html/img/emoticons/zombie.png">', $content);
			
			return $content;
		}
	}
?>
