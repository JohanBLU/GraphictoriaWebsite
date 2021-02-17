<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	if (!$GLOBALS['loggedIn']) {
		header("Location: /");
		exit;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo config::getName();?> | Character</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<?php html::getNavigation();?>
		<div class="adContainer" style="max-height:100px;height:100px">
			<?php html::buildAds();?>
		</div>
		<div class="container" style="margin-top:5px">
			<div class="row">
				<div class="col-md-4 col-sm-12 col-xs-12">
					<div class="well profileCard">
						<h4>Character</h4>
						<div class="center">
							<script>
								$(document).ready(function() {
									startGeneration(<?php echo $GLOBALS['userTable']['id'];?>);
								});
							</script>
							<img id="character" class="img-responsive" style="display:inline;" src="<?php echo context::getUserImage($GLOBALS['userTable']);?>">
							<a class="btn btn-default fullWidth" id="regen">Regenerate</a>
							<div class="btn-group btn-group-justified">
								<a class="btn btn-default" id="normal">Normal</a>
								<a class="btn btn-default" id="walking">Walking</a>
								<a class="btn btn-default" id="sitting">Sitting</a>
								<a class="btn btn-default" id="overlord">Overlord</a>
							</div>
							<div id="poseStatus"></div>
						</div>
					</div>
					<div class="well profileCard">
						<h4>Colors</h4>
						<?php
							include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/character/colors.php';
						?>
					</div>
				</div>
				<div class="col-md-8 col-sm-12 col-xs-12">
					<script src="/core/func/js/character.js?v=5"></script>
					<div class="well profileCard">
						<h4>Inventory</h4>
						<div class="btn-group btn-group-justified">
							<a class="btn" id="showHats">Hats</a>
							<a class="btn" id="showHeads">Heads</a>
							<a class="btn" id="showFaces">Faces</a>
							<a class="btn" id="showTshirts">T-Shirts</a>
							<a class="btn" id="showShirts">Shirts</a>
							<a class="btn" id="showPants">Pants</a>
							<a class="btn" id="showGear">Gear</a>
						</div>
						<div id="inventoryItems" class="row center">
						</div>
					</div>
					<div class="well profileCard">
						<h4>Wearing</h4>
						<div id="wearing" class="row center">
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>