<?php
	if (isset($_FILES['meshFile']) && isset($_FILES['textureFile']) && isset($_POST['hatName']) && isset($_POST['hatDescription']) && isset($_POST['hatPrice']) && isset($_POST['isBuyable']) && isset($_POST['RBXAsset']) && isset($_POST['xmlContent']) && isset($_POST['datafileName']) && isset($_POST['csrf_token'])) {
		include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
		$meshFile = $_FILES['meshFile'];
		$textureFile = $_FILES['textureFile'];
		$hatName = $_POST['hatName'];
		$hatDescription = $_POST['hatDescription'];
		$isBuyable = $_POST['isBuyable'];
		$rbxasset = $_POST['RBXAsset'];
		$hatPrice = $_POST['hatPrice'];
		$xmlContent = $_POST['xmlContent'];
		$datafileName = $_POST['datafileName'];
		
		$csrf = $_POST['csrf_token'];
		if ($csrf != $GLOBALS['csrf_token'] || $GLOBALS['loggedIn'] == false) {
			echo 'error';
			exit;
		}
		
		if ($GLOBALS['userTable']['rank'] != 1 && $GLOBALS['userTable']['hatuploader'] == 0) exit;
		
		// Validate if everything matches up in terms of string length and such, assuming admins do not bypass string checks
		if (strlen($hatName) == 0 || strlen($isBuyable) == 0 || strlen($hatDescription) == 0 || strlen($xmlContent) == 0 || strlen($hatPrice) == 0 || strlen($datafileName) == 0) {
			echo 'missing-info';
			exit;
		}
		
		if (strlen($hatName) > 50) {
			echo 'name-too-long';
			exit;
		}
		
		if (strlen($datafileName) > 50) {
			echo 'datafilename-too-long';
			exit;
		}
		
		if (strlen($hatDescription) > 128) {
			echo 'description-too-long';
			exit;
		}
		// Validate some things like, force price to be an integer and such
		if ($isBuyable != "true" && $isBuyable != "false") {
			echo 'illegal-buyable';
			exit;
		}
		
		if ($isBuyable != "false") {
			$buyable = 1;
		}else{
			$buyable = 0;
		}
		
		if ($rbxasset != "false") {
			$rbxassetv = 1;
		}else{
			$rbxassetv = 0;
		}
		
		if (is_numeric($hatPrice) == false && $hatPrice != 0 || $hatPrice < 1) die("price-too-low");
		
		// Check if the datafile name is in use (if this fails, there are several other checks in place)
		$stmt = $GLOBALS['dbcon']->prepare("SELECT id FROM catalog WHERE datafile=:dfile");
		$stmt->bindParam(':dfile', $datafileName, PDO::PARAM_STR);
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			echo 'datafile-mesh-already-exists';
			exit;
		}
		
		// Now move on to the files part, where the upload happens
		// Check if the meshfile is a text file as meshes are pretty much text files
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($finfo, $_FILES['meshFile']['tmp_name']);
		if ($mime != "text/plain") {
			echo 'meshfile-illegalFileType';
			exit;
		}
		
		// Move the file if it is a text/mesh file.
		$uploadDirectory_meshFile = $_SERVER['DOCUMENT_ROOT'].'/data/assets/hats/mesh/';
		$uploadDirectory_modelFile = $_SERVER['DOCUMENT_ROOT'].'/data/assets/hats/models/';
		$uploadDirectory_textureFile = $_SERVER['DOCUMENT_ROOT'].'/data/assets/hats/texture/';
		if (!file_exists($uploadDirectory_meshFile.$datafileName)) {
			if (!move_uploaded_file($_FILES["meshFile"]["tmp_name"], $uploadDirectory_meshFile.$datafileName)) {
				echo 'file-upload-error';
				exit;
			}
		}else{
			echo 'datafile-mesh-already-exists';
			exit;
		}
		
		// Parse the XML file (or model file)
		if (!file_exists($uploadDirectory_modelFile.$datafileName)) {
			$XMLFile = fopen($uploadDirectory_modelFile.$datafileName, "w") or die("file-upload-error");
			fwrite($XMLFile, $xmlContent);
			fclose($XMLFile);
		}else{
			echo 'datafile-mesh-already-exists';
			exit;
		}
		
		// Now parse the texture file
		$check = @getimagesize($_FILES["textureFile"]["tmp_name"]);
		if (!$check) {
			echo 'texture-illegalFileType';
			exit;
		}
		
		$imageFileType = pathinfo($_FILES['textureFile']["name"], PATHINFO_EXTENSION);
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($finfo, $_FILES['textureFile']['tmp_name']);
		if ($imageFileType != "jpg" && $imageFileType != "JPG" && $imageFileType != "png" && $imageFileType != "PNG" && $imageFileType != "jpeg" && $imageFileType != "JPEG" && $mime != "image/png" && $mime != "image/jpeg") {
			echo 'texture-illegalFileType';
			exit;
		}
		
		if (exif_imagetype($_FILES['textureFile']['tmp_name']) != IMAGETYPE_PNG && exif_imagetype($_FILES['textureFile']['tmp_name']) != IMAGETYPE_JPEG) {
			echo 'texture-illegalFileType';
			exit;
		}
		
		if (!file_exists($uploadDirectory_textureFile .$datafileName)) {
			if (!move_uploaded_file($_FILES["textureFile"]["tmp_name"], $uploadDirectory_textureFile.$datafileName)) {
				echo 'file-upload-error';
				exit;
			}
		}else{
			echo 'datafile-mesh-already-exists';
			exit;
		}
		
		// It looks like we've come this far, that we can finally add the hat to the catalog and render its image.
		$stmt = $dbcon->prepare("INSERT INTO catalog (`price`, `creator_uid`, `name`, `description`, `type`, `approved`, `datafile`, `buyable`, `rbxasset`) VALUES (:price, :user, :name, :description, 'hats', 1, :datafile, :buyable, :rbxasset);");
		$stmt->bindParam(':user', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
		$stmt->bindParam(':price', $hatPrice, PDO::PARAM_INT);
		$stmt->bindParam(':name', $hatName, PDO::PARAM_STR);
		$stmt->bindParam(':description', $hatDescription, PDO::PARAM_STR);
		$stmt->bindParam(':datafile', $datafileName, PDO::PARAM_STR);
		$stmt->bindParam(':buyable', $buyable, PDO::PARAM_INT);
		$stmt->bindParam(':rbxasset', $rbxassetv, PDO::PARAM_INT);
		$stmt->execute();
		
		$query = "INSERT INTO renders (`render_id`, `type`) VALUES (:id, 'hats');";
		$stmt = $GLOBALS['dbcon']->prepare($query);
		$stmt->bindParam(':id', $datafileName, PDO::PARAM_INT);
		$stmt->execute();
		
		echo 'success';
	}else{
		echo 'no-file';
	}
?>