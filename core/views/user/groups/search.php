<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo config::getName();?> | Groups</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<?php 
			html::getNavigation();
			$term = "";
			if (isset($_GET['term'])) {
				if (!is_array($_GET['term'])) {
					$term = context::secureString($_GET['term']);
				}
			}
		?>
		<?php html::buildAds();?>
		<script src="/core/func/js/searchGroup.js"></script>
		<div class="container">
			<div class="col-xs-12 col-sm-8 col-md-8 col-sm-offset-2 col-md-offset-2">
				<div class="well profileCard">
					<div class="form-group">
						<div class="input-group">
							<input type="text" id="searchValue_2" placeholder="Group name" class="form-control" value="<?php echo $term; ?>"></input>
							<span class="input-group-btn">
								<button id="doSearch_2" class="btn btn-default" type="button">Search</button>
							</span>
						</div>
					</div>
				</div>
				<div id="searchResults"></div>
			</div>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>