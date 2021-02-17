<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo config::getName();?> | Forum</title>
		<?php html::buildHead();?>
		<style>
			.adContainer {
				display: block;
			}
			
			@media screen and (max-width: 767px) {
				.adContainer {
					display: none;
				}
			}
		</style>
	</head>
	<body>
		<?php html::getNavigation();?>
		<div class="modal miniProfile" id="miniProfile" tabindex="-1" role="dialog" aria-labelledby="profileModal" aria-hidden="true">
			<div class="modal-dialog modal-sm" role="document">
				<div class="modal-content center">
					<h4 class="modal-title modalUsername" id="modalUsername"></h4>
					<div class="modal-body miniProfileContent" id="miniProfileContent">
						<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
					</div>
				</div>
			</div>
		</div>
		<script src="/core/func/js/forum.js?v=51"></script>
		<div class="container">
			<div class="col-xs-12 col-sm-12 col-md-2">
				<div id="catagories"></div>
				<div class="adContainer">
					<?php html::buildAds(); ?>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-10">
				<div id="posts"></div>
			</div>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>