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
		<title><?php echo config::getName();?> | New Server</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<?php html::getNavigation();?>
		<script src="/core/func/js/addServer.js?v=<?php echo time();?>"></script>
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-2">
					<?php html::buildAds();?>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-10">
					<div id="aStatus"></div>
					<div class="profileCardContainerHead"></div>
					<div class="profileCardContainer"></div>
					<div class="well profileCard">
						<h4>New Server</h4>
						<label>Server name</label>
						<input type="text" maxlength="32" id="serverName" class="form-control" placeholder="Server Name"></input>
						<label>Server Description</label>
						<textarea rows="5" maxlength="128" id="serverDescription" class="form-control" placeholder="Describe your server"></textarea>
						<label>Server Type</label>
						<select name="sType" id="serverType" class="form-control">
							<option value="0">Self-hosted (You need to port forward in order to have people join)</option>
							<option value="1">Dedicated (This server will remain online until nobody plays on it)</option>
						</select>
						<div id="selfHostOptions">
							<label>Server IP</label>
							<input type="text" maxlength="64" id="serverIP" class="form-control" placeholder="Server IP address" value="<?php echo auth::getIP();?>"></input>
							<label>Server Port</label>
							<input type="numbers" maxlength="5" id="serverPort" class="form-control" placeholder="Server Port"></input>
							<label>Server Privacy</label>
							<select name="privacy" id="privacyType" class="form-control">
								<option value="1">Public (Everyone can join your server)</option>
								<option value="0">Private (You will get a key you can share)</option>
							</select>
							<label>Server Version</label>
							<select name="version" id="versionType" class="form-control">
								<option value="0">2009</option>
								<option value="1">2008</option>
								<option value="2">2011</option>
							</select>
							<button class="btn btn-success fullWidth" id="addServer01">Add Self Hosted Server</button>
						</div>
						<div id="dedicatedOptions" style="display:none">
							<label>Server Version</label>
							<select name="version" id="versionTypeDedi" class="form-control">
								<option value="0">2009</option>
								<option value="1">2008</option>
								<option value="2">2011</option>
							</select>
							<label>Server Privacy</label>
							<select name="privacy" id="privacyTypeDedi" class="form-control">
								<option value="1">Public (Everyone can join your server)</option>
								<option value="0">Private (You will get a key you can share)</option>
							</select>
							<label>Place file</label>
							<input id="placeFile" type="file"></input><br>
							<label>Choose a default place - If you do not have a place file, you can choose one of those generic places</label>
							<div class="placeSelector">
								<img id="place0" class="place" style="cursor:pointer" height="150px" width="150px" src="/html/img/places/baseplate.png">
							</div>
							<br>
							<button class="btn btn-success fullWidth" id="addServer02">Add Dedicated Server</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>