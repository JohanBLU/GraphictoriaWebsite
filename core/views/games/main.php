<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo config::getName();?> | Games</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<?php html::getNavigation();?>
		<div class="modal viewGame" id="viewGame" tabindex="-1" role="dialog" aria-labelledby="viewGameModal" aria-hidden="true">
			<div class="modal-dialog modal-xs" role="document">
				<div class="modal-content center">
					<h4 class="modal-title gameTitle"></h4>
					<div class="modal-body gameContent" style="min-height:350px;padding:5px">
						<div class="center"><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i></div>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="col-xs-12 col-sm-12 col-md-2">
				<?php html::buildAds();?>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-8">
				<script src="/core/func/js/gamesPage.js?v=40"></script>
				<div class="alert" style="background-color:#324452;border-radius:0px;margin-bottom:0px;color:white">Looking to host a server? You can add a server <a href="/games/new">here</a>!</div>
				<div id="addKeyResult"></div>
				<div class="alert" style="background-color:#212121;margin-bottom:0px;border-radius:0px;padding:5px;">
					<span class="fa fa-key"></span> <span style="color:white;">Enter key directly : </span><input id="serverKey" type="text" class="form-control" placeholder="Server key"></input>
					<button style="width:100%;" id="addServer" class="btn btn-success"><span class="fa fa-plus"></span> Add Private Server</button>
				</div>
				<div class="btn-group btn-group-justified" style="margin-bottom:0px;">
					<a class="btn btn-default" id="all">All</a>
					<a class="btn btn-default" id="v1">2008</a>
					<a class="btn btn-default" id="v0">2009</a>
					<a class="btn btn-default disabled" id="v3">2010</a>
					<a class="btn btn-default" id="v2">2011</a>
				</div>
				<div class="btn-group btn-group-justified" style="margin-bottom:23px;">
					<a href="#" class="btn btn-default" id="showPublic" style="-webkit-box-shadow:none;box-shadow:none;">View Public Servers</a>
					<a href="#" class="btn btn-default" id="showMy" style="-webkit-box-shadow:none;box-shadow:none;">Private Servers</a>
					<a href="#" class="btn btn-default" id="showMyS" style="-webkit-box-shadow:none;box-shadow:none;">My Servers</a>
					<?php
						if ($GLOBALS['loggedIn']) {
							echo '<a href="/core/views/games/download.php" class="btn btn-default d09" style="-webkit-box-shadow:none;box-shadow:none;display:none">Download 2009</a>';
							echo '<a href="/core/views/games/download2008.php" class="btn btn-default d08" style="-webkit-box-shadow:none;box-shadow:none;display:none">Download 2008</a>';
							echo '<a href="/core/views/games/download2011.php" class="btn btn-default d11" style="-webkit-box-shadow:none;box-shadow:none;display:none">Download 2011</a>';
						}
					?>
				</div>
				<div id="result">
					<div class="center"><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i></div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-2">
				<?php html::buildAds();?>
			</div>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>