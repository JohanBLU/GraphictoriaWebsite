<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	if (!$GLOBALS['loggedIn']) {
		header("Location: /");
		exit;
	}
	if ($GLOBALS['userTable']['rank'] == 0) {
		header("Location: /");
		exit;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo config::getName();?> | Reports</title>
		<?php html::buildHead();?>
	</head>
	<body>
		<?php html::getNavigation();?>
		<div class="container">
			<div class="panel panel-default">
				<div class="panel-heading"><h4 style="color:grey;">Reports</h4></div>
				<div class="panel-body">
					<table class="table table-hover">
						<thead>
							<th>Reported User</th>
							<th>Reason</th>
							<th>Date Reported</th>
						</thead>
						<tbody>
							<?php
								$stmt = $dbcon->prepare("SELECT * FROM reports ORDER BY id DESC;");
								$stmt->execute();
								$count = 0;
								foreach($stmt as $result) {
									$count++;
									echo '<tr><td>'.$result['target'].'</td><td>'.htmlentities($result['reason'], ENT_QUOTES, "UTF-8").'</td><td>'.date('M j Y g:i A', strtotime($result['date'])).'</td></tr>';
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<?php html::buildFooter();?>
	</body>
</html>