<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo config::getName();?> | Online Users</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<?php 
			html::getNavigation();
		?>
		<script>
			$(document).ready(function() {
				console.log("Got online players");
				$("#onlineContainer").load("/core/func/api/users/getOnline.php");
				setInterval(function() {
					$("#onlineContainer").load("/core/func/api/users/getOnline.php");
				}, 5000);
			});
		</script>
		<div class="container">
			<div class="col-xs-12">
				<div id="onlineContainer">
					<div class="panel panel-primary">
						<div class="panel-heading" id="count"><span class="fa fa-user"></span> Users currently online</div>
						<div class="panel-body">
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>