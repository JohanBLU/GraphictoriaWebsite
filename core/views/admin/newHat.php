<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	if (!$GLOBALS['loggedIn']) {
		header("Location: /");
		exit;
	}
	if ($GLOBALS['userTable']['rank'] != 1 && $GLOBALS['userTable']['hatuploader'] == 0) {
		header("Location: /");
		exit;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo config::getName();?> | New Hat</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<?php html::getNavigation();?>
		<script src="/core/func/js/admin/uhat.js?tick=<?php echo time();?>"></script>
		<div class="container">
			<div class="row">
				<div class="col-xs-2">
					<h4>Before you upload</h4>
					<p>Make sure that the mesh type is correctly converted</p>
					<p>Assure that the heights of the "Handle" part is correct</p>
					<p>Make sure that the XML file is correctly formatted</p>
					<p>If something goes wrong, please contact an administrator to solve this</p>
				</div>
				<div class="col-xs-8">
					<div id="cStatus"></div>
					<h4>New Hat</h4>
					<p>This tool allows you to upload a new hat to the website. Hats are structured with a texture, mesh file and a model file (which is the XML file)</p>
					<p><b>Note</b> : The XML file must be tested before upload, you can not change the XML file after upload. However, this may change in the future.</p>
					<p><b>Note</b> : Do NOT upload R15 or future XML files, they will <b>NOT</b> work!</p>
					<p><b>Note</b> : Abuse of this system WILL result in a revoke of all rights, and a demotion if you are staff</p>
					<input class="form-control" id="hatName" type="text" placeholder="Hat name"></input>
					<textarea class="form-control" id="hatDescription" placeholder="Hat description"></textarea>
					<input class="form-control" id="datafileName" type="text" placeholder="Datafile name (such as rdominus)"></input>
					<textarea id="xmlContent" placeholder="Datafile Model File (XML) (copy and paste the file's content)" class="form-control" rows="15"></textarea>
					<input class="form-control" id="hatPrice" type="number" placeholder="Hat Price"></input>
					<input type="checkbox" id="isBuyable"> Buyable
					<input type="checkbox" id="isRBX"> Roblox Asset (ALWAYS CHECK IF NOT CUSTOM MADE!!!)
					<br>
					<div class="row">
						<div class="col-xs-6">
							<h4>Mesh File</h4>
							<p>The mesh file should be a correct extension</p>
							<input id="meshFile" type="file"></input>
						</div>
						<div class="col-xs-6">
							<h4>Texture File</h4>
							<p>Should be PNG, but JPG is also supported</p>
							<input id="textureFile" type="file"></input>
						</div>
					</div>
					<br>
					<p>If you are ready to upload, press the button below</p>
					<button class="btn btn-primary fullWidth" id="doUpload">Upload</button>
				</div>
				<div class="col-xs-2">
					<h4>Notes</h4>
					<p>Hats require three file types, a mesh file (its extension must be converted first), a texture file (simply an image file) and a datafile (basically XML)</p>
					<p>Once you upload the hat, it will appear in your inventory and you will get it for free</p>
					<p>The price must be at least 1 coin, otherwise an error will be returned</p>
					<p>The ImageServer will only correctly render your hat if the height and widths are correct, otherwise it'll appear cut in the image</p>
				</div>
			</div>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>