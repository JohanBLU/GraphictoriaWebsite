<?php
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		if (strlen($id) == 0) {
			exit;
		}
	}else{
		exit;
	}
	include_once $_SERVER['DOCUMENT_ROOT'].'/data/assets/config.php';
	try{
		$dbcon = new PDO('mysql:host='.$db_host.';port='.$db_port.';dbname='.$db_name, $db_user, $db_passwd);
		$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
		$dbcon->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	}catch (PDOExpection $e){
		exit;
	}
	
	$stmt = $dbcon->prepare("SELECT * FROM catalog WHERE assetid=:id AND type='tshirts';");
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($stmt->rowCount() == 0) {
		$dbcon = null;
		exit;
	}
	
	header('Content-type: text/xml');
	$dbcon = null;
?>


<roblox xmlns:xmime="http://www.w3.org/2005/05/xmlmime" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="" version="4">
  <External>null</External>
  <External>nil</External>
  <Item class="ShirtGraphic" referent="RBX0">
    <Properties>
      <Content name="Graphic">
        <url>http://xdiscuss.net/data/assets/uploads/<?php echo $result['fileHash']; ?></url>
      </Content>
      <string name="Name">Shirt Graphic</string>
      <bool name="archivable">true</bool>
    </Properties>
  </Item>
</roblox>