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
		<title><?php echo config::getName();?> | Upload Item</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<?php html::getNavigation();?>
		<?php html::buildAds();?>
		<script src="/core/func/js/uploadItem.js?v=2"></script>
		<div class="container">
			<h4>Upload Item</h4>
			<p>In here you can upload an asset. This will cost you 5 coins. If your item gets deleted, you will <b>not</b> be granted a refund. In addition, you also need to wait for your asset to be approved.</p>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-6">
					<div id="uploadStatus"></div>
					<div class="well profileCard">
						<label>Item Name</label>
						<input id="itemNameValue" class="form-control" type="text" placeholder="Item Name"></input>
						<label>Item Description</label>
						<textarea class="form-control" id="itemDescriptionValue" rows="5" placeholder="Describe your item"></textarea>
						<label>Item type</label>
						<select name="itemType" id="itemTypeValue" class="form-control">
							<option value="0">Shirt</option>
							<option value="1">Pants</option>
							<option value="2">T-Shirt</option>
							<option value="3">Decals</option>
						</select>
						<div id="itempriceContainer">
							<label>Item Price</label>
							<input class="form-control" id="itemPriceValue" type="number" placeholder="Item Price"></input>
						</div>
						<label>File to upload</label>
						<input id="fileValue" type="file"></input>
						<br>
						<button id="uploadItem" class="btn btn-primary fullWidth">Upload</button>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-6">
					<b>Information about assets</b>
					<ul>
						<li>Uploading assets will cost you 5 coins. You can only upload 1 item each minute to prevent spamming.</li>
						<li>Please make sure you are not breaking the terms of service. If you upload the wrong content, your account may get a punishment.</li>
						<li>Shirts and Pants should have the resolution of <b>585x559</b>, otherwise it will not upload.</li>
						<li>Accepted formats are <b>PNG</b> and <b>JPG</b>.</li>
						<li>Your item will go through approval. You will receive your item after approval, otherwise it'll be deleted.</li><br>
							<img class="img-responsive" src="/data/templates/clothing4.png">
						</li>
					</ul>
				</div>
			</div>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>