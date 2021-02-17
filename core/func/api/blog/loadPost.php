<?php
	if (!isset($_GET['id'])) die("Invalid post ID");
	if (is_array($_GET['id'])) die("Invalid post ID");
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	$postId = $_GET['id'];
	$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM blogposts WHERE id = :id");
	$stmt->bindParam(':id', $postId, PDO::PARAM_INT);
	$stmt->execute();
	if ($stmt->rowCount() == 0) die("Post not found");
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	$userSheet = context::getUserSheetByID($result['poster_uid']);
	$content = context::secureString($result['content']);
	$content = preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","<iframe width=\"420\" height=\"315\" src=\"//www.youtube.com/embed/$1\" frameborder=\"0\" allowfullscreen></iframe>", $content);
	$content = preg_replace("/https?:\/\/[^ ]+?(?:\.jpg|\.png|\.gif)/",'<img class="img-responsive" src="$0">', $content);
	$content = context::showBBcodes($content);
	echo '<script>$("#title").html("'.context::secureString($result['title']).'<div style=\"float:right;color:#158cba;cursor:pointer\" onclick=\"loadMain();\">Back</div>");</script>
	<div class="well profileCard">
		<div class="row">
			<div class="col-xs-2 center">'.context::getOnline($userSheet).'<a href="/user/profile/'.$userSheet['username'].'">'.$userSheet['username'].'</a><br><img width="300 height="300" class="img-responsive" style="display:inline" src="'.context::getUserImage($userSheet).'"></div>
			<div class="col-xs-10">
				<p style="margin-top:5px;word-wrap:break-word;">'.nl2br($content).'</p>
			</div>
		</div>
	</div>';
?>