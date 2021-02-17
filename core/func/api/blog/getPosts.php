<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM blogposts ORDER BY id DESC");
	$stmt->execute();
	foreach($stmt as $result) {
		$userSheet = context::getUserSheetByID($result['poster_uid']);
		echo '<div class="well profileCard">
			<div class="row">
				<div class="col-xs-1"><div style="position: relative;border:solid 1px #158cba;height:50px;width:50px;height:50px;border-radius:50%;overflow: hidden;" class="img-circle"><img style="position: absolute;clip: rect(0px, 75px, 50px, 0);left:-18px" src="'.context::getUserImage($userSheet).'" height="100"></div></div>
				<div class="col-xs-11">
					<h4 onclick="loadPost('.$result['id'].');" style="margin-bottom:0px;color:#158cba;cursor:pointer">'.context::secureString($result['title']).'</h4>
					<p>Posted by <a href="/user/profile/'.$userSheet['username'].'">'.$userSheet['username'].'</a>, '.context::humanTimingSince(strtotime($result['date'])).' ago</p>
				</div>
			</div>
		</div>';
	}
?>