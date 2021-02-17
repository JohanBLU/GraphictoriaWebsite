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
		<title><?php echo config::getName();?> | Create Group</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<?php html::getNavigation();?>
		<?php html::buildAds();?>
		<script src="/core/func/js/createGroup.js"></script>
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-6">
					<div id="gStatus"></div>
					<h4>Create a Group</h4>
					<p>In here you can create a group that anyone can join and be a part of.</p>
					<div class="well profileCard">
						<label>Group name</label>
						<input type="text" maxlength="32" class="form-control" id="groupName" placeholder="Group name"></input>
						<label>Group Description (You can change this later)</label>
						<textarea class="form-control" id="groupDescription" rows="5" placeholder="Describe your Group"></textarea>
						<br>
						<button id="createGroup" class="btn btn-primary fullWidth">Create new Group</button>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-6">
					<h4>Things to remember</h4>
					<ul>
						<li>Group Names can not be longer than 32 characters</li>
						<li>Group Names must be at least 5 characters</li>
						<li>Descriptions can not be longer than 256 characters</li>
						<li>Descriptions are optional</li>
						<li>Creating a Group will cost you 50 coins</li>
						<li>You can only be in 10 groups at a time</li>
					</ul>
				</div>
			</div>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>