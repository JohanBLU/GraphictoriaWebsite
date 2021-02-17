<style>
	.responsiveforum {
		display: block;
	}
	
	@media screen and (max-width: 767px) {
		.responsiveforum {
			text-align: center;
		}
	}
</style>
<?php
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		if (is_array($id)) {
			echo 'Something went wrong.';
			exit;
		}
		if (isset($_GET['page'])) {
			$page = $_GET['page'];
			if (is_numeric($page) == false) {
				exit;
			}
		}else{
			$page = 0;
		}
		if (is_array($page)) {
			echo 'Something went wrong.';
			exit;
		}
		$offset = $page*15;
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		if ($page == 0) {
			$stmt = $GLOBALS['dbcon']->prepare("SELECT id, author_uid, postTime, lastActivity, views, replies, title, content, forumId, locked, pinned FROM topics WHERE id = :id AND developer = 0");
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			if ($stmt->rowCount() == 0) {
				echo 'Topic not found';
				exit;
			}
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$id = $result['id'];
			$stmtr = $GLOBALS['dbcon']->prepare("SELECT id FROM `read` WHERE `userId` = :id AND `postId` = :pid;");
			$stmtr->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
			$stmtr->bindParam(':pid', $id, PDO::PARAM_INT);
			$stmtr->execute();
			$resultread = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($stmtr->rowCount() == 0) {
				$read = false;
			}else{
				$read = true;
			}
			
			if ($read == false and $loggedIn == true) {
				$query = "INSERT INTO `read` (`userId`, `postId`) VALUES (:userId, :postId);";
				$stmt = $GLOBALS['dbcon']->prepare($query);
				$stmt->bindParam(':postId', $id, PDO::PARAM_INT);
				$stmt->bindParam(':userId', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
				$stmt->execute();
				
				$stmt = $GLOBALS['dbcon']->prepare("UPDATE topics SET views = views + 1 WHERE id = :id");
				$stmt->bindParam(':id', $id, PDO::PARAM_INT);
				$stmt->execute();
			}
			
			echo '<div class="nav navbar-nav navbar-right" style="margin-right:15px;">';
			echo '<div id="pStatus"><b>Replies</b>: '.$result['replies'].'</div>';
			echo '</div>';
			echo '<h3 style="word-wrap:break-word;">'.context::secureString($result['title']).'</h3>';
			echo '<div class="nav navbar-nav navbar-right" style="margin-right:15px;">';
			if ($GLOBALS['loggedIn']) {
				if ($GLOBALS['userTable']['rank'] > 0) {
					echo '<button class="btn btn-danger" style="margin:-10px -15px 0px;" id="deletePost" onclick="deletePost('.$result['id'].', '.$result['forumId'].');"><span class="fa fa-trash-o"></span> Delete Post</button>';
					if ($result['locked'] == 1) {
						echo '<button class="btn btn-primary" style="margin:-10px 17px 0px;" id="unlockPost" onclick="unlockPost('.$result['id'].');"><span class="fa fa-unlock-alt"></span> Unlock Post</button>';
					}else{
						echo '<button class="btn btn-primary" style="margin:-10px 17px 0px;" id="lockPost" onclick="lockPost('.$result['id'].');"><span class="fa fa-lock"></span> Lock Post</button>';
					}
				}
				if ($GLOBALS['userTable']['rank'] == 1) {
					if ($result['pinned'] == 1) {
						echo '<button class="btn btn-primary" style="margin:-10px 17px 0px;" id="unpinPost" onclick="unpinPost('.$result['id'].');"><span class="fa fa-unlock-alt"></span> Unpin Post</button>';
					}else{
						echo '<button class="btn btn-primary" style="margin:-10px 17px 0px;" id="pinPost" onclick="pinPost('.$result['id'].');"><span class="fa fa-unlock-alt"></span> Pin Post</button>';
					}
				}
				if ($result['locked'] == 0) {
					echo '<button class="btn btn-primary" style="margin:-10px -15px 0px;" onclick="newReply('.$result['id'].');"><span class="fa fa-reply"></span> New Reply</button>';
				}else{
					if ($GLOBALS['userTable']['rank'] > 0) {
						echo '<button class="btn btn-primary" style="margin:-10px -15px 0px;" onclick="newReply('.$result['id'].');"><span class="fa fa-reply"></span> New Reply</button>';
					}else{
						echo '<button class="btn btn-primary disabled" style="margin:-10px -15px 0px;"><span class="fa fa-reply"></span> New Reply</button>';
					}
				}
			}
			echo '</div>';
			$userSheet = context::getUserSheetByID($result['author_uid']);
			if ($userSheet['rank'] == 0) {
				$usern = $userSheet['username'];
			}elseif ($userSheet['rank'] == 1) {
				$usern = '<b style="color:#158cba">'.$userSheet['username'].'</b>';
			}elseif ($userSheet['rank'] == 2) {
				$usern = '<b style="color:#28b62c">'.$userSheet['username'].'</b>';
			}
			echo '<p>Started by <a onclick="loadMiniProfile(\''.$userSheet['username'].'\');">'.$usern.'</a></p>';
			echo '<div class="list-group-item" style="border:none;border-bottom:2px solid #eeeeee"><div class="row"><div class="col-xs-12 col-sm-12 col-md-2 responsiveforum">
			<div class="center">'.context::getOnline($userSheet).'<a onclick="loadMiniProfile(\''.$userSheet['username'].'\');">'.$usern.'</a></div>
			<a onclick="loadMiniProfile(\''.$userSheet['username'].'\');"><img height="150" width="150" class="img-responsive" style="display:inline" src="'.context::getUserImage($userSheet).'"></a><br>';
			if ($userSheet['rank'] == 1) {
				echo '<p style="color:#158cba;margin:0 0 0px"><span class="fa fa-bookmark"></span> <b>Administrator</b></p>';
			}
			if ($userSheet['rank'] == 2) {
				echo '<p style="color:#28b62c;margin:0 0 0px"><span class="fa fa-gavel"></span> <b>Moderator</b></p>';
			}
			context::checkTopPoster($userSheet['id']);
			echo '<b>Joined: </b>'.date('M j Y', strtotime($userSheet['joinDate'])).'<br>
				<b>Posts: </b>'.$userSheet['posts'].'
				</div>';
			$content = strip_tags($result['content']);
			$content = context::secureString($content);
			$content = preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","<iframe width=\"420\" height=\"315\" src=\"//www.youtube.com/embed/$1\" frameborder=\"0\" allowfullscreen></iframe>", $content);
			if ($userSheet['rank'] > 0) {
				$content = preg_replace("/https?:\/\/[^ ]+?(?:\.jpg|\.png|\.gif)/",'<img class="img-responsive" src="$0">', $content);
			}
			$content = context::showBBcodes($content);
			$content = context::parseEmoticon($content);
			echo '<div class="col-xs-12 col-sm-12 col-md-10">
			<b><span class="fa fa-clock-o"></span> Posted on: </b>'.date('M j Y g:i A', strtotime($result['postTime'])).'<br>
			<span style="word-wrap:break-word;">'.nl2br($content).'</span>
			</div></div></div>';
		}else{
			$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM topics WHERE id = :id");
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			if ($stmt->rowCount() == 0) {
				echo 'Topic not found!';
				exit;
			}
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$id = $result['id'];
		}
		
		$stmt = $dbcon->prepare("SELECT author_uid, content, post_time FROM replies WHERE postId = :id ORDER BY id DESC LIMIT 16 OFFSET :offset;");
		$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		$count = 0;
		foreach($stmt as $result) {
			$count++;
			if ($count < 16) {
				$userSheet = context::getUserSheetByID($result['author_uid']);
				if ($userSheet['rank'] == 0) {
					$usern = $userSheet['username'];
				}elseif ($userSheet['rank'] == 1) {
					$usern = '<b style="color:#158cba">'.$userSheet['username'].'</b>';
				}elseif ($userSheet['rank'] == 2) {
					$usern = '<b style="color:#28b62c">'.$userSheet['username'].'</b>';
				}
				echo '<div class="list-group-item" style="border:none;border-bottom:2px solid #eeeeee"><div class="row"><div class="col-xs-12 col-sm-12 col-md-2 responsiveforum">
				<div class="center">'.context::getOnline($userSheet).'<a onclick="loadMiniProfile(\''.$userSheet['username'].'\');">'.$usern.'</a></div>
				<a onclick="loadMiniProfile(\''.$userSheet['username'].'\');"><img width="150 height="150" class="img-responsive" style="display:inline" src="'.context::getUserImage($userSheet).'"></a><br>';
				if ($userSheet['rank'] == 1) {
					echo '<p style="color:#158cba;margin:0 0 0px"><span class="fa fa-bookmark"></span> <b>Administrator</b></p>';
				}
				if ($userSheet['rank'] == 2) {
					echo '<p style="color:#28b62c;margin:0 0 0px"><span class="fa fa-gavel"></span> <b>Moderator</b></p>';
				}
				context::checkTopPoster($userSheet['id']);
				echo '<b>Joined: </b>'.date('M j Y', strtotime($userSheet['joinDate'])).'<br>
				<b>Posts: </b>'.$userSheet['posts'].'
				</div>';
				$content = strip_tags($result['content']);
				$content = context::secureString($content);
				$content = preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","<iframe width=\"420\" height=\"315\" src=\"//www.youtube.com/embed/$1\" frameborder=\"0\" allowfullscreen></iframe>", $content);
				if ($userSheet['rank'] > 0) {
					$content = preg_replace("/https?:\/\/[^ ]+?(?:\.jpg|\.png|\.gif)/",'<img class="img-responsive" src="$0">', $content);
				}
				$content = context::showBBcodes($content);
				$content = context::parseEmoticon($content);
				echo '<div class="col-xs-12 col-sm-12 col-md-10">
				<b><span class="fa fa-clock-o"></span> Posted on: </b>'.date('M j Y g:i A', strtotime($result['post_time'])).'<br>
				<span style="word-wrap:break-word;">'.nl2br($content).'</span>
				</div></div></div>';
			}
		}
		if ($count > 15) {
			echo '<button class="btn btn-primary fullWidth loadMore" onclick="loadMore(page, '.$id.')">Load more replies</button><script>page++;</script>';
		}
	}else{
		echo 'An error occurred';
	}
?>