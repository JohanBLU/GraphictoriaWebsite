<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo config::getName();?> | Blog</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<?php html::getNavigation();?>
		<script src="/core/func/js/blog.js?v=51"></script>
		<div class="container">
			<div class="row">
				<div class="col-xs-2" id="adContainer">
				</div>
				<div class="col-xs-8">
					<h4 id="title" style="display:inline">Graphictoria Blog</h4>
					<?php
						if ($GLOBALS['loggedIn'] == true && $GLOBALS['userTable']['rank'] == 1) echo '<h4 id="newPostBtn" style="margin-top:0px;display:inline;float:right;color:#158cba;cursor:pointer" onclick="loadNew();">New Post</h4>';
					?>
					<hr>
					<div id="postContainer">
						<div class="well profileCard" style="min-height:500px">
							<div class="center">
								<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-2" id="adContainer2">
				</div>
			</div>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>