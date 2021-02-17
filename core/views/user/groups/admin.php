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
		<title><?php echo config::getName();?> | Group Admin</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<?php html::getNavigation();?>
		<?php html::buildAds();?>
		<div class="container">
			<?php
				if (isset($_GET['id'])) {
					if (is_array($_GET['id'])) {
						echo 'No ID specified';
						exit;
					}
					$id = $_GET['id'];
				}else{
					echo 'No ID specified';
					exit;
				}
				
				$stmt = $dbcon->prepare("SELECT * FROM groups WHERE id = :id");
				$stmt->bindParam(':id', $id, PDO::PARAM_INT);
				$stmt->execute();
				if ($stmt->rowCount() == 0) {
					echo 'Group has not been found';
					exit;
				}
				$result = $stmt->fetch(PDO::FETCH_ASSOC);
				$creator_uid = $result['cuid'];
				$id = $result['id'];
				if ($creator_uid != $GLOBALS['userTable']['id'] and $GLOBALS['userTable']['rank'] == 0) {
					echo 'Access Denied';
					exit;
				}
			?>
			<script src="/core/func/js/groupAdmin.js"></script>
			<div class="col-xs-12 col-sm-8 col-md-8 col-sm-offset-2 col-md-offset-2">
				<div id="aStatus"></div>
				<div class="well profileCard">
					<h3>Group Admin</h3>
					<label>Change Group Description</label>
					<textarea maxlength="256" class="form-control" rows="5" id="descriptionValue" placeholder="Group Description"><?php echo context::secureString($result['description']);?></textarea>
					<button class="btn btn-primary" id="changeDescription" onclick="changeGroupDescription(<?php echo $id;?>);">Save Description</button>
					<a class="btn btn-primary" href="/groups/view/<?php echo $id;?>">Return to Group</a>
				</div>
			</div>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>